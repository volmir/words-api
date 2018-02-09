<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Multibyte;

$game = Yii::$app->session->get('game');

$total_words_count = count($game['words']);
$total_words_name = \app\models\Vocabulary::getNameWords($total_words_count);

$this->title = $game['word'] . ' - cоставь слова';
?>

<p></p>

<?php
$letters = Multibyte::stringToArray($game['word']);
foreach ($letters as $letter) {
    ?><div class="letters"><?= mb_strtoupper($letter) ?></div><?php } ?>

<p>
    <br>
    <i>всего найдено <?= $total_words_count ?> <?= $total_words_name ?>, вы отгадали <?= (int) count($game['answers']) ?></i>
</p>

<div>

    <form class="form-inline" method="post" action="<?= Url::toRoute(['game']) ?>">
        <span>Ваш ответ:</span>
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
        <div class="form-group">
            <input type="text" class="form-control" placeholder="" id="answerInput" name="answer" value="" maxlength="30" autocomplete="off">
        </div>
        <button class="btn btn-primary" type="submit">Проверить</button>
    </form>
</div>

<?php
if (count($game['answers'])) {
    ?>
    <br>
    <p>
        <strong>Найденные слова:</strong>
    </p>
    <ul class="list-inline">
        <?php
        foreach ($game['answers'] as $answer) {
            ?>
            <li><a href="#" class="description_link" data-answer="<?=$answer?>"><?= $answer ?></a> </li>
            <?php
        }
        ?>
    </ul>    
<?php }
?>



<div id="helpBlock">
    <p>
        <strong class="header">Подсказка:</strong> 
    </p>        
    <div id="help_infomation"></div>

</div>    


<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Вы уверены, что хотите завершить игру?</h4>
            </div>

            <div class="modal-body">
                <p class="text-center">          
                    <br>    
                    <a href="<?= Url::toRoute(['game/finish']) ?>" class="btn btn-danger"> Да </a>
                    <button type="button" class="btn btn-success" data-dismiss="modal">Нет</button>
                    <br>
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>  

<div id="descriptionModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Значение слова "<span class="word_value"></span>"</h4>
            </div>

            <div class="modal-body">
                <div id="word_description"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>    


<br><br>
<div class="container">
    <p class="pull-right">
        <a id="helpButton" class="btn btn-default btn-sm">
            <i class="glyphicon glyphicon-question-sign"></i> Показать подсказку
        </a>
        <a href="#myModal" data-toggle="modal" class="btn btn-default btn-sm">
            <i class="glyphicon glyphicon-remove"></i> Завершить игру
        </a>
    </p>
</div>

