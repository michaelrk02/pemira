function showToast(html, classes) {
    if (typeof(classes) === 'undefined') { classes = ''; }

    var id = Math.ceil(Math.random() * 10000);
    M.toast({html: '<div>' + html + '</div><div><button type="button" data-dismiss=".toast-id-' + id + '" class="waves-effect waves-light btn-flat white-text toast-dismiss" onclick="dismissToast(this)"><i class="fa fa-times"></i></button></div>', classes: 'toast-id-' + id + ' ' + classes, displayLength: 15000});
}

function dismissToast(el) {
    $($(el).data('dismiss')).each(function(i, toast) {
        M.Toast.getInstance(toast).dismiss();
    });
}

window.addEventListener('load', function() {
    M.AutoInit();

    $('select').formSelect();

    var status = $('#status').html();
    if (status.length > 0) {
        var severity = $('#status').data('severity');
        var classes = '';
        switch (severity) {
        case 'success':
            classes += ' green';
            break;
        case 'warning':
            classes += ' yellow';
            break;
        case 'error':
            classes += ' red';
            break;
        }

        showToast('<b>' + status + '</b>', classes);
    }
});

