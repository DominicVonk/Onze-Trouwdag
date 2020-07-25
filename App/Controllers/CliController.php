<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserAccount;

class CliController extends \DraftMVC\DraftController
{
    public function make($email, $id)
    {
        $password = md5(time());
        $user = new User();
        $user->email = $email;
        $user->password = $password;
        $user->save();
        $userAccount = new UserAccount();
        $userAccount->user_id = (int) $user->id;
        $userAccount->account_id = (int) $id;

        $userAccount->save();
        echo $password;
    }

    public function renew($email)
    {
        $user = User::findOne('email = ?', $email);
        $password = md5(time());
        $user->password = $password;
        $user->save();
        echo $password;
    }

    public function link($email, $id)
    {
        $user = User::findOne('email = ?', [$email]);
        $userAccount = new UserAccount();
        $userAccount->user_id = (int) $user->id;
        $userAccount->account_id = (int) $id;

        $userAccount->save();
    }

    private function requestData()
    {
        return \json_decode(\file_get_contents('php://input'), true);
    }
}
