<?php

namespace App;

class EngineMiscFunctions
{

    public static function jsonp_decode($jsonp, $assoc = false)
    {
        // PHP 5.3 adds depth as third parameter to json_decode

        $jsonp = substr($jsonp, strpos($jsonp, '('));
        $jsonp = str_replace('&quot;', '"', $jsonp);
        $jsonp = trim(trim($jsonp), '()');
        $jsonp = json_decode($jsonp, $assoc);

        return $jsonp;
    }

}
