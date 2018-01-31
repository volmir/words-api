<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vector".
 */
class Vector extends \yii\db\ActiveRecord
{

    /**
     *
     * @var array
     */
    public static $letters = array(
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
        'ь' => 'mgk', 
        'ы' => 'yi', 
        'ъ' => 'tvd',
        'э' => 'ee', 
        'ю' => 'yu', 
        'я' => 'ya',
    );

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vector';
    }
    
}
