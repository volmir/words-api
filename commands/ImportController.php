<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

class ImportController extends Controller {

    public function actionIndex() {
        $lengh = [];
        $content = [];

        $handle = fopen(__DIR__ . '/dictionary/ozhegov.txt', "r");
        if ($handle) {
            while (($buffer = fgets($handle, 20000)) !== false) {
                $buffer = str_replace('', '', $buffer);
                $content[] = explode('|', trim($buffer));
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail" . PHP_EOL;
            }
            fclose($handle);
        }
        
        array_shift($content);

        foreach ($content as $row) {
            $word = new \app\models\Vocabulary();
            $word->vocab = $row[0];
            $word->baseform = $row[1];
            $word->phongl = $row[2];
            $word->grclassgl = $row[3];
            $word->stylgl = $row[4];
            $word->def = $row[5];
            $word->anti = $row[6];
            $word->leglexam = $row[7];
            //$word->save();
            
            for ($i = 1; $i <= 7; $i++) {
                $len = 0;
                if (isset($i) && isset($row[$i])) {
                    $len = mb_strlen($row[$i]);

                    if (!isset($lengh[$i]['len']) || $len > $lengh[$i]['len']) {
                        $lengh[$i]['len'] = $len;
                        $lengh[$i]['content'] = $row[$i];
                    }
                } else {
                    var_dump($row);
                }
            }
        }
        echo 'Total: ' . count($content) . PHP_EOL;
    }

}
