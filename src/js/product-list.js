$(document).ready(function() {
    $('#apply-actions').click(function() {
        const actionName = $('#products-actions').val();

        switch(actionName) {
            case 'add-product':
                window.location.href = "/products/saveNew";
                break;
            case 'mass-delete':
                const values = [];

                $('.delete-checkbox:checked').each(function() {
                    values.push($(this).val());
                });

                $.ajax({
                    url: '/products/deleteMassApi',
                    type: 'post',
                    contentType: 'application/json',
                    data: JSON.stringify(values),
                    success: function() {
                        location.reload();
                    },
                    error: function(xhr) {
                        try {
                            const response = JSON.parse(xhr.responseText);

                            if (!response?.errors || !Array.isArray(response.errors) || !response.errors.length) {
                                showFormError('#product-list', 'Something went wrong');
                                return;
                            }

                            response.errors.forEach(({ key, message }) => {
                                showFormError('#product-list', message);
                            });
                        } catch (__) {
                            showFormError('#product-list', 'Something went wrong');
                        }
                    }
                });
                break;
            default:
                break;
        }
    });
});
