<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use app\models\Game;
use app\models\Vocabulary;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller {
    
    public function init() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function actionWords($word) {
        $word = Vocabulary::clear($word);
        
        $game = new Game();
        $game->setWord($word);
        $game->run();
        $results = $game->getResults();
        
        $data = new \stdClass();
        $data->status = 'success';
        $data->word = $word;
        $data->data = $results;
        
        return $data;
    }

    public function actionDescription($word) {
        $results = \app\models\Vocabulary::find()
                //->select(['vocabulary_id', 'def', 'leglexam'])
                ->where(['vocab' => $word])
                ->all();

        $data = new \stdClass();
        $data->status = 'success';
        $data->word = $word;
        $data->data = $results;        
        
        return $data;
    }

}
