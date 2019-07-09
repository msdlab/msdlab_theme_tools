var buttonDialog;
(function($) {
    var input = {};
    buttonDialog = {

        init : function() {
            input.dialog = $('#button-wpdialog');
            input.submit = $('#button-wpdialog-submit');
            input.submit.click(function(e) {
                e.preventDefault();
                buttonDialog.submit();
            });
            input.cancel = $('#button-wpdialog-cancel')
            input.cancel.click(function(e) {
                buttonDialog.close();
                e.preventDefault();
            });

            // Bind a function to the wpdialogbeforeopen event of the dialog,
            // use the next line and replace callback with your actually function.
            // input.dialog.bind('wpdialogbeforeopen', callback);
            // Bind a function to the wpdialogrefresh event
            // input.dialog.bind('wpdialogrefresh', callback);
            // Bind a function to the wpdialogclose event
            // input.dialog.bind('wpdialogclose', callback);
        },

        submit : function() {
            if (tinyMCE && tinyMCE.activeEditor) {
                var buttonShortcode = "[button url='" + $('#button_url').val() + "' target='" + $('#button_target').val() + "' color='" + $('#button_color').val() + "']" + $('#button_text').val() + "[/button]";
                tinyMCE.activeEditor.selection.setContent(buttonShortcode);
            }
            input.dialog.wpdialog('close');
            //buttonDialog.close();
        },

        close : function() {
            if (buttonDialog.isMCE())
                tinyMCEPopup.close();
            else
                input.dialog.wpdialog('close');
        },

        isMCE : function() {
            return tinyMCEPopup && (ed = tinyMCEPopup.editor) && !ed.isHidden();
        }
    };
    $(document).ready(buttonDialog.init);
})(jQuery)