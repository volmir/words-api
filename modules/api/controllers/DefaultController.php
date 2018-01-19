<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use app\models\Searching;
use app\models\Vocabulary;

set_time_limit(60);

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller {
    
    /**
     *
     * @var Timer 
     */
    public $timer;

    public function init() {
        $this->timer = new Yii::$app->timer();
        $this->timer->start();
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function actionWords($word) {
        $result = new \stdClass();
        
        $word = Vocabulary::clear($word);
        $word = mb_strtolower($word);        
        if (mb_strlen($word) > 10) {
            $word = mb_substr($word, 0, 10);
        } 
        
        if (mb_strlen($word) > 0) {
            $game = new Searching();
            $game->setWord($word);
            $game->run();

            $result->status = 'success';
            $result->word = $word;
            $result->data = $game->getResults();
        } else {
            $result->status = 'error';
            $result->reason = 'Incorrect word. Myst be cyrillic.';
            $result->word = $word;
            $result->data = [];
        }

//        echo Yii::$app->memory::getMemoryPeakUsage() . PHP_EOL;
//        echo 'Время: ' . $this->timer->finish() . ' сек.' . PHP_EOL;
        
        return $result;
    }

    public function actionDescription($word) {
        $results = \app\models\Vocabulary::find()
                ->where(['vocab' => $word])
                ->all();

        $data = new \stdClass();
        $data->status = 'success';
        $data->word = $word;
        $data->data = $results;        
        
        return $data;
    }

}
