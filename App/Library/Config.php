<?php
namespace App\Library;
class Config
{
    public static function load($file)
    {
        \DraftMVC\DraftConfig::load($file);
    }
    public static function get($get)
    {
        return \DraftMVC\DraftConfig::get($get);
    }
}
