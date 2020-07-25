<?php

namespace App\Models;

class Account extends \DraftMVC\DraftModel
{
    public function __get($var)
    {
        if ($var === 'users') {
            return \App\Models\UserAccount::find('account_id = ?', [$this->data['id']])->map(fn ($e) => $e->user);
        } else {
            return parent::__get($var);
        }
    }
}
