<?php

namespace app\models;

use Yii;
use app\models\Vocabulary;
use app\models\ISearching;

class SearchingVector implements ISearching {

    /**
     *
     * @var array 
     */
    protected $results;

    /**
     *
     * @var string 
     */
    protected $word;
    
    /**
     *
     * @var array
     */
    protected $letters;    

    /**
     * 
     * @param string $word
     */
    public function setWord($word) {
        $this->results = [];
        $this->word = $word;
        $this->letters = Multibyte::stringToArray($this->word);
    }

    public function run() {
        if (isset($this->word) && mb_strlen($this->word)) {
            $letters_exists = [];
            $sql_letters = [];

            $word_arr = array_unique($this->letters);
            foreach ($word_arr as $letter) {
                if (isset(Vector::$letters[$letter])) {
                    $letters_exists[] = Vector::$letters[$letter];
                }
            }

            $letters_diff = array_diff(Vector::$letters, $letters_exists);
            if (count($letters_diff)) {
                foreach ($letters_diff as $letter) {
                    $sql_letters[] = '`vkt`.`' . $letter . '` = 0';
                }
            } else {
                $sql_letters[] = '1 LIMIT 0,0';
            }

            if (count($sql_letters) >= 2) {
                $sql = 'SELECT v.vocabulary_id, v.vocab 
                        FROM vocabulary v
                        LEFT JOIN vector vkt ON v.vocabulary_id = vkt.id 
                        WHERE ' . implode(' AND ', $sql_letters);
                $result = \Yii::$app->db->createCommand($sql)->queryAll();
                if ($result) {
                    foreach ($result as $row) {
                        $this->setResult($row['vocab']);
                    }
                }
            }
        }
    }

    /**
     * 
     * @param type $new_word
     */
    private function setResult($new_word) {
        $new_word_arr = Multibyte::stringToArray($new_word);
        
        foreach ($this->letters as $letter) {
            $letter_pos = array_search($letter, $new_word_arr);
            if (is_integer($letter_pos)) {
                $new_word_arr[$letter_pos] = '';
            }
        }
        foreach ($new_word_arr as $letter) {
            if (strlen($letter) > 0) {
                return ;
            }
        }
        if ($new_word == $this->word) {
            return ;
        }
        
        $lenght = mb_strlen($new_word);
        $this->results[$lenght][] = ['vocab' => $new_word];
    }

    public function getResults() {
        return $this->results;
    }

}
