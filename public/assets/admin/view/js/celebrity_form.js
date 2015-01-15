$(function()
{
    $('img[data-upload]').click(function(){
        $('input[name='+$(this).data('upload')+']').click();
    });

    $('#form_image_filename').change(function(){
        read_url(this, $(this).parents('.profileSetting').find('img'));
    });

    $("input[name$='[]']")
        .each(function(nil, obj){
            $('<input/>')
                .attr('type', 'button')
                .val('+')
                .attr('title', 'Add more item')
                .click(function(){
                    entry_fld = $(this).siblings(':first');
                    if (entry_fld.val().trim() != "")
                    {
                        clone($(this));
                        entry_fld.removeClass('validate[required]').select();
                    }
                })
                .insertAfter(obj);
        })
        .keydown(function(e){
            if (e.keyCode === 13)
            {
                e.preventDefault();
                $(this).siblings(':last').click();
            }
        });

    set_autocomplete($("input[name='nonprofit[]']"), window.autocomplete_arr['nonprofit[]']);
    set_autocomplete($("input[name='cause[]']"), window.autocomplete_arr['cause[]']);
    set_autocomplete($("input[name='field[]']"), window.autocomplete_arr['field[]']);
});

function clone(obj)
{
    p = obj.parent();
    p.clone()
        .find('input[type=button]')
            .val('x')
            .css('backgroundColor', 'pink')
            .attr('title', 'Delete this item')
            .unbind('click')
            .click(function(){
                $(this).parent().remove();
            })
            .end()
        .insertBefore(p);
}

// eof
