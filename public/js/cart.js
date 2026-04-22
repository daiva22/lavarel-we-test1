$(document).ready(function () {
    $(document).on('submit', '.add-to-cart-form', function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');
        let formData = form.serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                alert(response.message);

                if (response.cart_count !== undefined) {
                    $('#cart-count').text(response.cart_count);
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    });
});