$(document).ready(function() {
    $('#mass-delete').click(function() {
        const values = [];

        $('.delete-checkbox:checked').each(function() {
            values.push($(this).val());
        });

        $.ajax({
            url: '/product/deleteMassApi',
            type: 'post',
            contentType: 'application/json',
            data: JSON.stringify(values),
            success: function() {
                showSuccessNotification('#product-list', 'Selected products successfully removed', function() {
                    location.reload();
                })
            },
            error: function(xhr) {
                try {
                    const response = JSON.parse(xhr.responseText);

                    if (!response?.errors || !Array.isArray(response.errors) || !response.errors.length) {
                        showDangerNotification('#product-list', 'Something went wrong');
                        return;
                    }

                    response.errors.forEach(({ message }) => {
                        showDangerNotification('#product-list', message);
                    });
                } catch (__) {
                    showDangerNotification('#product-list', 'Something went wrong');
                }
            }
        });
    });
});
