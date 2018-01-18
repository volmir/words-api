<?php

namespace app\models;

use app\models\Permutation;
use app\models\Multibyte;

class Combination {

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

    public function setWord($word) {
        $this->word = $word;
    }

    public function find() {
        $this->letters = Multibyte::stringToArray($this->word);
        $letters_count = count($this->letters);

        for ($i = 2; $i <= ($letters_count - 1); $i++) {
            $this->permutation->init($i, $letters_count);
            do {
                $s = [];
                for ($i = 0; $i < $this->permutation->getK(); $i++) {
                    $s[] = $this->permutation->getIndex($i);
                }

                $combination = '';
                foreach ($s as $position) {
                    $combination .= $this->letters[$position];
                }
                
                yield $combination;
            } while ($this->permutation->next());
        }
    }

}
