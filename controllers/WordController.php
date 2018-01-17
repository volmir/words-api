<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

class WordController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionAnswers() {
        $word = Yii::$app->request->get('word');
        if (mb_strlen($word)) {
            $api_url = Url::to('api/words/' . urlencode($word), true);
            $content = file_get_contents($api_url);
            $results = json_decode($content, true);

            Yii::$app->view->params['results'] = $results;
        }
        return $this->render('answers');
    }

    public function actionDescription() {
        $word = Yii::$app->request->get('word');
        if (mb_strlen($word)) {
            $api_url = Url::to('api/description/' . urlencode($word), true);
            $content = file_get_contents($api_url);
            $results = json_decode($content, true);

            Yii::$app->view->params['results'] = $results;
        }
        return $this->render('description');
    }

    public function actionRules() {
        return $this->render('rules');
    }

    public function actionAbout() {
        return $this->render('about');
    }

}
