$(function () {
    'use strict'

    let hasError = $('.has-error').data('error');
    let hasSuccess = $('.has-success').data('success');

    if (hasError){
        toastr['error'](hasError, 'Error!', {
            closeButton: true,
            tapToDismiss: false,
        });
    }

    if (hasSuccess){
        toastr['success'](hasSuccess, 'Success!', {
            closeButton: true,
            tapToDismiss: false,
        });
    }
})
