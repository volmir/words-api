<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vocabulary".
 *
 * @property int $vocabulary_id
 * @property string $vocab
 */
class Vocabulary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vocabulary';
    }

    /**
     * 
     * @param string $word
     * @return string
     */
    public static function clear($word) {
        $word = trim($word);
        $word = strip_tags($word);
        $word = stripslashes($word);
        $word = htmlspecialchars($word);
        $word = preg_replace('/[^а-яА-ЯёЁ]/ui', '', $word);
        
        return $word;
    }
}
