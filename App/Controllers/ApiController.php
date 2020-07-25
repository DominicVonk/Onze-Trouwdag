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
            if ($_SERVER['HTTP_HOST'] === $codeRow->account->url) {
                Session::set('code', $codeRow->id);
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
        $code = Code::findOne('id = ?', [Session::get('code')]);
        if (isset($requestData['volwassene'])) {
            $code->adults = $requestData['volwassene'];
        }
        if (isset($requestData['kinderen'])) {
            $code->children = $requestData['kinderen'];
        }
        if (isset($requestData['status'])) {
            $code->status = $requestData['status'];
        }
        $code->save();
        if ($code->account->pushnotification) :
            $pushc = explode(',', $code->account->pushnotification);
            foreach ($pushc as $push) :
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://maker.ifttt.com/trigger/subscription/with/key/' . $push . '');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
                    "value1" => ($code->internal_name ?: $code->name) . ' heeft zich ' . ($code->status === 1 ? 'aangemeld' : 'afgemeld') . '.'
                )));
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
            endforeach;
        endif;
    }
}
