function showFormError(selector, message) {
    $(".form-error").remove();
    $(selector).prepend(`<div class="form-error alert alert-danger">${message}</div>`);
}