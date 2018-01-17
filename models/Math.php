<?php

namespace app\models;

class Math {

    /**
     * 
     * @param int $number
     * @return int
     */
    public static function factorial($number) {
        if ($number <= 1) {
            return 1;
        } else {
            return ($number * self::factorial($number - 1));
        }
    }

}
