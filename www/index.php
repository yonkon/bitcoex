<?php

//todo yiic migrate --migrationPath=user.migrations
ini_set('dusplay_errors', 1);
error_reporting(-1);
function cecho($message, $default = '', $condition = null) {
    if (empty ($message)) {
        $message = '';
    }
    if (empty($default)) {
        $default = '';
    }
    if (isset($condition)) {
        if (empty($condition)) {
            echo $default;
        } else {
            echo isset($message) ? $message : $default;
        }
    } else {
        echo isset($message) ? $message : $default;
    }
}

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../framework/yii.php';
$config = dirname(__FILE__) . '/protected/config/main.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once($yii);
$app = Yii::createWebApplication($config);
$app->run();
