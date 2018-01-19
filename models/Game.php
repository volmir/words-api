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
        $this->check();
    }

    private function init() {
        $this->game = Yii::$app->session->get('game');
    }

    private function check() {
        if (mb_strlen($this->word)) {
            if (count($this->game) == 0) {
                $this->newGame();
            } elseif ($this->game['word'] != $this->word) {
                $this->newGame();
            }
        }
    }

    private function newGame() {
        $api_url = Url::to('api/words/' . urlencode($this->word), true);
        $content = file_get_contents($api_url);
        $results = json_decode($content, true);

        $game = [
            'word' => $this->word,
            'results' => $results,
            'answers' => [
                'finded' => [],
                'not_finded' => [],
            ],
        ];

        Yii::$app->session->set('game', $game);
        $this->init();
    }

}
