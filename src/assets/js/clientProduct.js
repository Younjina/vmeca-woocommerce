jQuery(document).ready(function ($) {
    $(".parameter-value").change(function () {
        var form_action = '';
        $(".parameter-value").each(function (i, item) {
            var id = item.id;
            var val = $('#' + id).val();

            if (val != '') {
                $(this).attr('name', id);
            }
        });

        $("#product_list").submit();
    });

    $("#reset").click(function () {
        $(".parameter-value").val('');
        $("#product_list").submit();
    }); //reset button click
});