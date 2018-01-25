<?php
/* @var $this yii\web\View */

use \Yii;
use yii\helpers\Html;

if (isset($this->params['results'])) {

    $base_word = $this->params['results']['word'];
    $this->title = 'Слова, которые можно составить из "' . $base_word . '"';

    if ($this->params['results']['status'] == 'success' && isset($this->params['results']['data']) && count($this->params['results']['data'])) {
        ?>
        <h3>Все слова, которые можно составить из "<?= $base_word ?>"</h3>

        <?php
        
        $data = $this->params['results']['data'];
        ksort($data);
        reset($data);
        
        $total_lenght = [];
        $total_words = 0;
        foreach ($data as $lenght => $words) {
            if (count($words) > 0) {
                $total_lenght[] = $lenght;
                $total_words += count($words);
            }
        }
        
        $total_words_name = \app\models\Vocabulary::getNameWords($total_words);
        
        ?>
        <p><i>Из "<?= $base_word ?>" можно составить <?= $total_words ?> <?= $total_words_name ?> из <?= implode(',', $total_lenght) ?> букв</i>.</p>

        <?php
        foreach ($data as $lenght => $words) {
            if (count($words) > 0) {
                $words_name = \app\models\Vocabulary::getNameWords(count($words));
                ?>
                <p>Слова из <?= $lenght ?> букв, составленные из комбинации "<?= $base_word ?>"
                    (<?= count($words) ?> <?= $words_name ?>):</p>
                <ul class="list-inline">
                    <?php
                    foreach ($words as $word) {
                        ?>
                        <li><a href="/description?word=<?= $word['vocab'] ?>"><?= $word['vocab'] ?></a> </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
            }
        }
    } else {

        $this->title = 'Возможные комбинации слов не найдены';
        Yii::$app->session->setFlash('info', '<strong>Ошибка!</strong> Ничего не найдено.');
        ?>

        <p class="text-center">
            <a href="/answers" class="btn btn-success btn-lg">Попробовать снова</a>
        </p>
        <?php
    }
} else {
    $this->title = 'Возможные комбинации слов не найдены';
    Yii::$app->session->setFlash('info', '<strong>Ошибка!</strong> Ничего не найдено.');
    ?>

    <p class="text-center">
        <a href="/answers" class="btn btn-success btn-lg">Попробовать снова</a>
    </p>
    <?php
}
?>

