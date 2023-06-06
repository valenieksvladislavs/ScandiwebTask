function showDangerNotification(selector, message, callback = () => {}) {
    showNotification(selector, message, callback, false);
}

function showSuccessNotification(selector, message, callback = () => {}) {
    showNotification(selector, message, callback);
}

function showNotification(selector, message, callback, success = true) {
    const alert = $(selector).prepend(`<div class="alert ${success ? 'alert-success' : 'alert-danger'} alert-animated">${message}</div>`);
    setTimeout(function () {
        alert.children().first().remove();
        callback();
    }, 1000);
}