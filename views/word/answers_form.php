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
        <button class="btn btn-primary btn-lg" id="start_search" type="submit">Искать варианты</button>
    </form>
</div>


<div id="myModalWaiting" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <p class="text-center" style="padding: 40px 0px 30px 0px;">
                <img src="/images/waiting.gif" width="40" border="0">
                <strong>Идет поиск комбинаций слов ...</strong>
            </p>
        </div>
    </div>
</div> 

