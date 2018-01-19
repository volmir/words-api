<?php
/* @var $this yii\web\View */

use \Yii;
use yii\helpers\Html;

$this->title = 'Составь слова - игра';
?>

<h4>Игра «Составь слова»</h4>

<div class="well well-small">
    <p>
        <span>Введите слово:</span>
    </p>
    <form class="form-inline" method="post" action="/game">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <div class="form-group">
            <input type="text" class="form-control input-lg" placeholder="" name="word" value="" maxlength="20">
        </div>
        <button class="btn btn-primary btn-lg" type="submit">Начать игру</button>
    </form>
</div>