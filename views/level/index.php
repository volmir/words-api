<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Прохождение уровней - игра «Слова из слов»';

?>

<h3>Прохождение уровней игры</h3>

<?php
if (isset($this->params['words'])) {
    ?>
    <div class="well well-small">
        <p>
            <span>Выберите доступный уровень:</span>
        </p>

        <div class="container-fluid">
            <div class="row content">        
                <form class="form-inline" method="post" action="<?= Url::toRoute(['/game']) ?>">
                    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>" />
                    <input type="hidden" name="word" class="random_word" value="" />
                    <?php
                    $counter = 0;
                    $button_disabled = true;
                    
                    foreach ($this->params['words'] as $word => $data) {
                        $counter++;

                        $task_level = '';
                        $task_words = '';
                        $task_letters = '';
                        
                        $disabled_param = '';
                        if ($counter > 1 && $button_disabled) {
                            $disabled_param = ' disabled="disabled"';
                        } 
                        
                        if (isset($this->params['level'][$word]['next_level'])) {
                            $task_level = ' task-comlete';
                            $button_disabled = false;
                        } else {
                            $button_disabled = true;
                        }
                        if (isset($this->params['level'][$word]['count_words'])) {
                            $task_words = ' task-comlete';
                        }
                        if (isset($this->params['level'][$word]['count_letters'])) {
                            $task_letters = ' task-comlete';
                        }
                        
                        ?>
                        <button class="btn btn-default inline-buttons list-buttons" data-word="<?= $word ?>" <?=$disabled_param?>>
                            <?= $counter . '. ' . mb_strtoupper($word) ?>
                            <i class="glyphicon glyphicon-star-empty<?=$task_level?>" aria-hidden="true"></i>
                            <i class="glyphicon glyphicon-star-empty<?=$task_words?>" aria-hidden="true"></i>
                            <i class="glyphicon glyphicon-star-empty<?=$task_letters?>" aria-hidden="true"></i>
                        </button>
                    <?php } ?>
                </form>    
            </div>
        </div>
    </div>
<?php } ?>
