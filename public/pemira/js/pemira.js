function showToast(id, html, classes) {
    if (typeof(classes) === 'undefined') { classes = ''; }

    M.toast({html: '<div>' + html + '</div><div><button type="button" data-dismiss=".toast-id-' + id + '" class="waves-effect waves-light btn-flat white-text toast-dismiss" onclick="dismissToast(this)"><i class="fa fa-times"></i></button></div>', classes: 'toast-id-' + id + ' ' + classes, displayLength: 15000});
}

function dismissToast(el) {
    $($(el).data('dismiss')).each(function(i, toast) {
        M.Toast.getInstance(toast).dismiss();
    });
}

window.addEventListener('load', function() {
    $('.sidenav').sidenav();

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

        showToast('status', '<b>' + status + '</b>', classes);
    }
});

