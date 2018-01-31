<?php
/* @var $this yii\web\View */

use \Yii;
use yii\helpers\Html;

if (isset($this->params['results'])) {

    $base_word = $this->params['results']['word'];
    $this->title = $base_word . ' - значение слова';
    $h1_title = 'Значения слова "' . $base_word . '"';

    if ($this->params['results']['status'] == 'success' && isset($this->params['results']['data']) && count($this->params['results']['data'])) {
        ?>

        <h3><?= Html::encode($h1_title) ?></h3>

        <?php
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
        ?>
        <p class="text-center">
            <a href="<?= Yii::$app->getHomeUrl() ?>" class="btn btn-success btn-lg">Вернуться на главную страницу</a>
        </p>
        <?php
    }
} else {
    $this->title = 'Слово не найдено';
    Yii::$app->session->setFlash('info', '<strong>Ошибка!</strong> Ничего не найдено.');
    ?>
    <p class="text-center">
        <a href="<?= Yii::$app->getHomeUrl() ?>" class="btn btn-success btn-lg">Вернуться на главную страницу</a>
    </p>        
    <?php
}
?>