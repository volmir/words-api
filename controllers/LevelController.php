<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\models\Game;
use app\models\Level;
use app\models\Vocabulary;

class LevelController extends Controller {
    
    /**
     *
     * @var Level 
     */
    public $level;

    public function actionIndex() {
        $this->view->registerMetaTag([
            'name' => 'keywords',
            'content' => 'Прохождение, уровни, игра, слово, комбинации слов'
        ]);
        $this->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Прохождение уровней в игре «Составь слова»'
        ]);
        
        $this->level = new Level();
        $this->view->params['words'] = $this->level->getWords();
        $this->view->params['level'] = $this->level->getLevel();
        
        return $this->render('index');
    }

}
