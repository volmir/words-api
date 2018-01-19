<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Составь слова - игра';
?>

<h4>Игра «Составь слова»</h4>

<div class="well well-small">
    <p>
        <span>Введите слово:</span>
    </p>
    <form class="form-inline" method="post" action="/game">
        <input type="hidden" name="_csrf" value="<?=\Yii::$app->request->getCsrfToken()?>" />
        <div class="form-group">
            <input type="text" class="form-control input-lg" placeholder="" name="word" value="" maxlength="20">
        </div>
        <button class="btn btn-primary btn-lg" type="submit">Начать игру</button>
    </form>
</div>


<div style="padding-top: 10px;"></div> 
    <h4>Об игре</h4>
      
<p>    
Игра <b>"Составь слова"</b> предлагает игрокам известную головоломку, в которой нужно 
составлять разные слова из одного длинного слова. 
</p>
<p>
Соревнуйтесь с друзьями в количестве сложенных слов и использованных букв, и узнайте кто более смышлёный.
    </p>