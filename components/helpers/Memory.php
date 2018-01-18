<?php

namespace app\components\helpers;

class Memory {

    /**
     * Return "memory_limit" parameter
     *
     * @return string
     */
    public static function getMemoryLimit() {
        return ini_get("memory_limit");
    }

    /**
     * Get memory usage
     *
     * @return string
     */
    public static function getMemoryUsage() {
        $mem_usage = memory_get_usage();
        if ($mem_usage < 1024) {
            return $mem_usage . " bytes";
        } elseif ($mem_usage < 1048576) {
            return round($mem_usage / 1024, 2) . " kilobytes";
        } else {
            return round($mem_usage / 1048576, 2) . " megabytes";
        }
    }

}
