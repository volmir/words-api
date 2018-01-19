<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\Multibyte;

$game = Yii::$app->session->get('game');

$this->title = $game['word'] . ' - cоставь слова';
?>

<p></p>

<?php
$letters = Multibyte::stringToArray($game['word']);
foreach ($letters as $letter) {
    ?><div class="letters"><?= mb_strtoupper($letter) ?></div>
<?php } ?>

<p>
    <br>
    <i>всего найдено <?= count($game['words']) ?> слов, вы отгадали <?= (int) count($game['answers']) ?></i>
</p>

<div>

    <form class="form-inline" method="post" action="/game">
        <span>Ваш ответ:</span>
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
        <div class="form-group">
            <input type="text" class="form-control" placeholder="" name="answer" value="" maxlength="20">
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
            <li><a href="/description?word=<?= $answer ?>" target="_blank"><?= $answer ?></a> </li>
            <?php
        }
        ?>
    </ul>    
<?php }
?>




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
                    <a href="/game/finish" class="btn btn-danger"> Да </a>
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


<div class="down">
    <p><a href="#myModal" data-toggle="modal" class="btn btn-default btn-sm">
            <i class="glyphicon glyphicon-remove"></i> Завершить игру</a></p>

</div>



