<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\Game;
use app\models\Level;
use app\models\Vocabulary;

class GameController extends Controller {

    public function actionIndex() {
        $word = Yii::$app->request->post('word');
        $word = Vocabulary::clear($word);
        $game = (new Game())->getGame();
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
            $answer = mb_strtolower(Vocabulary::clear($answer));
            if (mb_strlen($answer)) {
                $game->setAnswer($answer);
            }
            $game->run();   
            
            $level = new Level();
            $level->run();
            if ($level->check()) {
                $this->view->params['is_level'] = true;
                $game_answers = $game->getAnswers();
                if (count($game_answers)) {
                    $level->setAnswers($game_answers);
                }
                $level_answers = $level->getAnswers();
                if (empty($game->getAnswers()) && count($level_answers)) {
                    $game->setAnswers($level_answers);
                }
            }
            
            return $this->render('index');
        } else {
            Yii::$app->session->setFlash('info', 'Для начала игры введите корректное слово');
            return $this->redirect(Yii::$app->getHomeUrl());
        }
    }    

    public function actionFinish() {
        Yii::$app->session->set('game', []);
        return $this->redirect(Yii::$app->getHomeUrl());
    }    
    
    public function actionHelp() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $game = new Game();
        $game->init();
        
        $result = ['word' => $game->getHelpWord()];
        
        return $result;
    }    

}
