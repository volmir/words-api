<?php

namespace app\models;

use Yii;
use app\models\Combination;
use app\models\Vocabulary;

class Game {

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
        $this->word = $word;
    }

    public function connect() {
        $this->mysqli = Yii::$app->mysqli::getInstance();
    }

    public function run() {
        $this->connect();

        $combination = new Combination();
        $combination->setWord($this->word);

        $count = 0;
        foreach ($combination->find() as $word) {
            $count++;

            $this->combinations[] = '\'' . $word . '\'';
            if (count($this->combinations) == 100000) {
                $this->checkCombination();
                $this->combinations = [];
            }
        }
        $this->checkCombination();
        $this->combinations = [];
    }

    protected function checkCombination() {
        $sql = "SELECT `vocab` 
                FROM `vocabulary` 
                WHERE `vocab` IN (" . implode(',', $this->combinations) . ") 
                GROUP BY `vocab`";
        if ($result = $this->mysqli->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $lenght = mb_strlen($row['vocab']);
                $this->results[$lenght][] = ['vocab' => $row['vocab']];
            }
            $result->close();
        }
    }

    public function getResults() {
        return $this->results;
    }

}
