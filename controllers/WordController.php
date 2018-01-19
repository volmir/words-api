<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\Game;

class WordController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionGame() {
        $word = Yii::$app->request->post('word');
        $game = Yii::$app->session->get('game');
        if (mb_strlen($word) || count($game)) {
            $game = new Game();
            if (mb_strlen($word)) {
                $game->setWord($word);
            }
            $game->run();
            
            return $this->render('game');
        } else {
            return $this->redirect('/');
        }
    }    
    
    public function actionAnswers() {
        $word = Yii::$app->request->get('word');
        if (mb_strlen($word)) {
            $api_url = Url::to('api/words/' . urlencode($word), true);
            $content = file_get_contents($api_url);
            $results = json_decode($content, true);

            Yii::$app->view->params['results'] = $results;
            
            return $this->render('answers_results');
        } else {
            return $this->render('answers_form');
        }
        
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
