$(document).ready(function () {

    $("#start_search").click(function () {
        $("#myModalWaiting").modal('show');
    });

    $("#helpButton").click(function () {
        let word = '';
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

    if ($("#answerInput").length){
        $("#answerInput").focus();
    }

    if ($("input[name='word']").length){
        $("input[name='word']").focus();
    }
    
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

function getHelpBlock(description) {
    let data = '';

    data = '<blockquote class="help"><p>' + description['def'] + ' ';

    if (description['baseform'].length) {
        data += '<br><span class="descr">' + description['baseform'] + '</span>';
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
