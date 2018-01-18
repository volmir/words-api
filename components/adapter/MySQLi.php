<?php

namespace app\components\adapter;

use Yii;

class MySQLi {

    protected static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            $data = explode(';', Yii::$app->db->dsn);
            $data_host = explode('=', $data[0]);
            $data_db = explode('=', $data[1]);

            self::$instance = new \mysqli($data_host[1], Yii::$app->db->username, Yii::$app->db->password, $data_db[1]);
            if (self::$instance->connect_errno) {
                echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
                exit();
            }

            $sql = 'SET NAMES utf8 COLLATE utf8_unicode_ci';
            self::$instance->query($sql);
        }
        return self::$instance;
    }

    public function __construct() {
        
    }

    private function __clone() {
        
    }

    private function __wakeup() {
        
    }

}
