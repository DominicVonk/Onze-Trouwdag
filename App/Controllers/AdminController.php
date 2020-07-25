<?php

namespace App\Controllers;

use \App\Library\Session;
use App\Models\Account;
use \App\Models\Code;

class AdminController extends \DraftMVC\DraftController
{
    public $user;
    public $currentAccount;
    public $accounts;
    public function __construct()
    {
        if (!Session::get('user')) {
            $this->redirect('/login');
        } else {
            if (!Session::get('account')) {
                Session::set('account', Session::get('user')->accounts->nth(0)->id);
            }
        }
    }

    public function init()
    {
        $this->user = Session::get('user');
        $this->currentAccount = Session::get('account');
        $this->accounts = $this->user->accounts->toArray();
        if ($this->view) {
            $this->view->user = $this->user;
            $this->view->currentAccount = $this->currentAccount;
            $this->view->accounts = $this->accounts;
        }
    }
    public function saveText()
    {
        $account = Account::findOne('id = ?', Session::get('account'));
        $account->main_text = $_POST['text'];
        $this->redirect('/admin/content/main');
    }

    public function switchAccount()
    {
        $account = $_GET['account'];
        Session::set('account', $account);
        $this->redirect('/admin');
    }


    public function logout()
    {
        Session::destroy();
        $this->redirect('/login');
    }

    private function requestData()
    {
        return \json_decode(\file_get_contents('php://input'), true);
    }

    public function apiCreate()
    {
        $code = new Code();
        $code->name = $_POST['name'];
        $code->code = $_POST['code'];
        $code->internal_name = $_POST['internal_name'];
        $code->adults = $_POST['adults'] ?: 0;
        $code->children = $_POST['children'] ?: 0;
        $code->visits = 0;
        $code->account_id = Session::get('account');
        $code->email = '';
        $code->type = $_POST['type'];
        $code->status = $_POST['status'];
        $code->save();
        $this->redirect('/admin/invitees');
    }
    public function apiEdit($id)
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
        $this->redirect('/admin/invitees');
    }
    public function create()
    {
        $this->setView(new \DraftMVC\DraftViewTwig('admin/manage'));
        $this->init();
        $this->view->code = (new Code())->dump();
        $this->view->code['code'] = rand(1111, 9999);
        while (Code::findOne('code = ?', [$this->view->code['code']]) !== false) {
            $this->view->code['code'] = rand(1111, 9999);
        }
        $this->view->method = 'create';
    }
    public function edit($id)
    {
        $this->setView(new \DraftMVC\DraftViewTwig('admin/manage'));
        $this->init();
        $this->view->code = Code::findOne(' id = ? ', [$id])->dump();
        $this->view->method = 'edit';
    }
    public function contentMain()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $this->view->account = $account->toArray();
    }
    public function contentMainSave()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $account->main_text = $_POST['content'];
        $account->save();

        $this->redirect('/admin/content/main');
    }

    public function contentProgram()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $imagePath = PUBLIC_PATH . '/assets/gallery/' . $account->id . '/program';
        if (is_dir($imagePath)) {
            $photos = glob(realpath(__DIR__ . '/../..') . '/public/assets/gallery/' . $account->id . '/program/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
            $photos = array_map(function ($e) {
                return str_replace(realpath(__DIR__ . '/../..') . '/public', '', $e);
            }, $photos);
            natsort($photos);
        } else {
            $photos = [];
        }
        $this->view->gallery = $photos;
        $this->view->account = $account->toArray();
    }
    public function contentProgramSave()
    {
        $account = Account::findOne('id = ?', Session::get('account'));
        $files = $_FILES['image'];
        $imagePath = PUBLIC_PATH . '/assets/gallery/' . $account->id . '/program/';
        if (count($files['name'])) {
            if (is_dir($imagePath)) {
                rmdir($imagePath);
            }
            mkdir($imagePath);
            for ($i = 0; $i < count($files['name']); $i++) {
                $file = ['name' => $files['name'][$i], 'tmp_name' => $files['tmp_name'][$i], 'size' =>  $files['size'][$i]];
                $_filepath = $imagePath . $file['name'];
                move_uploaded_file($file['tmp_name'], $_filepath);
            }
        }
        $account->program_text = $_POST['content'];
        $account->save();

        $this->redirect('/admin/content/program');
    }
    public function contentLocation()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $this->view->account = $account->toArray();
    }
    public function contentLocationSave()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $account->location_text = $_POST['content'];
        $account->save();

        $this->redirect('/admin/content/location');
    }
    public function contentAddress()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $this->view->account = $account->toArray();
    }
    public function contentAddressSave()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $account->address = $_POST['content'];
        $account->save();

        $this->redirect('/admin/content/address');
    }
    public function contentPhotos()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $this->view->account = $account->toArray();
    }
    public function contentPhotosSave()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $account->photos = $_POST['content'];
        $account->save();

        $this->redirect('/admin/content/photos');
    }
    public function contentPresent()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $this->view->account = $account->toArray();
    }
    public function contentPresentSave()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $file = $_FILES['image'];
        if ($file['size']) {
            $_filepath = '/assets/present_tip/' . $account->url .  substr($file['name'], strrpos($file['name'], '.'));
            $_file = (PUBLIC_PATH . $_filepath);
            move_uploaded_file($file['tmp_name'], $_file);

            $account->present_image = $_filepath;
        }
        $account->present_text = $_POST['content'];
        $account->save();

        $this->redirect('/admin/content/present');
    }
    public function theme()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $this->view->account = $account->toArray();
    }
    public function settings()
    {
        $account = Account::findOne('id = ?', Session::get('account'));

        $this->view->account = $account->toArray();
    }
    public function settingsSave()
    {
        $account = Account::findOne('id = ?', Session::get('account'));
        $account->enddate = $_POST['enddate'];
        $account->weddingdate = $_POST['weddingdate'];
        $account->our_program_button = $_POST['our_program_button'];
        $account->our_wedding_day = $_POST['our_wedding_day'];
        $account->pushnotification = $_POST['pushnotification'];
        $account->save();
        $this->redirect('/admin/settings');
    }
    public function themeSave()
    {
        $account = Account::findOne('id = ?', Session::get('account'));
        $file = $_FILES['image'] ?: null;
        if ($file['size']) {
            $_filepath = '/assets/background_image/' . $account->url .  substr($file['name'], strrpos($file['name'], '.'));
            $_file = (PUBLIC_PATH . $_filepath);
            move_uploaded_file($file['tmp_name'], $_file);
            $account->background_image = $_filepath;
        }
        $file = $_FILES['logo'] ?: null;
        if ($file['size']) {
            $_filepath = '/assets/logo/' . $account->url .  substr($file['name'], strrpos($file['name'], '.'));
            $_file = (PUBLIC_PATH . $_filepath);
            move_uploaded_file($file['tmp_name'], $_file);
            $account->logo_path = $_filepath;
        }
        $file = $_FILES['oglogo'] ?: null;
        if ($file['size']) {
            $_filepath = '/assets/og_logo/' . $account->url .  substr($file['name'], strrpos($file['name'], '.'));
            $_file = (PUBLIC_PATH . $_filepath);
            move_uploaded_file($file['tmp_name'], $_file);
            $account->oglogo = $_filepath;
        }
        $account->accent_color = $_POST['accent_color'];
        $account->save();
        $this->redirect('/admin/theme');
    }
    public function invitees($day = 0)
    {
        if ($day == 0) {
            $codes = Code::find('account_id = ? AND status < ?', [Session::get('account'), 3])->map(function ($a) {
                return $a->dump();
            });
        } else if ($day == 1) {
            $codes = Code::find('account_id = ? AND status < ? AND (type = ? OR type = ?)', [Session::get('account'), 3, 0, 1])->map(function ($a) {
                return $a->dump();
            });
        } else {
            $codes = Code::find('account_id = ? AND status < ? AND (type = ? OR type = ?)', [Session::get('account'), 3, 1, 2])->map(function ($a) {
                return $a->dump();
            });
        }
        $codesEntered = $codes->filter(function ($code) {
            return $code['status'] > 0;
        });
        $codesNotEntered = $codes->filter(function ($code) {
            return $code['status'] == 0;
        });

        $codesEntered = $codesEntered->sort(function ($a, $b) {
            return ($a['internal_name'] > $b['internal_name'] ? 1 : -1);
        });
        $codesNotEntered = $codesNotEntered->sort(function ($a, $b) {
            return ($a['internal_name'] > $b['internal_name'] ? 1 : -1);
        });
        $codesEntered = $codesEntered->toArray();
        $codesNotEntered = $codesNotEntered->toArray();
        $this->view->day = $day;
        $codes = array_merge($codesEntered, $codesNotEntered);
        $adults = 0;
        $children = 0;
        foreach ($codes as $code) {
            if ($code['status'] < 2) {
                $adults += $code['adults'];
                $children += $code['children'];
            }
        }
        $this->view->adults = $adults;
        $this->view->children = $children;
        $this->view->codes = $codes;
    }
}
