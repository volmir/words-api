<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = "Страница не найдена (404 ошибка)";

?>
<div class="site-error">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="alert alert-danger">
        Страница <b>http://<?= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?></b> не существует
    </div>

    <p>
        Произошла ошибка при обработке вашего запроса.<br>
        Вероятно, подобной страницы не существовало, либо она была удалена с сервера.
    </p>

</div>
