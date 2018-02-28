<?php

namespace app\models;

use Yii;
use app\models\Level;

class Level {
    
    /**
     *
     * @var Game 
     */
    protected $game; 
    /**
     *
     * @var array 
     */
    protected $level = [];    
    /**
     *
     * @var string 
     */
    protected $words = [
        'рекуперация' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'п', 'count' => 8]],
        'опровержение' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'р', 'count' => 3]],
        'хлопкопункт' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'аспирантура' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'ратификация' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'чемпионство' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'симфонизация' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'субъективизм' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'намеривание' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'артикуляция' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'реввоенсовет' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'пересыпание' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'перетекание' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'аннигиляция' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'правотворец' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'колядование' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'катехизация' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'передвигание' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'субординация' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'одноколейка' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'перерождение' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'переводчица' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'питекантроп' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'вегетарианец' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'регулировщик' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'видеотехника' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'мелодичность' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'пудлинговка' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'импровизация' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
        'наблюдатель' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => '', 'count' => 1]],
    ];
    
    
    /**
     * 
     * @return array
     */
    public function getWords() {
        return $this->words;
    }

    public function getLevel() {
        $this->init();
        return $this->level;
    }    
    
    public function run() {
        $this->loadGame();
        if ($this->check()) {
            $this->init();
            $this->checkNextLevel();
            $this->checkCountWords();
            $this->checkCountLetters();
            $this->save();
        }
    }
    
    public function check() {
        $result = false;
        if (isset($this->words[$this->game['word']])) {
            $result = true;
        }
        return $result;
    }
    
    protected function init() {
        $this->level = Yii::$app->session->get('level', []);
    }
    
    public function loadGame() {
        $this->game = (new Game())->getGame();
    } 
    
    protected function checkNextLevel() {
        if (count($this->game['answers']) >= $this->words[$this->game['word']]['answers']) {
            $this->level[$this->game['word']]['next_level'] = 1;
        }
    }
    
    protected function checkCountWords() {
        if (count($this->game['answers']) >= $this->words[$this->game['word']]['words']) {
            $this->level[$this->game['word']]['count_words'] = 1;
        }
    }

    protected function checkCountLetters() {
        $level_letter = $this->words[$this->game['word']]['letters']['letter'];
        $level_count_letters = $this->words[$this->game['word']]['letters']['count'];
        
        $game_count_letters = 0;
        if (isset($this->game['answers'])) {
            foreach ($this->game['answers'] as $answer) {
                $first_letter = mb_substr($answer, 0, 1);
                if ($first_letter == $level_letter) {
                    $game_count_letters++;
                }
            }
        }
        
        if ($game_count_letters >= $level_count_letters) {
            $this->level[$this->game['word']]['count_letters'] = 1;
        }
    }     
    
    protected function save() {
        Yii::$app->session->set('level', $this->level);
    }    
    
}
