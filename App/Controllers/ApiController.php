<?php
namespace App\Controllers;

use \App\Library\Session;
use \App\Models\Code;

class ApiController extends \DraftMVC\DraftController
{
    public function code()
    {
        $requestData = $this->requestData();
        $codeRows = Code::find(' code = ? ', [$requestData['code']]);
        foreach ($codeRows as $codeRow) {
            if (stripos($_SERVER['HTTP_HOST'], $codeRow->account->url) !== false) {
                Session::set('code', $codeRow->code);
                return [
                    'status' => 200,
                    'data' => [
                        'name' => $codeRow->name,
                        'adults' => $codeRow->adults,
                        'children' => $codeRow->children,
                        'status' => $codeRow->status,
                    ],
                ];
            }
        }
        return [
            'status' => 404,
            'message' => 'Code not found',
        ];
    }

    private function requestData()
    {
        return \json_decode(\file_get_contents('php://input'), true);
    }

    public function create()
    {

        $code = new Code();
        $code->name = $_POST['name'];
        $code->code = $_POST['code'];
        $code->internal_name = $_POST['internal_name'];
        $code->adults = $_POST['adults'] ?: 0;
        $code->children = $_POST['children'] ?: 0;
        $code->visits = 0;
        $code->account = 1;
        $code->email = '';
        $code->type = $_POST['type'];
        $code->status = $_POST['status'];
        $code->save();
        $this->redirect('/CAPSKDHR');
    }
    public function edit($id)
    {
        $code = Code::findOne(' id = ? ', [$id]);
        $code->name = $_POST['name'];
        $code->code = $_POST['code'];
        $code->internal_name = $_POST['internal_name'];
        $code->adults = $_POST['adults'];
        $code->children = $_POST['children'];
        $code->status = $_POST['status'];
        $code->type = $_POST['type'];
        $code->save();
        $this->redirect('/CAPSKDHR');
    }
    public function attendence()
    {
        $requestData = $this->requestData();
        $code = Code::findOne('code = ?', [Session::get('code')]);
        if (isset($requestData['volwassene'])) {
            $code->adults = $requestData['volwassene'];
        }
        if (isset($requestData['kinderen'])) {
            $code->children = $requestData['kinderen'];
        }
        if (isset($requestData['status'])) {
            $code->status = $requestData['status'];
        }
        curl_setopt_array($ch = curl_init(), array(
            CURLOPT_URL => "https://api.pushed.co/1/push",
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                "app_key" => "8sqKQhqMuQooIcQkpSyZ",
                "app_secret" => "ucdXx9oMCzortCye1BLjZ9ynB1m1ypPpNJymOfTMvy6WpkT3XSnzdlvlY36O98Ma",
                "target_type" => "app",
                "content" => $code->internal_name . ' heeft zich ' . ($code->status === 1 ? 'aangemeld' : 'afgemeld') . '.',
                "content_type" => "url",
                "content_extra" => "https://onze-trouwdag.nl/CAPSKDHR",
            ),
            CURLOPT_SAFE_UPLOAD => true,
            CURLOPT_RETURNTRANSFER => true,
        ));
        curl_exec($ch);
        curl_close($ch);
        $code->save();
    }
}
