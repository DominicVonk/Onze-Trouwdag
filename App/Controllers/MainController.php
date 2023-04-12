<?php

namespace App\Controllers;

use App\Library\Session;
use App\Models\Account;
use App\Models\Code;

class MainController extends \DraftMVC\DraftController
{
    public function index()
    {
        $accounts = Account::findAll();
        foreach ($accounts as $account) {
            if ($account->url === $_SERVER['HTTP_HOST']) {
                $account = $account->dump();
                $account['infuture'] = $account['weddingdate'] > date('Y-m-d') ? true : false;
                $account['weddingdate'] = strftime('%e %B %Y', strtotime($account['weddingdate']));
                $this->view->account = $account;
            }
        }
    }
    public function gallery($account, $type)
    {
        $imagePath = PUBLIC_PATH . '/assets/gallery/' . $account->id . '/' . $type . '';
        if (is_dir($imagePath)) {
            $photos = glob(realpath(__DIR__ . '/../..') . '/public/assets/gallery/' . $account->id . '/' . $type . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
            $photos = array_map(function ($e) {
                return str_replace(realpath(__DIR__ . '/../..') . '/public', '', $e);
            }, $photos);
            natsort($photos);
        } else {
            $photos = [];
        }
        if (count($photos) == 0) {
            return '';
        } else {
            return '<div class="gallery">
            <div><img src="' . implode('"></div>
            <div><img src="', $photos) . '"></div>
        </div>';
        }
    }
    public function main($code = null)
    {
        $self = $this;
        if ($code) {
            $found = false;
            $codeRows = Code::find(' code = ? ', [$code]);
            foreach ($codeRows as $codeRow) {
                if (stripos($_SERVER['HTTP_HOST'], $codeRow->account->url) !== false) {
                    Session::set('code', $codeRow->id);
                    $found = true;
                }
            }
            if (!$found) {
                $this->redirect('/');
                return;
            }
        }
        if (!Session::get('code')) {
            $this->redirect('/');
        }
        $code = Code::findOne('`id` = ? AND `status` < ?', [Session::get('code'), 3]);
        if ($code->visits == '') {
            $code->visits = 0;
        } else {
            $code->visits = intval($code->visits);
        }
        if (!$code->ignore_count) {
            $code->visits += 1;
        }
        $code->save();
        $this->view->code = $code->dump();
        $this->personCount = ($code->adults + $code->children);
        $this->view->code_json = json_encode($this->view->code);
        $this->view->addFilter('m', function ($word) use ($self) {
            return $self->multiWord($word);
        }, array());
        $this->view->addFunction('nl2br', function ($v) {
            return nl2br($v);
        }, ['is_safe' => ['html']]);
        $this->view->account = $code->account->dump();
        $this->view->account['program_text'] = str_replace('[gallery]', $this->gallery($code->account, 'program'), $this->view->account['program_text']);
        $this->view->formsubscription = true;//$code->account->enddate >= date('Y-m-d');

        $this->view->formmaybeopen = true;//$code->account->weddingdate > date('Y-m-d') ? true : false;
        if (substr($code->account->enddate, 0, 4) > date('Y')) {
            $this->view->enddate =  strftime('%e %B %Y',  strtotime(($code->account->enddate)));
        } else {
            $this->view->enddate =  strftime('%e %B',  strtotime(($code->account->enddate)));
        }
    }
    public function photos($type)
    {
        if (!Session::get('code')) {
            $this->redirect('/');
        }
        $code = Code::findOne('`id` = ? AND `status` < ?', [Session::get('code'), 3]);
        $photos = glob(realpath(__DIR__ . '/../..') . '/public/assets/photos/' . $code->account_id . '/' . $type . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
        $photos = array_map(function ($e) {
            return str_replace(realpath(__DIR__ . '/../..') . '/public', '', $e);
        }, $photos);
        natsort($photos);
        $this->view->photos = $photos;
    }


    public function multiWord($word)
    {
        if ($this->personCount == 1) {
            return $word;
        }
        $words = json_decode(file_get_contents(DATA_FOLDER . '/words.json'), true);
        if (array_key_exists($word, $words)) {
            return $words[$word];
        }
        return $word;
    }
}
