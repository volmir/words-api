<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;
use app\models\Vector;
use app\models\Multibyte;

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

    public function actionVector() {
        $unique_words = [];

        $sql = 'SELECT vocabulary_id, vocab 
                FROM vocabulary';
        $words = \Yii::$app->db->createCommand($sql)->queryAll();
        if (isset($words)) {
            foreach ($words as $word) {
                if (in_array($word['vocab'], $unique_words)) {
                    continue;
                }
                $unique_words[] = $word['vocab'];

                $sql_letters = [];
                $sql_values = [];

                $word_arr = Multibyte::stringToArray($word['vocab']);
                $word_arr = array_unique($word_arr);
                foreach ($word_arr as $letter) {
                    if (isset(Vector::$letters[$letter])) {
                        $letter_eng = Vector::$letters[$letter];

                        $sql_letters[] = '`' . $letter_eng . '`';
                        $sql_values[] = "'1'";
                    }
                }

                if (count($sql_letters) >= 2) {
                    $sql = "INSERT INTO `vector` (`id`," . implode(',', $sql_letters) . ") 
                        VALUES ('" . $word['vocabulary_id'] . "'," . implode(',', $sql_values) . ")";
                    $result = \Yii::$app->db->createCommand($sql)->execute();
                }
            }
        }
        $this->stdout('Success: ' . count($unique_words) . ' vectors loaded' . PHP_EOL);
    }

    public function actionUpdate() {
        $sql = "SELECT vocabulary_id, vocab 
                FROM vocabulary 
                WHERE vocab LIKE '%.'";
        $words = \Yii::$app->db->createCommand($sql)->queryAll();
        if (isset($words)) {
            foreach ($words as $word) {
                $data = $word;

                $word = substr($data['vocab'], 0, -1);
                $sql = "UPDATE `vocabulary` SET `vocab` = '" . addslashes($word) . "' WHERE `vocabulary_id` = " . $data['vocabulary_id'] . ";";
                //$result = \Yii::$app->db->createCommand($sql)->execute();
            }
        }
        $this->stdout('Update finished' . PHP_EOL);
    }    
    
}
