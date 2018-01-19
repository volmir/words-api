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
        return self::formatData(memory_get_usage());
    }

    /**
     * Get memory peak usage
     *
     * @return string
     */
    public static function getMemoryPeakUsage() {
        return self::formatData(memory_get_peak_usage());
    }    

    /**
     * 
     * @param int $bytes
     * @return string
     */
    private static function formatData($bytes) {
        if ($bytes < 1024) {
            return $bytes . " bytes";
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 2) . " kilobytes";
        } else {
            return round($bytes / 1048576, 2) . " megabytes";
        }
    }    
    
}
