<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Составь слова - игра';
?>

<h4>Составление слов из букв и других слов</h4>

<div class="well well-small">
    <p>
        <span>Введите слово или последовательность букв, из которых нужно составить слова</span>
    </p>
    <form class="form-inline" method="get" action="/answers">
        <div class="form-group">
            <input type="text" class="form-control input-lg" placeholder="" name="word" value="" maxlength="20">
        </div>
        <button class="btn btn-primary btn-lg" type="submit">Искать варианты</button>
    </form>
</div>