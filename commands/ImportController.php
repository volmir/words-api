<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;
use \app\models\Vocabulary;

class ImportController extends Controller {

    public function actionIndex() {
        //$this->importOzhegov();
        //$this->importEfremova();
        $this->importKuznetsov();
    }

    /**
     * is_noun: 
     *   0 - not nount
     *   1 - nount
     *   2 - error download page
     */
    public function actionPartSpeech() {
        $sql = 'SELECT vocabulary_id, vocab 
                FROM vocabulary
                WHERE is_noun IS NULL';
        $words = \Yii::$app->db->createCommand($sql)->queryAll();
        if (isset($words)) {
            foreach ($words as $word) {
                $is_noun = 2;
                $data = $word;

                $check_url = 'http://wordius.ru/часть-речи/' . mb_strtolower($data['vocab']) . '/';
                $headers = get_headers($check_url);
                if ($headers[0] == 'HTTP/1.1 200 OK') {
                    $is_noun = 0;
                    $content = file_get_contents($check_url);

                    preg_match_all('|<div class="rule">(.*)</div>|Uis', $content, $matches);
                    for ($i = 0; $i < count($matches[0]); $i++) {
                        if (preg_match("/существительным/i", $matches[1][$i])) {
                            $is_noun = 1;
                        }
                    }
                }

                $sql = "UPDATE `vocabulary` SET `is_noun` = '" . $is_noun . "' WHERE `vocabulary_id` = " . $data['vocabulary_id'] . ";";
                $result = \Yii::$app->db->createCommand($sql)->execute();
            }
        }
    }

    public function importKuznetsov() {
        $words_added = 0;

        $path = '/var/www/download';

        $dir = scandir($path);
        foreach ($dir as $file) {
            if ($file != '.' && $file != '..') {
                $filepath = $path . '/' . $file;
                $content = file_get_contents($filepath);
                if (!preg_match("/<section id='pagination'>/i", $content)) {

                    $descr = '';
                    if (preg_match_all("/<h3>словарь Кузнецова<\/h3>(.+?)<div class=\"layout\">(.+?)<\/div>/ism", $content, $matches_def)) {
                        for ($i = 0; $i < count($matches_def[0]); $i++) {
                            $descr = trim($matches_def[2][$i]);
                        }
                    }

                    if (preg_match_all("/<h1>(.*)<\/h1>/is", $content, $matches)) {
                        for ($i = 0; $i < count($matches[0]); $i++) {
                            $word = mb_strtolower(trim($matches[1][$i]));

                            if (strlen($word) && strlen($descr)) {
//                                echo $word . PHP_EOL;
//                                echo $descr . PHP_EOL;

                                $stylgl = '';
                                $descr_ext = explode('◁', $descr);
                                if (isset($descr_ext[1]) && strlen(trim($descr_ext[1]))) {
                                    $descr = $descr_ext[0];
                                    $stylgl = trim($descr_ext[1]);
                                } 

                                $result = Vocabulary::find()
                                        ->where(['vocab' => $word])
                                        ->one();
                                if (!$result) {
                                    $add_word = new Vocabulary();
                                    $add_word->vocab = $word;
                                    $add_word->baseform = '';
                                    $add_word->phongl = '';
                                    $add_word->grclassgl = '';
                                    $add_word->stylgl = $stylgl;
                                    $add_word->def = $descr;
                                    $add_word->anti = '';
                                    $add_word->leglexam = '';
                                    $add_word->save();

                                    $words_added++;
                                }
                            }
                        }
                    }
                }
            }
        }
        echo 'Kuznetsov: added ' . $words_added . ' words' . PHP_EOL;
    }

    public function importOzhegov() {
        $lengh = [];
        $content = [];

        $handle = fopen(__DIR__ . '../docs/ozhegov.txt', "r");
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
            $word = new Vocabulary();
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
        echo 'Ozhegov: added ' . count($content) . ' words' . PHP_EOL;
    }

    public function importEfremova() {
        $lengh = [];
        $content = '';

        $handle = fopen(__DIR__ . '/../docs/efremova.txt', "r");
        if ($handle) {
            while (($buffer = fgets($handle, 30000)) !== false) {
                $buffer = str_replace('', '', $buffer);
                $content .= $buffer;
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail" . PHP_EOL;
            }
            fclose($handle);
        }

        $data = explode("\n", $content);

        $words_added = 0;

        $key = 0;
        $block = [];
        foreach ($data as $row) {
            if (strlen($row) == 1) {
                $word = trim($block[0]);
                $part_of_speech = trim($block[1]);
                array_shift($block);
                $descr = trim(implode(PHP_EOL, $block));

                if (mb_strlen($word) > 2 && ($part_of_speech == 'предикатив' || mb_strpos($part_of_speech, 'разг.')) && count(explode(' ', $word)) == 1 && !mb_strpos($word, '-') && !mb_strpos($part_of_speech, 'прил.') && !mb_strpos($part_of_speech, 'сниж.') && !mb_strpos($part_of_speech, 'мн.') && $part_of_speech != 'нареч. разг.' && $part_of_speech != '1. ж. разг.' && $part_of_speech != 'ж. разг.' && $part_of_speech != 'прил. разг.' && $part_of_speech != 'несов. неперех. разг.' && $part_of_speech != 'местоим. разг.' && $part_of_speech != 'мн. разг.' && $part_of_speech != 'несов. разг.' && $part_of_speech != 'несов. перех. разг.' && $part_of_speech != 'сов. перех. и неперех. разг.' && $part_of_speech != 'несов. перех. и неперех. разг.' && $part_of_speech != '1. несов. перех. разг.' && $part_of_speech != '1. сов. неперех. разг.' && $part_of_speech != '1. несов. разг.' && $part_of_speech != '1. несов. неперех. разг.' && $part_of_speech != 'сов. неперех. разг.' && $part_of_speech != '1. нареч. разг.' && $part_of_speech != 'несов. и сов. перех. разг.' && $part_of_speech != '1. предикатив разг.' && $part_of_speech != 'предикатив разг.' && $part_of_speech != '1. сов. перех. разг.' && $part_of_speech != '1. несов. перех. и неперех. разг.' && $part_of_speech != '1. сов. перех. и неперех. разг.' && $part_of_speech != 'сов. разг.' && $part_of_speech != '1. сов. разг.' && $part_of_speech != 'сов. перех. разг.' && $part_of_speech != '1. м. нескл. разг.' && $part_of_speech != 'несов. и сов. перех. и неперех. разг.' && $part_of_speech != 'несов. и сов. разг.' && $part_of_speech != '1. союз разг.' && $part_of_speech != 'союз разг.' && $part_of_speech != 'мн. нескл. разг.' && $part_of_speech != 'мн.' && $part_of_speech != 'мн. разг.' && $part_of_speech != 'мн. нескл.' && $part_of_speech != 'числит. разг.'
//                        && $part_of_speech != 'прил.'
//                        && $part_of_speech != 'м. разг.'
//                        && $part_of_speech != '1. м. разг.'
//                        && $part_of_speech != 'м. разг.-сниж.'
//                        && $part_of_speech != '1. м. разг.-сниж.'
//                        && !strpos($part_of_speech, 'м. разг.') 
//                        && !mb_strpos($part_of_speech, 'м. разг.')
//                        && !mb_strpos($part_of_speech, 'разг.')
//                        && !mb_strpos($part_of_speech, '-сниж.')
//                        && !mb_strpos($word, '...')
//                        && $part_of_speech != 'I'
//                        && $part_of_speech != 'предикатив'
//                        && $part_of_speech != 'несов. и сов.'
//                        && $part_of_speech != '1. нареч. нар.-поэт.'
//                        && $part_of_speech != '1. предикатив'
//                        && $part_of_speech != 'сов. неперех. местн.'
//                        && $part_of_speech != '1. прил.'
//                        && $part_of_speech != 'прил. разг.'
//                        && $part_of_speech != 'прил. нар.-поэт.'
//                        && $part_of_speech != '1. прил. нар.-поэт.'
//                        && $part_of_speech != 'прил. местн.'
//                        && $part_of_speech != 'прил. разг.-сниж.'
//                        && $part_of_speech != 'прил. устар.'
//                        && $part_of_speech != 'мн.'
//                        && $part_of_speech != 'несов.'
//                        && $part_of_speech != 'сов.'
//                        && $part_of_speech != 'несов. перех. и неперех.'
//                        && $part_of_speech != 'несов. неперех.'
//                        && $part_of_speech != 'несов. перех.'
//                        && $part_of_speech != 'несов. и сов. перех.'
//                        && $part_of_speech != 'сов. перех.'
//                        && $part_of_speech != 'сов. неперех.'
//                        && $part_of_speech != 'несов. и сов. перех. и неперех.'
//                        && $part_of_speech != '1. несов.'
//                        && $part_of_speech != '1. сов.'
//                        && $part_of_speech != '1. несов. перех. и неперех.'
//                        && $part_of_speech != '1. несов. неперех.'
//                        && $part_of_speech != '1. несов. перех.'
//                        && $part_of_speech != '1. несов. и сов. перех.'
//                        && $part_of_speech != '1. сов. перех.'
//                        && $part_of_speech != '1. сов. неперех.'
//                        && $part_of_speech != '1. несов. и сов. перех. и неперех.'                        
//                        && $part_of_speech != 'несов. неперех. местн.'                        
//                        && $part_of_speech != 'м. местн.'                        
//                        && $part_of_speech != 'ж. местн.'                        
//                        && $part_of_speech != 'ср. местн.'                        
//                        && !strpos($part_of_speech, 'прил.')
//                        && !strpos($part_of_speech, 'мн.')
//                        && mb_substr($word, 0, 1) != '-' 
//                        && substr($word, 0, 1) != '-' 
//                        && substr($word, strlen($word) - 1, 1) != '-' 
//                        && mb_substr($word, mb_strlen($word) - 1, 1) != '-' 
//                        && !strpos($word, ' ') 
//                        && !mb_strpos($word, '...')
//                        && mb_substr($word, 0, 3) != '...'
//                        && substr($word, 0, 3) != '...'
//                        && substr($word, strlen($word) - 3, 3) != '...' 
//                        && mb_substr($word, mb_strlen($word) - 3, 3) != '...' 
//                        && !strpos($word, '...')
                ) {

                    $word = mb_strtolower($word);

                    $result = Vocabulary::find()
                            ->where(['vocab' => $word])
                            ->one();
                    if (!$result) {
                        $add_word = new Vocabulary();
                        $add_word->vocab = $word;
                        $add_word->baseform = '';
                        $add_word->phongl = '';
                        $add_word->grclassgl = '';
                        $add_word->stylgl = '';
                        $add_word->def = $descr;
                        $add_word->anti = '';
                        $add_word->leglexam = '';
                        //$add_word->save();

                        $words_added++;
                    }
                }


                $block = [];
            } else {
                $block[] = trim($row);
            }
        }

        echo 'Efremova: added ' . $words_added . ' words' . PHP_EOL;
    }

}
