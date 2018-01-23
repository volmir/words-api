<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

class VocabularyController extends Controller {

    public function actionSet() {
        $words = \Yii::$app->db->createCommand('SELECT vocab FROM vocabulary')->queryAll();
        if (isset($words)) {
            foreach ($words as $word) {
                \Yii::$app->memCache->set($word['vocab'], $word['vocab']);
            }
        }
        $this->stdout('Success: ' . count($words) . ' words loaded' . PHP_EOL);
    }
    
}
