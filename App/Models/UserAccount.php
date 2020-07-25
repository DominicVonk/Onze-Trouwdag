<?php

namespace App\Models;

class UserAccount extends \DraftMVC\DraftModel
{
    public function __get($var)
    {
        if ($var === 'account') {
            return \App\Models\Account::findOne('id = ?', [$this->data['account_id']]);
        } else if ($var === 'user') {
            return \App\Models\User::findOne('id = ?', [$this->data['user_id']]);
        } else {
            return parent::__get($var);
        }
    }
}
