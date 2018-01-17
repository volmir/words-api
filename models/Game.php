<?php

namespace app\models;

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

    public function setWord($word) {
        $this->word = $word;
    }
    
    public function run() {
        $combination = new Combination();
        $combination->setWord($this->word);
        $combination->run();

        $this->combinations = $combination->getCombinations();
        $this->checkCombination();
    }

    protected function checkCombination() {
        foreach ($this->combinations as $lenght => $combination) {
            $this->results[$lenght] = Vocabulary::find()
                    ->select(['vocab'])
                    ->where(['in', 'vocab', $combination])
                    ->groupBy('vocab')
                    ->asArray()
                    ->all();
        }
    }

    public function getResults() {
        return $this->results;
    }

}
