<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;

class StatisticWidget extends Widget {

    /**
     *
     * @var array
     */
    public $game;

    public function init() {
        
    }

    public function run() {
        $statistic = [];
        $statistic['words'] = [];
        $statistic['answers'] = [];        
        $statistic['total_words'] = 0;
        $statistic['total_answers'] = 0;

        if (isset($this->game['words']) && count($this->game['words'])) {
            $statistic['total_words'] = count($this->game['words']);
            foreach ($this->game['words'] as $word) {
                $statistic['words'][mb_strlen($word)][] = $word;
            }
            ksort($statistic['words']);
            reset($statistic);
        }
        if (isset($this->game['answers']) && count($this->game['answers'])) {
            $statistic['total_answers'] = count($this->game['answers']);
            foreach ($this->game['answers'] as $answer) {
                $statistic['answers'][mb_strlen($answer)][] = $answer;
            }
        }
        
        return $this->renderFile('@app/views/game/statistic.php', [
                    'game' => $this->game,
                    'statistic' => $statistic,
        ]);
    }

}
