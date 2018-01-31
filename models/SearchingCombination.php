<?php

namespace app\models;

use Yii;
use app\models\Combination;
use app\models\Vocabulary;
use app\models\ISearching;
use app\components\adapter\MySQLi;

class SearchingCombination implements ISearching {

    /**
     *
     * @var array 
     */
    protected $combinations;

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
     * @var \mysqli
     */
    private $mysqli;

    public function setWord($word) {
        $this->combinations = [];
        $this->results = [];
        $this->word = $word;
    }

    public function run() {
        $combination = new Combination();
        $combination->setWord($this->word);

        $count = 0;
        foreach ($combination->find() as $word) {
            $count++;

            $this->combinations[] = $word;
            if ($count % 100000 == 0) {
                $this->checkCombination();
                $this->combinations = [];
            }
        }
        $this->checkCombination();
        $this->combinations = [];
    }

    protected function checkCombination() {
        $type = 'mysqli';

        if ($type == 'cache') {
            $this->checkCombinationCache();
        } elseif ($type == 'mysqli') {
            $this->checkCombinationMysqli();
        }
    }

    protected function checkCombinationMysqli() {
        $mysqli = MySQLi::getInstance();
        $sql = "SELECT `vocab` 
                FROM `vocabulary` 
                WHERE `vocab` IN ('" . implode('\',\'', $this->combinations) . "') 
                GROUP BY `vocab`";
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $this->setResult($row['vocab']);
            }
            $result->close();
        }
    }

    protected function checkCombinationCache() {
        if (count($this->combinations)) {
            $result = Yii::$app->memCache->multiGet($this->combinations);
            if (isset($result)) {
                foreach ($result as $key => $word) {
                    if ($word) {
                        $this->setResult($word);
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
        $lenght = mb_strlen($new_word);
        if (!isset($this->results[$lenght])) {
            $this->results[$lenght][] = ['vocab' => $new_word];
        } else {
            $finded = false;
            foreach ($this->results[$lenght] as $word) {
                if ($new_word == $word['vocab']) {
                    $finded = true;
                    break;
                }
            }
            if (!$finded) {
                $this->results[$lenght][] = ['vocab' => $new_word];
            }
        }
    }

    public function getResults() {
        return $this->results;
    }

}
