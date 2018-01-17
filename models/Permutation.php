<?php

namespace app\models;

use app\models\Math;

/**
 * 
 * Search of placements without permutations
 * 
 * @link http://slovesnov.users.sourceforge.net/index.php?permutations,russian
 */
class Permutation {
    
    /**
     *
     * @var int
     */
    private $n;
    /**
     *
     * @var int
     */    
    private $k;
    /**
     *
     * @var array
     */    
    private $i;
    /**
     *
     * @var array
     */     
    private $a;
    /**
     *
     * @var int
     */     
    private $index;

    /**
     * 
     * @param type $k
     * @param type $n
     * @throws Exception
     */
    public function init($k, $n) {
        if ($k <= 0 || $n <= 0) {
            throw new Exception('$n and $k should be positive');
        }
        if ($n < $k) {
            throw new Exception('error $n < $k');
        }

        $this->n = $n;
        $this->k = $k;

        $this->i = [];
        $this->a = [];

        $this->index = 0;
        $this->i[0] = -1;
        
        $this->add();
    }
    
    /**
     * 
     * @return int
     */
    public function calcCombinations() {
        $combination = Math::factorial($this->n) / Math::factorial($this->n - $this->k);

        return $combination;
    }    

    public function add() {
        $j = $this->index;
        $m = '';
        $l = '';
        $this->i[$j] ++;

        for ($j++; $j < $this->k; $j++) {
            $this->i[$j] = 0;
        }

        for ($j = 0; $j < $this->n; $j++) {
            $this->a[$j] = $j;
        }
        for ($j = 0; $j < $this->k; $j++) {
            $m = $j + $this->i[$j];
            $l = $this->a[$m];
            for (; $j != $this->k - 1 && $m > $j; $m--) {
                $this->a[$m] = $this->a[$m - 1];
            }
            $this->a[$j] = $l;
        }
    }

    /**
     * 
     * @return boolean
     */
    public function next() {
        $l = '';
        $j = '';
        for ($j = $this->k - 1; $j >= 0; $j--) {
            $l = $j;

            if ($this->n - 1 != $this->i[$j] + $l) {
                break;
            }
        }
        $this->index = $j;

        if ($this->index == -1) {
            return false;
        }
        $this->add();
        
        return true;
    }

    /**
     * 
     * @return int
     */
    public function getK() {
        return $this->k;
    }

    /**
     * 
     * @return int
     */
    public function getN() {
        return $this->n;
    }

    /**
     * 
     * @param int $i
     * @return int
     */
    public function getIndex($i) {
        return $this->a[$i];
    }

}

