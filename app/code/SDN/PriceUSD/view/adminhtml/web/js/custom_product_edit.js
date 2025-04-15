require(['jquery'], function ($) {
    $(document).ready(function () {
        $('.usd_custom_price').on('input', function () {
            $('.price').val($(this).val());
        });
    });
});