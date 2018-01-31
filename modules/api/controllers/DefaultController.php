<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use app\models\SearchingCombination;
use app\models\SearchingVector;
use app\models\Vocabulary;
use app\components\helpers\Timer;
use app\components\helpers\Memory;

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

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,
                ],
            ],
        ]);
    }

    public function init() {
        $this->timer = new Timer();
        $this->timer->start();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function actionWords($word) {
        $result = new \stdClass();
        $result->word = $word;
        $result->data = [];

        $word = Vocabulary::clear($word);
        $word = mb_strtolower($word);
        if (mb_strlen($word) <= 2) {
            $result->status = 'error';
            $result->reason = 'The length of the word must be more that 2 characters.';
        } elseif (mb_strlen($word) > 30) {
            $result->status = 'error';
            $result->reason = 'The length of the word is limited to 30 characters.';
        } elseif (mb_strlen($word) > 0) {
            //$searching = new SearchingCombination();
            $searching = new SearchingVector();
            $searching->setWord($word);
            $searching->run();

            $result->status = 'success';
            $result->data = $searching->getResults();
        } else {
            $result->status = 'error';
            $result->reason = 'Incorrect word. Must be cyrillic.';
        }

        if (Yii::$app->request->get('debug')) {
            echo 'Память: ' . Memory::getMemoryPeakUsage() . PHP_EOL;
            echo 'Время: ' . $this->timer->finish() . ' сек.' . PHP_EOL;
        }

        return $result;
    }

    public function actionDescription($word) {
        $results = Vocabulary::find()
                ->where(['vocab' => $word])
                ->asArray()
                ->all();

        if ($results) {
            foreach ($results as $key => $result) {
                $results[$key]['def'] = nl2br($result['def']);
            }
        }

        $data = new \stdClass();
        $data->status = 'success';
        $data->word = $word;
        $data->data = $results;

        return $data;
    }

}
