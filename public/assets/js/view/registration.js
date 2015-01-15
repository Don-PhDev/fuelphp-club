$(function(){

    window.us_states_arr = $.map(us_states, function (value, key) { return value; });

    // $('#form_birth_date').datepicker({defaultDate: "1990-01-01"});

    $('#form_confirm_password').change(function(){ if ($(this).val()) $(this).removeClass('redinput')});
    $('#form_password').change(function(){ if ($(this).val()) $(this).removeClass('redinput')});
    $('#form_city').change(function(){ if ($(this).val()) $(this).removeClass('redinput')});
    $('#form_state').change(function(){ if ($(this).val()) $(this).removeClass('redinput')});
    $('#form_country').change(function(){ if ($(this).val()) $(this).removeClass('redinput')});
    $('#form_last_name').change(function(){ if ($(this).val()) $(this).removeClass('redinput')});
    $('#form_first_name').change(function(){ if ($(this).val()) $(this).removeClass('redinput')});
    $('#form_alias').change(function(){ if ($(this).val()) $(this).removeClass('redinput')});
    $('#form_email').change(function(){ if ($(this).val()) $(this).removeClass('redinput')});

    $('#form_country').change(function(){
        call_set_autocomplete($('#form_state'), $(this).val());
    });
    $('#form_nprofit_country_1').change(function(){
        call_set_autocomplete($('#form_nprofit_state_1'), $(this).val());
    });
    $('#form_nprofit_country_2').change(function(){
        call_set_autocomplete($('#form_nprofit_state_2'), $(this).val());
    });

    set_autocomplete($('#form_nprofit_name_1, #form_nprofit_name_2'), nonprofit_arr);

    $('#form_terms_agreed').click(function(){
        if ($('#form_terms_agreed').is(':checked'))
            $('#terms_agreed_message').hide();
        else
            $('#terms_agreed_message').addClass('flderrmsg').html("To proceed, please signify your agreement by checking the check box above.").show();
    });
    
    $('button[class=ragister_btn]').click(function(e)
    {
        valid = true;
        
        // if ("" == $('#form_recaptcha_response_field').val())
        // {
        //     $('#captcha_message').addClass('flderrmsg').html("Please enter the captcha text above.").show();
        //     $('#form_recaptcha_response_field').focus();
        //     valid = false;
        // }
    
        if ($('#form_confirm_password').val() == '') { valid = false; /*$('#form_confirm_password').addClass('redinput').focus();*/ }
        if ($('#form_password').val() == '') { valid = false; /*$('#form_password').addClass('redinput').focus();*/ }
        if ($('#form_city').val() == '') { valid = false; /*$('#form_city').addClass('redinput').focus();*/ }
        if ($('#form_state').val() == '') { valid = false; /*$('#form_state').addClass('redinput').focus();*/ }
        if ($('#form_country').val() == '') { valid = false; /*$('#form_country').addClass('redinput').focus();*/ }
        if ($('#form_birth_date_month').val() == '') { valid = false; /*$('#form_birth_date_month').addClass('redinput').focus();*/ }
        if ($('#form_birth_date_day').val() == '') { valid = false; /*$('#form_birth_date_day').addClass('redinput').focus();*/ }
        if ($('#form_last_name').val() == '') { valid = false; /*$('#form_last_name').addClass('redinput').focus();*/ }
        if ($('#form_first_name').val() == '') { valid = false; /*$('#form_first_name').addClass('redinput').focus();*/ }
        if ($('#form_alias').val() == '' || $('span#msg_taken').length > 0) { valid = false; /*$('#form_alias').addClass('redinput').focus();*/ }
        if ($('#form_email').val() == '') { valid = false; /*$('#form_email').addClass('redinput').focus();*/ }
        
        if (valid)
        {
            if ( ! $('#form_terms_agreed').is(':checked'))
            {
                $('#terms_agreed_message').addClass('flderrmsg').html("To proceed, please signify your agreement by checking the check box above.").show();
                $('#terms_agreed_message').siblings('.flderrmsg').remove();
                $('#form_terms_agreed').focus();
                return false;
            }
        }

        // return valid && captcha_validation();

        /**
         * Return true for the browser to do the "required" validation
         */
        return true;
    });

    $('div#help-find input').click(function(){
        window.open($(this).data('link'));
    });

    // $( "#form_birth_date" ).datepicker( "option", "dateFormat", 'mm/dd' );

    /**
     * Check username availability
     */
    $('#form_alias').blur(function(){

        if ($(this).val() == "")
            return 

        if (c4c.alias === $(this).val())
            return;

        c4c.alias = $(this).val();
        $this = this;

        $.get('/ajax/check_username_availability', { username: $(this).val() }, function(data){
            if (data.is_taken)
            {
                $('<span/>')
                    .attr('id', 'msg_taken')
                    .html('Someone already has that username. Try another?')
                    .css('color', 'red')
                .insertAfter($($this));
            }
            else
            {
                $('#msg_taken').remove();
            }
        }, 'json');
    });
});

function call_set_autocomplete(obj, val)
{
    switch (val)
    {
        case "United States":
            set_autocomplete(obj, us_states_arr);
            break;

        case "":
        default:
            set_autocomplete(obj, []);
            break;
    }
}
