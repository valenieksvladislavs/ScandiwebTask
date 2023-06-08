$(document).ready(function() {
    const productTypeToFieldMapping = {
        'book': {
            description: 'Please provide the book weight in kilograms',
            fields: [
                {
                    name: 'weight',
                    label: 'Weight(kg)',
                    type: 'number',
                    rules: { required: true, number: true },
                    messages: { required: 'Please specify book weight', number: 'Book weight should be a number' }
                },
            ]
        },
        'dvd': {
            description: 'Please provide disk size in megabytes',
            fields: [
                {
                    name: 'size',
                    label: 'Size(mb)',
                    type: 'number',
                    rules: { required: true, digits: true },
                    messages: { required: 'Please specify disc size', digits: 'Disk size must be an integer number' }
                }
            ]
        },
        'furniture': {
            description: 'Please provide dimensions in HxWxL format',
            fields: [
                {
                    name: 'height',
                    label: 'Height(mm)',
                    type: 'number',
                    rules: { required: true, digits: true },
                    messages: { required: 'Please specify furniture height', digits: 'Furniture height must be an integer number' }
                },
                {
                    name: 'width',
                    label: 'Width(mm)',
                    type: 'number',
                    rules: { required: true, digits: true },
                    messages: { required: 'Please specify furniture width', digits: 'Furniture width must be an integer number' }
                },
                {
                    name: 'length',
                    label: 'Length(mm)',
                    type: 'number',
                    rules: { required: true, digits: true },
                    messages: { required: 'Please specify furniture length', digits: 'Furniture length must be an integer number' }
                }
            ]
        }
    };

    $('#productType').change(function() {
        const type = $(this).val();
        const additionalFields = $('#additional-fields');
        additionalFields.find('input').each(function() {
            $(this).rules('remove');
        });
        additionalFields.empty();

        const additionalInfo = productTypeToFieldMapping[type];

        additionalInfo.fields.forEach(field => {
            additionalFields.append(`<div class="form-group row mt-4"><label for="${field.name}" class="col-sm-2 col-form-label">${field.label}</label><div class="col-sm-10"><input type="${field.type}" id="${field.name}" name="${field.name}" class="form-control" required></div></div>`);
            const addedField = $(`#${field.name}`);
            addedField.rules('add', {
                ...field.rules,
                messages: field.messages
            });
        });

        additionalFields.append(`<p class="mt-4">${additionalInfo.description}</p>`);
    });

    $('#product-form').validate({
        rules: {
            'sku': 'required',
            'name': 'required',
            'price': 'required',
            'productType': 'required'
        },
        messages: {
            'sku': 'Please specify product sku',
            'name': 'Please specify product name',
            'price': 'Please specify product price',
            'productType': 'Please specify product type',
        },
        errorClass: 'text-danger',
        errorElement: 'div',
        highlight: function(element) {
            $(element).addClass('is-invalid');
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
            $(element).closest('.form-group').removeClass('has-error');
        },
        invalidHandler: function() {
            $("#submit-product-form").removeClass('disabled').removeAttr('disabled');
        },
        submitHandler: function(form) {
            const data = {
                sku: $('#sku').val(),
                name: $('#name').val(),
                price: $('#price').val(),
                productType: $('#productType').val()
            };

            const additionalFields = $('#additional-fields');
            additionalFields.find('input').each(function() {
                data[$(this).attr('id')] = $(this).val();
            });

            $.ajax({
                url: '/product/saveApi',
                type: 'post',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function() {
                    showSuccessNotification('#product-form', 'Product successfully created', function() {
                        window.location.href = "/product/list";
                    })
                },
                error: function(xhr) {
                    $("#submit-product-form").removeClass('disabled').removeAttr('disabled');
                    try {
                        const response = JSON.parse(xhr.responseText);

                        if (!response?.errors || !Array.isArray(response.errors) || !response.errors.length) {
                            showDangerNotification('#product-form', 'Something went wrong');
                            return;
                        }

                        response.errors.forEach(({ key, message }) => {
                            if (key === 'system') {
                                showDangerNotification('#product-form', message);
                            } else {
                                const validator = $('#product-form').validate();
                                validator.showErrors({[key]: message});
                            }
                        });
                    } catch (__) {
                        showDangerNotification('#product-form', 'Something went wrong');
                    }
                }
            });

            return false;
        }
    });

    $('#submit-product-form').click(function() {
        $(this).addClass('disabled').attr('disabled', 'disabled');
        $('#product-form').submit();
    });
});
