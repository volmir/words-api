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
    protected $game = [];

    public function setWord($word) {
        $this->word = $word;
    }

    public function run() {
        $this->init();
        $this->checkGame();
        $this->checkAnswer();
        $this->checkFinish();
    }

    private function init() {
        $this->game = Yii::$app->session->get('game');
    }
    
    private function checkAnswer() {
        $answer = Yii::$app->request->post('answer');
        if (mb_strlen($answer)) {
            $answers = $this->game['answers'];
            if (in_array($answer, $this->game['answers'])) {
                Yii::$app->session->setFlash('info', 'Вы уже разгадали слово <strong>' . $answer . '</strong>');
            } elseif (in_array($answer, $this->game['words'])) {
                $this->game['answers'][] = $answer;
                Yii::$app->session->set('game', $this->game);
                Yii::$app->session->setFlash('success', 'Правильно!');
            } else {
                Yii::$app->session->setFlash('info', 'Слово <strong>' . $answer . '</strong> не найдено!');
            }
        }
    }

    private function checkGame() {
        if (mb_strlen($this->word)) {
            if (count($this->game) == 0) {
                $this->newGame();
            } elseif ($this->game['word'] != $this->word) {
                $this->newGame();
            }
        }
    }
    
    private function checkFinish() {
        if (count($this->game['words']) == count($this->game['answers'])) {
            Yii::$app->session->setFlash('success', 'Вы отгадали все слова!');
        }
    }

    private function newGame() {
        $this->word = str_replace('/', '', $this->word);
        $api_url = Url::to('api/words/' . urlencode($this->word), true);
        $content = file_get_contents($api_url);
        $results = json_decode($content, true);

        $words = [];
        if (isset($results['data']) && count($results['data'])) {
            foreach ($results['data'] as $rows) {
                foreach ($rows as $word) {
                    $words[] = $word['vocab'];
                }
            }
        }
        
        $game = [
            'word' => $this->word,
            'words' => $words,
            'answers' => [],
        ];

        Yii::$app->session->set('game', $game);
        $this->init();
    }

}
