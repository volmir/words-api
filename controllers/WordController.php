<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\Game;
use app\models\Vocabulary;

set_time_limit(60);

class WordController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionGame() {
        $this->view->params['show_footer'] = true;
        $word = Yii::$app->request->post('word');
        $word = Vocabulary::clear($word);
        $game = Yii::$app->session->get('game', []);
        if (mb_strlen($word) > 0 && mb_strlen($word) <= 2) {
            Yii::$app->session->setFlash('info', 'Введите слово длиной не менее 3-х символов');
            return $this->redirect(Yii::$app->getHomeUrl());
        } elseif (mb_strlen($word) > 30) {
            Yii::$app->session->setFlash('info', 'Введите слово длиной не более 30-ти символов');
            return $this->redirect(Yii::$app->getHomeUrl());
        } elseif (mb_strlen($word) || count($game)) {
            $game = new Game();
            if (mb_strlen($word)) {
                $game->setWord($word);
            }
            $answer = Yii::$app->request->post('answer');
            $answer = Vocabulary::clear($answer);
            if (mb_strlen($answer)) {
                $game->setAnswer($answer);
            }
            $game->run();
            
            return $this->render('game');
        } else {
            Yii::$app->session->setFlash('info', 'Для начала игры введите корректное слово');
            return $this->redirect(Yii::$app->getHomeUrl());
        }
    }    
    
    public function actionAnswers() {
        $word = Yii::$app->request->get('word');
        $word = Vocabulary::clear($word);
        if (mb_strlen($word) > 0 && mb_strlen($word) <= 2) {
            Yii::$app->session->setFlash('info', 'Введите слово длиной не менее 3-х символов');
            return $this->redirect(Url::toRoute(['answers']));
        } elseif (mb_strlen($word) > 30) {
            Yii::$app->session->setFlash('info', 'Введите слово длиной не более 30-ти символов');
            return $this->redirect(Url::toRoute(['answers']));
        } elseif (mb_strlen($word)) {
            $api_url = Url::toRoute('words/' . urlencode($word), true);
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
        $word = Vocabulary::clear($word);
        if (mb_strlen($word)) {
            $api_url = Url::toRoute('description/' . urlencode($word), true);
            $content = file_get_contents($api_url);
            $results = json_decode($content, true);

            Yii::$app->view->params['results'] = $results;
        }
        return $this->render('description');
    }

    public function actionFinish() {
        Yii::$app->session->set('game', []);
        return $this->redirect(Yii::$app->getHomeUrl());
    }    
    
    public function actionRules() {
        return $this->render('rules');
    }

    public function actionAbout() {
        return $this->render('about');
    }

}
