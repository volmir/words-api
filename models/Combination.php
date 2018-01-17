<?php

namespace app\models;

use app\models\Permutation;
use app\models\Multibyte;

class Combination {
    
    /**
     *
     * @var array 
     */
    protected $combinations;
    /**
     *
     * @var string 
     */
    protected $word;   
    /**
     *
     * @var array 
     */
    protected $letters;   
    /**
     *
     * @var Permutation 
     */
    protected $permutation;
    
    
    public function __construct() {
        $this->permutation = new Permutation();
    }

    public function init() {
        $this->combinations = [];
    }    

    public function setWord($word) {
        $this->word = $word;
    } 
    
    public function getWord() {
        return $this->word;
    } 
    
    public function run() {
        $this->init();
        $this->find();
    }

    protected function find() {
        $this->word = mb_strtolower($this->word);
        $this->letters = Multibyte::stringToArray($this->word);
        $letters_count = count($this->letters);

        for ($i = 2; $i <= ($letters_count - 1); $i++) {
            $this->permutation->init($i, $letters_count);
            do {
                $s = [];
                for ($i = 0; $i < $this->permutation->getK(); $i++) {
                    $s[] = $this->permutation->getIndex($i);
                }
                $this->combinations[$i][] = $s;
            } while ($this->permutation->next());
        }  
    }
    
    public function getCombinations() {
        $combinations = [];
        foreach ($this->combinations as $length => $data) {
            foreach ($data as $positions) {
                $combination = '';
                foreach ($positions as $position) {
                    $combination .= $this->letters[$position];
                }
                $combinations[$length][] = $combination;
            }
        } 
        
        return $combinations;
    }
    
    /*
    public function show() {
        echo PHP_EOL . "Исходное слово: " . $this->word . PHP_EOL;
        foreach ($this->combinations as $key => $data) {
            echo PHP_EOL . "Комбинации (" . $key . " из " . count($this->letters) . "): "
            . count($data) . PHP_EOL;

            foreach ($data as $positions) {
                foreach ($positions as $position) {
                    echo $this->letters[$position];
                }
                echo ' ';
            }
            echo PHP_EOL;
        }        
    }
    */
}
