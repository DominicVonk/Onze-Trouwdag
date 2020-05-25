<?php
namespace App\Controllers;

use \App\Library\Session;
use \App\Models\Code;

class MainController extends \DraftMVC\DraftController
{
    public function main($code = null)
    {
        $self = $this;
        if ($code) {
            $found = false;
            $codeRows = Code::find(' code = ? ', [$code]);
            foreach ($codeRows as $codeRow) {
                if (stripos($_SERVER['HTTP_HOST'], $codeRow->account->url) !== false) {
                    Session::set('code', $codeRow->code);
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
        $code = Code::findOne('`code` = ? AND `status` < ?', [Session::get('code'), 3]);
        if ($code->visits == '') {
            $code->visits = 0;
        } else {
            $code->visits = intval($code->visits);
        }
        $code->visits += 1;
        $code->save();
        $this->view->code = $code->dump();
        $this->personCount = ($code->adults + $code->children);
        $this->view->code_json = json_encode($this->view->code);
        $this->view->addFilter('m', function ($word) use ($self) {return $self->multiWord($word);}, array());
    }
    public function photos($type)
    {
        if (!Session::get('code')) {
            $this->redirect('/');
        }
        $photos = glob(realpath(__DIR__ . '/../..') . '/public/static/img/photos/' . $type . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
        $photos = array_map(function ($e) {return str_replace(realpath(__DIR__ . '/../..') . '/public', '', $e);}, $photos);
        natsort($photos);
        $this->view->photos = $photos;
    }
    public function overview($day = 0)
    {
        $this->setView(new \DraftMVC\DraftViewTwig('admin/overview'));
        if ($day == 0) {
            $codes = array_map(function ($a) {return $a->dump();}, Code::find('account = ? AND status < ?', [1, 3]));
        } else if ($day == 1) {
            $codes = array_map(function ($a) {return $a->dump();}, Code::find('account = ? AND status < ? AND (type = ? OR type = ?)', [1, 3, 0, 1]));
        } else {
            $codes = array_map(function ($a) {return $a->dump();}, Code::find('account = ? AND status < ? AND (type = ? OR type = ?)', [1, 3, 1, 2]));
        }
        $codesEntered = array_filter($codes, function ($code) {return $code['status'] > 0;});
        $codesNotEntered = array_filter($codes, function ($code) {return $code['status'] == 0;});

        usort($codesEntered, function ($a, $b) {
            return ($a['internal_name'] > $b['internal_name'] ? 1 : -1);
        });
        usort($codesNotEntered, function ($a, $b) {
            return ($a['internal_name'] > $b['internal_name'] ? 1 : -1);
        });
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
    public function create()
    {
        $this->setView(new \DraftMVC\DraftViewTwig('admin/manage'));
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
        $this->view->code = Code::findOne(' id = ? ', [$id])->dump();
        $this->view->method = 'edit';
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
