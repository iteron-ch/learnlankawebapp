<script>
    jQuery(document).ready(function(){
        var frmObj = $('<?php echo $validator['selector']; ?>');
        var frmError = $('.alert-danger', frmObj);
        var frmSuccess = $('.alert-success', frmObj);
        $("<?php echo $validator['selector']; ?>").validate({
            highlight: function (element) { // hightlight error inputs
                $(element)
                .closest('.form-group, .checkbox').addClass('has-error'); // set error class to the control group
        $(element).addClass('error');
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                .closest('.form-group, .checkbox').removeClass('has-error'); // set error class to the control group
                $(element).removeClass('error');
            },
            errorElement: 'p', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ":hidden",
            //ignore: "",  // validate all fields including form hidden input
            errorPlacement: function(error, element) { 
                if (element.attr("type") == "radio") {
                    error.insertAfter(element.parents('div').find('.radio-list'));
                }
                else if (element.attr("type") == "checkbox") {
                    error.insertAfter(element.parents('label'));
                }
                else if (element.attr("type") == "file") {
                    error.insertAfter(element.closest('div'));
                }
                else {
                    if(element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                        element.addClass('error');
                    } else {
                        error.insertAfter(element);
                        element.addClass('error');
                    }
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit   
                frmSuccess.hide();
                frmError.show();
                if($('#js_flash_message')) {
                    $('#js_flash_message').addClass('space_for_message')
                    $('#js_flash_message').show('100');
                }
                $('html,body').animate({
                    scrollTop: '-200'
                }, 'slow');
                //Metronic.scrollTo(frmError, -200);
            },
            success: function (label) {
                label
                .closest('.form-group, .checkbox').removeClass('has-error'); // set success class to the control group
            },
            rules: <?php echo json_encode($validator['rules']); ?> ,
            messages: <?php echo json_encode($validator['messages']) ?>
        })
        //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
        $('.select2me,.e1', frmObj).change(function () {
            frmObj.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
        });
        $(':file', frmObj).change(function () {
            frmObj.validate().element($(this)); //
        });
        
    })
</script>
