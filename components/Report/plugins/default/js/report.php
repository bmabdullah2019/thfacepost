$(document).ready(function() {
    $('body').on('click', '.ossn-report-this', function(e) {
        var $dataguid = $(this).attr('data-guid');
        var $datatype = $(this).attr('data-type');
        Ossn.MessageBox('report/file?data-guid=' + $dataguid + '&data-type=' + $datatype);
    });
    $('body').on('click', '#ossn-file-report', function(e) {
        e.preventDefault();
        $form = $('#ossn-report-file');
        Ossn.PostRequest({
            url: Ossn.site_url + "action/file/report",
            params: '&reason=' + $form.find('textarea[name="reason"]').val() + '&guid=' + $form.find('input[name="guid"]').val() + '&type=' + $form.find('input[name="type"]').val(),
            callback: function(callback) {
                Ossn.MessageBoxClose();
                if (callback['success']) {
                    Ossn.trigger_message(callback['success']);
                }
                if (callback['error']) {
                    Ossn.trigger_message(callback['error'], 'error');
                }
            }
        });
    });

});