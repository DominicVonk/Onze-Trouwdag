<?php
namespace App\Models;
class Code extends \DraftMVC\DraftModel {
    public function __get($var) {
        if ($var === 'account') {
            return \App\Models\Account::findOne('id = ?', [$this->data['account']]);
        } else {
            return parent::__get($var);
        }
    }
}
