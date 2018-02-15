<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;


$this->title = 'Значение, толкование слов';
$h1_title = 'Значение слова';

if (isset($this->params['results'])) {
    $base_word = $this->params['results']['word'];
    $this->title = $base_word . ' - значение, толкование слова';
    $h1_title = 'Значение слова "' . $base_word . '"';
}

?>

<h3><?= Html::encode($h1_title) ?></h3>

<div class="well well-small">
    <p>
        <span>Введите слово, значение которого вы хотите узнать</span>
    </p>
    <form class="form-inline" method="get" action="<?= Url::toRoute(['description']) ?>">
        <div class="form-group">
            <input type="text" class="form-control input-lg" placeholder="" name="word" value="" maxlength="30" autocomplete="off">
        </div>
        <button class="btn btn-primary btn-lg" id="start_search" type="submit">Поиск</button>
    </form>
</div>

<?php
if (isset($this->params['results'])) {
    if ($this->params['results']['status'] == 'success' && isset($this->params['results']['data']) && count($this->params['results']['data'])) {

        foreach ($this->params['results']['data'] as $description) {
            ?>    
            <blockquote>
                <p>
                    <span class="label label-default"><?= $base_word ?></span> - <?= $description['def'] ?>
            <?php
            if (strlen($description['baseform'])) {
                ?><br><span class="descr"><?= $description['baseform'] ?></span><?php
                    }
                    if (strlen($description['phongl'])) {
                        ?><br><span class="descr"><?= $description['phongl'] ?></span><?php
                    }
                    if (strlen($description['grclassgl'])) {
                        ?><br><span class="descr"><?= $description['grclassgl'] ?></span><?php
                    }
                    if (strlen($description['stylgl'])) {
                        ?><br><span class="descr"><?= $description['stylgl'] ?></span><?php
                    }
                    if (strlen($description['anti'])) {
                        ?><br><span class="descr">противоп. <i><?= $description['anti'] ?></i></span><?php
                    }
                    if (strlen($description['leglexam'])) {
                        ?><br><i><?= $description['leglexam'] ?></i><?php
                    }
                    ?>

                </p>
            </blockquote>
            <?php
        }
    } else {
        $this->title = 'Слово не найдено';
        Yii::$app->session->setFlash('info', '<strong>Ошибка!</strong> Ничего не найдено.');
    }
} 
?>