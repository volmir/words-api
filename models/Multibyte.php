<?php

namespace app\models;

class Multibyte {

    /**
     * 
     * @param string $string
     * @return array
     */
    public static function stringToArray($string) {
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, 0, 1, "UTF-8");
            $string = mb_substr($string, 1, $strlen, "UTF-8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }

}
