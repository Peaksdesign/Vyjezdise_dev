<?php


namespace App\Tools;
use Nette\Utils\Json;

/**
 * Class IPLocator
 * @package App\Tools
 * @author Josef Banya
 */
class IPLocator
{
    /**
     * @return bool|string
     */
    public static function getClientIp() {
        $ip = null;
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ip = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
           return ($ip === 'UNKNOWN') ? false : $ip;
    }


    public static function getIpInfo()
    {
        // create curl resource
        $ip = self::getClientIp();
        if (!$ip) return false;
        $url = 'https://ipinfo.io/' . $ip;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);

        if (curl_error($ch)) {
            return false;
        }
        $output = Json::decode($output);
        curl_close($ch);
        if (isset($output->loc) && $output->loc !== '') {
            return $output->loc;
        } else {
            return false;
        }
    }
}