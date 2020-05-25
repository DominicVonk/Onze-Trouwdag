<?php
namespace App\Library;
class Session
{
    public static function start()
    {
        \SecSession\SecSession::start();
    }
    public static function get($name)
    {
        return \SecSession\SecSession::get($name);
    }
    public static function set($name, $value)
    {
        \SecSession\SecSession::set($name, $value);
    }
    public static function destroy()
    {
        \SecSession\SecSession::destroy();
    }
}
