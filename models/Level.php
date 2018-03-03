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
        'рекуперация' => ['answers' => 8, 'words' => 14, 'letters' => ['letter' => 'п', 'count' => 6]],
        'опровержение' => ['answers' => 8, 'words' => 14, 'letters' => ['letter' => 'р', 'count' => 3]],
        'хлопкопункт' => ['answers' => 10, 'words' => 20, 'letters' => ['letter' => 'к', 'count' => 8]],
        'аспирантура' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'а', 'count' => 7]],
        'ратификация' => ['answers' => 8, 'words' => 14, 'letters' => ['letter' => 'ф', 'count' => 7]],
        'чемпионство' => ['answers' => 12, 'words' => 24, 'letters' => ['letter' => 'о', 'count' => 6]],
        'симфонизация' => ['answers' => 10, 'words' => 16, 'letters' => ['letter' => 'с', 'count' => 6]],
        'субъективизм' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'б', 'count' => 6]],
        'намеривание' => ['answers' => 7, 'words' => 14, 'letters' => ['letter' => 'в', 'count' => 5]],
        'артикуляция' => ['answers' => 9, 'words' => 20, 'letters' => ['letter' => 'р', 'count' => 5]],
        'реввоенсовет' => ['answers' => 8, 'words' => 14, 'letters' => ['letter' => 'c', 'count' => 5]],
        'фисгармоника' => ['answers' => 15, 'words' => 40, 'letters' => ['letter' => 'г', 'count' => 10]],
        'перетекание' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'к', 'count' => 6]],
        'сурдоперевод' => ['answers' => 10, 'words' => 14, 'letters' => ['letter' => 'о', 'count' => 4]],
        'правотворец' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'п', 'count' => 1]],
        'колядование' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'к', 'count' => 1]],
        'катехизация' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'к', 'count' => 1]],
        'передвигание' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'п', 'count' => 1]],
        'субординация' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'с', 'count' => 1]],
        'одноколейка' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'о', 'count' => 1]],
        'перерождение' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'п', 'count' => 1]],
        'переводчица' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'п', 'count' => 1]],
        'питекантроп' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'п', 'count' => 1]],
        'вегетарианец' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'в', 'count' => 1]],
        'регулировщик' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'р', 'count' => 1]],
        'видеотехника' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'в', 'count' => 1]],
        'мелодичность' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'м', 'count' => 1]],
        'пудлинговка' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'п', 'count' => 1]],
        'импровизация' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'и', 'count' => 1]],
        'наблюдатель' => ['answers' => 10, 'words' => 15, 'letters' => ['letter' => 'н', 'count' => 1]],
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
        if ($this->check()) {
            $this->init();
            $this->checkNextLevel();
            $this->checkCountWords();
            $this->checkCountLetters();
            $this->save();
        }
    }
    
    public function check() {
        $this->loadGame();        
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
    
    public function setAnswers($answers) {
        if (isset($this->game['word'])) {
            $this->loadGame();
        }
        $this->level[$this->game['word']]['answers'] = $answers;
        $this->save();
    }    
    
    public function getAnswers() {
        if (isset($this->game['word'])) {
            $this->loadGame();
        }
        
        $answers = [];
        if (isset($this->level[$this->game['word']]['answers'])) {
            $answers = $this->level[$this->game['word']]['answers'];
        }
        
        return $answers;
    }      
    
    protected function save() {
        Yii::$app->session->set('level', $this->level);
    }    
    
}
