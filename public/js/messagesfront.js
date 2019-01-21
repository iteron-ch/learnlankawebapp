$(document).ready(function() {
    $(".msg_table").on('click', '.delete_row', function(e) {
        e.preventDefault();
        fnOpenNormalDialog($(this), vars);

    });
});
function fnOpenNormalDialog(eleObj, vars) {
    $("#dialog-confirm").html(vars['confirmMsg']);

    // Define the Dialog and its properties.
    $("#dialog-confirm").dialog({
        resizable: false,
        modal: true,
        title: "Modal",
        height: 150,
        width: 400,
        buttons: {
            "Yes": function() {
                $(this).dialog('close');
                $.ajax({
                    url: vars['deleteUrl'],
                    method: 'POST',
                    data: {id: eleObj.data('id')},
                    beforeSend: function() {

                    },
                    success: function(returnData) {
                        //delete row and reload table
                        $("#js_flash_message_success").addClass('space_for_message').html('<div class="main_container">' + vars['successMsg'] + '</div>').show();
                        $("#num-" + eleObj.data('id')).remove();
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        //other stuff
                    },
                    complete: function() {

                    }
                });
            },
            "No": function() {
                $(this).dialog('close');
            }
        }
    });
}