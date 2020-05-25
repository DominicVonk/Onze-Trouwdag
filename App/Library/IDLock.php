<?php
namespace App\Library;
class IDLock
{
    public static function lock($id, $datetime)
    {
        return base_convert(rand(11, 99) . $id . $datetime, 10, 36);
    }
    public static function unlock($str)
    {
        $dateTimeID = base_convert($str, 36, 10);
        $dateTime = substr($dateTimeID, -14);
        $id = substr($dateTimeID, 2, -14);

        $date = substr($dateTime, 0, 4) . '-' .substr($dateTime, 4, 2) . '-' . substr($dateTime, 6, 2);
        $time = substr($dateTime, 8, 2) . ':' . substr($dateTime, 10, 2) . ':' . substr($dateTime, 12, 2);

        return array('id' => $id, 'datetime' => $date . ' ' .  $time);
    }
}
