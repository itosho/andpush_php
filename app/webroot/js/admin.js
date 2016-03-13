(function($, _window, undefined) {

    $(function() {

        var KEYCODE_BACKSPACE = 8, KEYCODE_ENTER = 13;

        $(document).on(
            'submit',
            'form',
            function(e) {
                // console.log(e);
                var action = $(this).attr('action');

                var message = (/\/destroy/.test(action))
                    && 'この項目を削除しますがよろしいですか？' || null;

                if (!message || _window.confirm(message)) {
                    return true;
                }

                return false;
            });


        $(document).on(
            'submit',
            'form',
            function(e) {
                // console.log(e);
                var action = $(this).attr('action');

                var message = (/\/property/.test(action))
                    && '属性情報を更新しますがよろしいですか？' || null;

                if (!message || _window.confirm(message)) {
                    return true;
                }

                return false;
            });

        $('div.form-group').on(
            'click',
            'button.alias-button',
            function(e) {
                var fileList = $(this).attr("id").split('-');
                var id = fileList[1];
                $('input#' + id).trigger('click');
            });

        $('div.form-group').on(
            'change',
            'input.file',
            function(e) {
                var id = $(this).attr("id");
                $('input#input-' + id).val($(this).val().replace(/C:\\fakepath\\/i, ''));
            });

        /*$('div.form-group').on(
            'click',
            'button.clear-button',
            function(e) {
                var fileList = $(this).attr("id").split('-');
                var id = fileList[1];
                $('input#input-' + id).val('');
                $('input#' + id + '-file').val('');
            });*/


        if ($('input[name="data[Message][send_type]"]:radio').val() == "1") {
            $("div.option").removeClass("off").addClass("on");
        } else if ($('input[name="data[Message][send_type]"]:radio').val() != "0") {
            $("div.option").removeClass("off").addClass("on");
        } else {
            $("div.option").addClass("off").removeClass("on");
        }

        $('div.form-group').on(
            'change',
            'input[name="data[Message][send_type]"]:radio',
            function(e) {
                if ($(this).val() == "1") { // 予約送信
                    $("div.option").removeClass("off").addClass("on");
                } else { // 即時送信
                    $("div.option").addClass("off").removeClass("on");
                }
            });

    });

})(jQuery, window);
