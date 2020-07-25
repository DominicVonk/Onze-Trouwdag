<?php

namespace App\Controllers;

use App\Library\Password;
use \App\Library\Session;
use \App\Models\Code;
use App\Models\User;

class LoginController extends \DraftMVC\DraftController
{
    public function __construct()
    {
        if (Session::get('user')) {
            $this->redirect('/admin');
        }
    }

    private function requestData()
    {
        return \json_decode(\file_get_contents('php://input'), true);
    }

    public function login()
    {
        $this->view->islogin = true;
    }
    public function loggingIn()
    {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $user = User::findOne('email = ?', [$user]);
        if (Password::check($user->password, $pass, $user->salt)) {
            Session::set('user', $user);
            $this->redirect('/admin');
        }
        $this->redirect('/login');
    }
}
