<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Игра «Слова из слов» - онлайн головоломка, викторина';
?>

<h4>Игра «Составь слова из слов»</h4>

<div class="well well-small">
    <p>
        <span>Введите слово:</span>
    </p>
    <form class="form-inline" method="post" action="<?= Url::toRoute(['game']) ?>">
        <input type="hidden" name="_csrf" value="<?=\Yii::$app->request->getCsrfToken()?>" />
        <div class="form-group">
            <input type="text" class="form-control input-lg" placeholder="" name="word" value="" maxlength="30" autocomplete="off">
        </div>
        <button class="btn btn-primary btn-lg" id="start_search" type="submit">Начать игру</button>
    </form>
</div>

<?php
if (isset($this->params['random_words'])) {
?>
<div class="well well-small">
    <p>
        <span>Начать новую игру (<i>со случайным словом</i>):</span>
    </p>
    <form class="form-inline" method="post" action="<?= Url::toRoute(['game']) ?>">
        <input type="hidden" name="_csrf" value="<?=\Yii::$app->request->getCsrfToken()?>" />
        <input type="hidden" name="word" class="random_word" value="" />
        <?php foreach ($this->params['random_words'] as $word) { ?>
        <button class="btn btn-default random_game" data-word="<?=$word['vocab']?>"><?= mb_strtoupper($word['vocab'])?></button>
        <?php } ?>
    </form>    
</div>
<?php } ?>

    <h4>Об игре</h4>
      
<p>    
Игра <b>"Составь слова"</b> предлагает игрокам известную головоломку, в которой нужно 
составлять разные слова из одного длинного слова. 
</p>
<p>
Соревнуйтесь с друзьями в количестве сложенных слов и использованных букв, и узнайте кто более смышлёный.
    </p>
    
    

<div id="myModalWaiting" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <p class="text-center" style="padding: 40px 0px 30px 0px;">
                <img src="<?= Url::toRoute(['images/waiting.gif']) ?>" width="40" border="0">
                <strong>Идет поиск комбинаций слов ...</strong>
            </p>
        </div>
    </div>
</div>     