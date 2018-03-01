<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Multibyte;
use app\models\Game;
use app\models\Level;
use app\widgets\StatisticWidget;


$game = new Game();
$game_data = $game->getGame();
if (isset($this->params['is_level'])) { 
    $level = new Level();
    $level_data = $level->getLevel();
    $level_words = $level->getWords();
    $level_word = $level_words[$game_data['word']];
}

$total_words_count = count($game_data['words']);
$total_words_name = \app\models\Vocabulary::getNameWords($total_words_count);

$this->title = $game_data['word'] . ' - cоставь слова';
?>

<p></p>

<div>

<?php
$letters = Multibyte::stringToArray($game_data['word']);
foreach ($letters as $letter) {
    ?><div class="letters"><?= mb_strtoupper($letter) ?></div><?php } ?>
</div>
<div class="container statistic">    
<p class="pull-left">
    <br>
    <i>всего найдено <?= $total_words_count ?> <?= $total_words_name ?>, вы отгадали <?= (int) count($game_data['answers']) ?></i>
    <?php
    if (isset($this->params['is_level'])) {
        $task_level = '';
        $task_words = '';
        $task_letters = '';

        if (isset($level_data[$game_data['word']]['next_level'])) {
            $task_level = ' task-comlete';
        }
        if (isset($level_data[$game_data['word']]['count_words'])) {
            $task_words = ' task-comlete';
        }
        if (isset($level_data[$game_data['word']]['count_letters'])) {
            $task_letters = ' task-comlete';
        }
        ?>      
        <i style="padding-left: 20px;"></i>
        <a href="#" class="btn btn-default btn-sm tasks_link inline-buttons">
        <i class="glyphicon glyphicon-star-empty<?= $task_level ?>" aria-hidden="true"></i>
        <i class="glyphicon glyphicon-star-empty<?= $task_words ?>" aria-hidden="true"></i>
        <i class="glyphicon glyphicon-star-empty<?= $task_letters ?>" aria-hidden="true"></i>
        </a>
    <?php } ?>                            
</p>
</div>
    
<div>

    <form class="form-inline" method="post" action="<?= Url::toRoute(['/game']) ?>">
        <span>Ваш ответ:</span>
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
        <div class="form-group">
            <input type="text" class="form-control" placeholder="" id="answerInput" name="answer" value="" maxlength="30" autocomplete="off">
        </div>
        <button class="btn btn-primary" type="submit">Проверить</button>
    </form>
</div>

<?php
if (count($game_data['answers'])) {
    ?>
    <br>
    <p>
        <strong>Найденные слова:</strong>
    </p>
    <ul class="list-inline">
        <?php
        foreach ($game_data['answers'] as $answer) {
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

<?php

echo Yii::$app->view->renderFile('@app/views/word/description_modal.php');
echo StatisticWidget::widget(['game' => $game_data]);
if (isset($this->params['is_level'])) { 
    echo Yii::$app->view->renderFile('@app/views/game/tasks_modal.php', [
        'level_data' => $level_data, 
        'level_word' => $level_word, 
        'game_data' => $game_data
    ]);
}

?> 

<br><br>
<div class="container game_navigation">
    <p class="pull-right">
        <a id="helpButton" class="btn btn-default btn-sm inline-buttons">
            <i class="glyphicon glyphicon-question-sign"></i> Показать подсказку
        </a>
        <a href="#" class="btn btn-default btn-sm statistic_link inline-buttons">
            <i class="glyphicon glyphicon-stats"></i> Статистика
        </a>
        <?php if (isset($this->params['is_level'])) { ?>
        <a href="#" class="btn btn-default btn-sm tasks_link inline-buttons">
            <i class="glyphicon glyphicon-star"></i> Задания
        </a>
        <?php } ?>
        <a href="#myModal" data-toggle="modal" class="btn btn-default btn-sm inline-buttons">
            <i class="glyphicon glyphicon-remove"></i> Завершить игру
        </a>
    </p>
</div>

