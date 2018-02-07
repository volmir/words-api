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
        $this->view->registerMetaTag([
            'name' => 'keywords',
            'content' => 'Слова из слов, игра, слово, комбинации слов, игра со словами, викторина, головоломка, аннаграмма'
        ]);
        $this->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Игра «Составь слова» предлагает игрокам известную головоломку, в которой нужно составлять разные слова из одного длинного слова'
        ]);
        
        $sql = 'SELECT `vocab` FROM `vocabulary` 
                WHERE
                    CHAR_LENGTH(`vocab`) >= 11 
                    AND CHAR_LENGTH(`vocab`) <= 12 
                ORDER BY RAND() 
                LIMIT 0,4';
        $this->view->params['random_words'] = \Yii::$app->db->createCommand($sql)->queryAll();
      
        return $this->render('index');
    }

    public function actionGame() {
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
        $this->view->registerMetaTag([
            'name' => 'keywords',
            'content' => 'Составление слов, подбор слов, комбинации слов'
        ]);
        $this->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Составление слов из заданных букв. Поиск и подбор возможных слов, составленных из искомого слова. Составление слов по заданным буквам'
        ]);
        
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
        $this->view->registerMetaTag([
            'name' => 'keywords',
            'content' => Yii::$app->request->get('word')
        ]);
        $this->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Значение слова ' . Yii::$app->request->get('word')
        ]);        
        
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
        $this->view->registerMetaTag([
            'name' => 'keywords',
            'content' => 'Правила, игра, составление слов'
        ]);
        $this->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Правила игры «Составь слова»'
        ]);        
        
        return $this->render('rules');
    }

    public function actionAbout() {
        $this->view->registerMetaTag([
            'name' => 'keywords',
            'content' => 'Описание, информация, подбор слов'
        ]);
        $this->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Описание игры, общая информация о сайте'
        ]);        
        
        return $this->render('about');
    }
    
    public function actionHelp() {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $game = new Game();
        $game->init();
        
        $result = ['word' => $game->getHelpWord()];
        
        return $result;
    }    

}
