<?php

namespace app\components\helpers;

class Translit {

    protected static $letters = array(
        'а' => 'a', 
        'б' => 'b', 
        'в' => 'v',
        'г' => 'g', 
        'д' => 'd', 
        'е' => 'e',
        'ё' => 'ye', 
        'ж' => 'zh', 
        'з' => 'z',
        'и' => 'i', 
        'й' => 'y', 
        'к' => 'k',
        'л' => 'l', 
        'м' => 'm', 
        'н' => 'n',
        'о' => 'o', 
        'п' => 'p', 
        'р' => 'r',
        'с' => 's', 
        'т' => 't', 
        'у' => 'u',
        'ф' => 'f', 
        'х' => 'kh', 
        'ц' => 'ts',
        'ч' => 'ch', 
        'ш' => 'sh', 
        'щ' => 'shch',
        'ь' => '\'', 
        'ы' => 'y', 
        'ъ' => '\'',
        'э' => 'e', 
        'ю' => 'yu', 
        'я' => 'ya',
    );

    /**
     * 
     * @param string $string
     * @return string
     */
    public static function convert($string) {
        $string = (string) $string;
        $string = strip_tags($string);
        $string = str_replace(array("\n", "\r"), " ", $string);
        $string = preg_replace("/\s+/", ' ', $string);
        $string = trim($string);
        $string = function_exists('mb_strtolower') ? mb_strtolower($string) : strtolower($string);
        
        $string = strtr($string, self::letters);
        
        $string = preg_replace("/[^0-9a-z-_ ]/i", "", $string);

        return $string;
    }

}
