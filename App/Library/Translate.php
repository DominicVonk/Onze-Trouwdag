<?php
namespace App\Library;
class Translate
{
    private $translation;
    public function __construct($lang)
    {
        $this->translation = json_decode(file_get_contents(APP_FOLDER . '/i18n/' . $lang . '.json'), true);
    }
    public function translate($where)
    {
        $where = explode('.', $where);
        $translation = $this->translation;
        foreach ($where as $name) {
            $translation = $translation[$name];
        }
        return $translation;
    }
}
