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
                    foreach ($this->params['words'] as $word => $data) {
                        $counter++;
                        
                        $disabled = '';
                        if ($counter > 5) {
                            $disabled = ' disabled="disabled"';
                        }
                        ?>
                        <button class="btn btn-default inline-buttons list-buttons" data-word="<?= $word ?>" <?=$disabled?>>
                            <?= $counter . '. ' . mb_strtoupper($word) ?>
                            <i class="glyphicon glyphicon-star-empty _task-comlete" aria-hidden="true"></i>
                            <i class="glyphicon glyphicon-star-empty" aria-hidden="true"></i>
                            <i class="glyphicon glyphicon-star-empty" aria-hidden="true"></i>
                        </button>
                    <?php } ?>
                </form>    
            </div>
        </div>
    </div>
<?php } ?>
