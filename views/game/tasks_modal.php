<div id="tasksModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Задания</h4>
            </div>

            <div class="modal-body">
                
                <?php
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
                
                $answers_remained = '';
                if ($level_word['answers'] > count($game_data['answers'])) {
                    $answers_remained = '(ещё ' . ($level_word['answers'] - count($game_data['answers'])) . ' слов)';
                }
                
                $count_letters = 0;
                if (isset($game_data['answers'])) {
                    foreach ($game_data['answers'] as $answer) {
                        $first_letter = mb_substr($answer, 0, 1);
                        if ($first_letter == $level_word['letters']['letter']) {
                            $count_letters++;
                        }
                    }
                }                
                
                ?>                

                <table class="table">
                    <thead>
                        <tr>
                            <td class="text-center"><i class="glyphicon glyphicon-star-empty<?= $task_level ?>" aria-hidden="true"></i></td>
                            <td class="text-left"><b>Пройдите уровень <?= $answers_remained ?></b></td>
                        </tr>
                        <tr>
                            <td class="text-center"><i class="glyphicon glyphicon-star-empty<?= $task_words ?>" aria-hidden="true"></i></td>
                            <td class="text-left"><b>Введите <?= $level_word['words'] ?> слов (<?= count($game_data['answers']) ?>/<?= $level_word['words'] ?>)</b></td>
                        </tr>
                        <tr>
                            <td class="text-center"><i class="glyphicon glyphicon-star-empty<?= $task_letters ?>" aria-hidden="true"></i></td>
                            <td class="text-left"><b>Введите <?= $level_word['letters']['count'] ?> слов на букву "<?= $level_word['letters']['letter'] ?>" (<?= $count_letters ?>/<?= $level_word['letters']['count'] ?>)</b></td>
                        </tr>
                    </thead>                
                </table>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>   