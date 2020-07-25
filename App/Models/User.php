<?php

namespace App\Models;

use App\Library\Password;

class User extends \DraftMVC\DraftModel
{
    public function __get($var)
    {
        if ($var === 'accounts') {
            return \App\Models\UserAccount::find('user_id = ?', [$this->data['id']])->map(fn ($e) => $e->account);
        } else {
            return parent::__get($var);
        }
    }
    public function __set($variable, $value)
    {

        if ($variable === 'password') {
            $this->data['salt'] = Password::generateSalt();
            $this->data['password'] = Password::create($value, $this->data['salt']);
        } else {
            parent::__set($variable, $value);
        }
    }
}
