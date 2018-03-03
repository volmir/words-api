<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

class Game {

    /**
     *
     * @var string 
     */
    protected $word;
    /**
     *
     * @var string 
     */
    protected $answer;
    /**
     *
     * @var array 
     */
    protected $game = [];
    
    
    public function getGame() {
        $this->init();
        return $this->game;
    }

    public function setWord($word) {
        $this->word = $word;
    }
    
    public function setAnswer($answer) {
        $this->answer = $answer;
    }
    
    public function run() {
        $this->init();
        $this->checkGame();
        $this->checkAnswer();
        $this->checkFinish();
    }

    public function init() {
        $this->game = Yii::$app->session->get('game', []);
    }
    
    private function checkAnswer() {
        if (mb_strlen($this->answer)) {
            $answers = $this->game['answers'];
            if (in_array($this->answer, $this->game['answers'])) {
                Yii::$app->session->setFlash('info', 'Вы уже разгадали слово <strong>' . $this->answer . '</strong>');
            } elseif (in_array($this->answer, $this->game['words'])) {
                $this->game['answers'][] = $this->answer;
                $this->save();
                Yii::$app->session->setFlash('success', 'Правильно!');
            } else {
                Yii::$app->session->setFlash('info', 'Слово <strong>' . $this->answer . '</strong> не найдено!');
            }
        }
    }

    private function checkGame() {
        if (mb_strlen($this->word)) {
            if (is_null($this->game) || count($this->game) == 0) {
                $this->newGame();
            } elseif ($this->game['word'] != $this->word) {
                $this->newGame();
            }
        }
    }
    
    private function checkFinish() {
        if (count($this->game['words']) == count($this->game['answers'])) {
            if (count($this->game['words'])) {
                Yii::$app->session->setFlash('success', 'Вы отгадали все слова!');
            }
        }
    }

    private function newGame() {
        $api_url = Url::toRoute('words/' . urlencode($this->word), true);
        $content = file_get_contents($api_url);
        $results = json_decode($content, true);

        $words = [];
        if (isset($results['data']) && count($results['data'])) {
            foreach ($results['data'] as $rows) {
                foreach ($rows as $word) {
                    if (mb_strlen($word['vocab']) > 2) {
                        $words[] = $word['vocab'];
                    }
                }
            }
        } else {
            Yii::$app->session->setFlash('info', 'Комбинации слов не найдены!');
        }
        
        $this->game = [
            'word' => $this->word,
            'words' => $words,
            'answers' => [],
        ];
        
        $this->save();
    }

    public function getHelpWord() {
        $word = '';

        if (count($this->game['words']) > count($this->game['answers'])) {
            if (count($this->game['words'])) {
                $words = array_diff($this->game['words'], $this->game['answers']);
                sort($words);
                if (isset($words) && is_array($words) && count($words)) {
                    $random_id = mt_rand(0, count($words) - 1);
                    if (isset($words[$random_id])) {
                        $word = $words[$random_id];
                    }
                }
            }
        }

        return $word;
    }
    
    protected function save() {
        Yii::$app->session->set('game', $this->game);
    }

    public function setAnswers($answers) {
        $this->game['answers'] = $answers;
        $this->save();
    }    
    
    public function getAnswers() {
        return $this->game['answers'];
    }     
    
}
