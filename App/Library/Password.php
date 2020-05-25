<?php
namespace App\Library;
class Password
{
    public static function generateSalt($len = 40)
    {
        $createfrom = '!@#$%^&*()_+QWERTYUIOP{}ASDFGHJKL:"|ZXCVBNM<>?1234567890-=qwertyuiop[]asdfghjkl;\'zxcvbnm,./';
        $createfromLen = strlen($createfrom);
        $salt = '';
        for ($i = 0; $i < $len; $i++) {
            $salt .= $createfrom[mt_rand(0, $createfromLen-1)];
        }
        return $salt;
    }
    public static function create($password, $salt)
    {
        $salt2 = Config::get('general.salt');
        $salt2len = strlen($salt2)/2;
        $saltlen = strlen($salt)/2;

        $newPassword = strrev(substr($salt2, $salt2len, $salt2len));
        $newPassword .= substr($salt, 0, $saltlen);
        $newPassword .= $password;
        $newPassword .= strrev(substr($salt, $saltlen, $saltlen));
        $newPassword .= substr($salt2, 0, $salt2len);

        return hash('sha512', $newPassword);
    }
    public static function check($password, $userinput, $salt)
    {
        return hash_equals($password, self::create($userinput, $salt));
    }
}
