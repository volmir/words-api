$(document).ready(function () {

    $("#start_search").click(function () {
        $("#myModalWaiting").modal('show');
    });

    $("#helpButton").click(function () {
        $.ajax({
            type: 'GET',
            url: '/web/game/help',
            success: function (data) {
                if (data['word'].length > 0) {
                    getHelp(data['word']);
                }
            }
        });
        
        $("#answerInput").focus();
    });
    
    $(".description_link").click(function () {
        if ($(this).data('answer').length > 0) {
            $('#descriptionModal .word_value').html($(this).data('answer'));
            getDescription($(this).data('answer'));
        }
    });  
    
    $(".statistic_link").click(function () {
        $('#statisticModal').modal('show');
    });

    $(".tasks_link").click(function () {
        $('#tasksModal').modal('show');
    });

    if ($("#answerInput").length){
        $("#answerInput").focus();
    }

    if ($("input[name='word']").length){
        $("input[name='word']").focus();
    }
    
    $("form button.inline-buttons").click(function () {
        $("form input.random_word").attr('value', $(this).data('word'));
    });
    
});

function getHelp(word) {
    if (word.length > 0) {
        $.ajax({
            type: 'GET',
            url: '/web/description/' + word,
            success: function (data) {
                if (data['status'] == 'success') {
                    let help_info = '';

                    $.each(data['data'], function (key, row) {
                        help_info += getHelpBlock(row);
                    });

                    if (help_info.length) {
                        $('#help_infomation').html(help_info);
                        $('#helpBlock').show();
                    }
                }
            }
        });
    }
}

function getDescription(word) {
    if (word.length > 0) {
        $.ajax({
            type: 'GET',
            url: '/web/description/' + word,
            success: function (data) {
                if (data['status'] == 'success') {
                    let description_info = '';

                    $.each(data['data'], function (key, row) {
                        description_info += getHelpBlock(row, true);
                    });

                    if (description_info.length) {
                        $('#word_description').html(description_info);
                        $('#descriptionModal').modal('show');
                    }
                }
            }
        });
    }
}

function getHelpBlock(description, show_vocab = false) {
    let data = '';

    let vocab = '';
    if (show_vocab) {
        vocab = '<span class="label label-default">' + description['vocab'] + '</span> - ';
    }

    data = '<blockquote class="help"><p>' + vocab + description['def'] + ' ';

    if (description['baseform'].length) {
        //data += '<br><span class="descr">' + description['baseform'] + '</span>';
    }
    if (description['phongl'].length) {
        data += '<br><span class="descr">' + description['phongl'] + '</span>';
    }
    if (description['grclassgl'].length) {
        data += '<br><span class="descr">' + description['grclassgl'] + '</span>';
    }
    if (description['stylgl'].length) {
        data += '<br><span class="descr">' + description['stylgl'] + '</span>';
    }
    if (description['anti'].length) {
        data += '<br><span class="descr">противоп. <i>' + description['anti'] + '</i></span>';
    }
    if (description['leglexam'].length) {
        data += '<br><i>' + description['leglexam'] + '</i>';
    }

    data += '</p></blockquote>';

    return data;

}
