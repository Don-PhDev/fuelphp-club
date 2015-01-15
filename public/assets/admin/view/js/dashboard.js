$(function(){

    tbl_invite = $('table#tbl-invite');

    tbl_invite.find('input[type=button]').click(function(){
        email_recipient_entry(this);
    });

});

function email_recipient_entry(btn)
{
    $btn = btn;
    tbl_invite.find('input[type=text]').remove();

    /**
     * Reset buttons
     */
    tbl_invite.find('input[type=button]')
        .val('send email')
        .unbind('click')
        .click(function(){
            email_recipient_entry(this);
        }
    );

    $(btn).parent()
        .prepend(
            $('<input/>')
                .attr('type', 'text')
                .attr('placeholder', 'enter email here...')
        );

    $this_entry = tbl_invite.find('input[type=text]');
    $this_entry
        .focus()
        .keyup(function(e){
            if (e.keyCode === 13)
                send_email();
        })

    $(btn)
        .val('submit')
        .unbind('click')
        .click(function(){
            send_email();
        }
    );
}

function send_email()
{
    if (is_valid_email( $this_entry.val() ))
    {
        $("#load_spinner").spin();

        $.post('/admin/ajax/invite_nonprofit',
            {
                nonprofit_id: $($btn).data('npid'),
                email: $this_entry.val()
            },
            function(result){
                $("#load_spinner").spin(false);
                if (result.success)
                {
                    $($btn)
                        .parent()
                            .html($('<span/>').html('sent to ' + $this_entry.val())
                            .css('color', 'darkgreen'))
                            .parent()
                                .find('td:first').prepend('&middot; Invitation for ')
                                .css('fontWeight', 'normal');
                }
                else
                {
                    alert(result.message ? result.message : "Failed attempt to send email invitation.  Please try again later.");
                }
            },
            "json"
        );
    }
    else
    {
        $( "#dialog-message" ).dialog({
            modal: true,
                buttons: {
                Ok: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
        $this_entry.focus().select();
    }
}
