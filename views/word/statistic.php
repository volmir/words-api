<div id="statisticModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Статистика по слову "<b><?= $game['word'] ?></b>"</h4>
            </div>

            <div class="modal-body">

                <table class="table">
                    <thead>
                        <tr class="statistic">
                            <th class="text-left">Кол-во букв в слове</th>
                            <th class="text-center">Отгадано / Всего</th>
                            <th class="text-right">Процент, %</th>
                        </tr>
                    </thead>
                    <?php
                    $total_words = 0;
                    $total_answers = 0;

                    if (isset($statistic['words']) && count($statistic['words'])) {
                        ?>
                        <tbody>
                            <?php
                            foreach ($statistic['words'] as $key => $value) {
                                $count_words = count($value);
                                $count_answers = 0;
                                $percent = 0;
                                if (isset($statistic['answers'][$key]) && count($statistic['answers'][$key])) {
                                    $count_answers = count($statistic['answers'][$key]);
                                    if ($count_words > 0) {
                                        $percent = round($count_answers / $count_words * 100);
                                    }
                                }

                                $total_words += $count_words;
                                $total_answers += $count_answers;
                                ?>
                                <tr>
                                    <th scope="row" class="text-left"><?= $key ?></th>
                                    <td class="text-center"><?= $count_answers ?> / <?= $count_words ?></td>
                                    <td class="text-right"><?= $percent ?>%</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    <?php } ?>
                    <?php
                    if ($total_answers > 0 && $total_words > 0) {
                        $total_percent = round($total_answers / $total_words * 100);
                    }
                    ?>    
                    <tfoot>
                        <tr class="statistic">
                            <th scope="row" class="text-left">Всего</th>
                            <th class="text-center"><?= $total_answers ?> / <?= $total_words ?></th>
                            <th class="text-right"><?= $total_percent ?>%</th>
                        </tr>
                    </tfoot>  
                </table>                

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>   