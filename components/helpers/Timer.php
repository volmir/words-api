<?php

namespace app\components\helpers;

class Timer {

    /**
     *
     * @var float
     */
    private $start;

    public function start() {
        $this->start = microtime(true);
    }

    /**
     * 
     * @param int $decimals
     * @return float
     */
    public function finish($decimals = 6) {
        return number_format(microtime(true) - $this->start, $decimals);
    }

}
