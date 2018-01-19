<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\Game;
use app\models\Vocabulary;

class WordController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionGame() {
        $word = Yii::$app->request->post('word');
        $word = Vocabulary::clear($word);
        $game = Yii::$app->session->get('game');
        if (mb_strlen($word) || count($game)) {
            $game = new Game();
            if (mb_strlen($word)) {
                $game->setWord($word);
            }
            $game->run();
            
            return $this->render('game');
        } else {
            Yii::$app->session->setFlash('info', 'Для начала игры введите корректное слово');
            return $this->redirect('/');
        }
    }    
    
    public function actionAnswers() {
        $word = Yii::$app->request->get('word');
        if (mb_strlen($word)) {
            $word = str_replace('/', '', $word);
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
            $word = str_replace('/', '', $word);
            $api_url = Url::to('api/description/' . urlencode($word), true);
            $content = file_get_contents($api_url);
            $results = json_decode($content, true);

            Yii::$app->view->params['results'] = $results;
        }
        return $this->render('description');
    }

    public function actionFinish() {
        Yii::$app->session->set('game', []);
        return $this->redirect('/');
    }    
    
    public function actionRules() {
        return $this->render('rules');
    }

    public function actionAbout() {
        return $this->render('about');
    }

}
