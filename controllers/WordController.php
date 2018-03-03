<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\Vocabulary;

class WordController extends Controller {
    
    const RANDOM_WORSD = 4;

    public function actionIndex() {
        $this->view->registerMetaTag([
            'name' => 'keywords',
            'content' => 'Слова из слов, игра, слово, комбинации слов, игра со словами, викторина, головоломка, аннаграмма'
        ]);
        $this->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Игра «Составь слова» предлагает игрокам известную головоломку, в которой нужно составлять разные слова из одного длинного слова'
        ]);
      
        $api_url = Url::toRoute('random/' . self::RANDOM_WORSD, true);
        $content = file_get_contents($api_url);
        $results = json_decode($content, true); 
        if (isset($results['data']) && count($results['data'])) {
            $this->view->params['random_words'] = $results['data'];
        }
        
        return $this->render('index');
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
            'content' => 'слово, толкование, значение' . (strlen(Yii::$app->request->get('word')) ? ', ' . Yii::$app->request->get('word') : '') 
        ]);
        $this->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Значение, толкование слова ' . Yii::$app->request->get('word')
        ]);        
        
        $word = Yii::$app->request->get('word');
        $word = Vocabulary::clear($word, true);
        if (mb_strlen($word)) {
            $api_url = Url::toRoute('description/' . urlencode($word), true);
            $content = file_get_contents($api_url);
            $results = json_decode($content, true);

            Yii::$app->view->params['results'] = $results;
        }
        return $this->render('description');
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

}
