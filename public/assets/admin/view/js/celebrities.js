divc = [];

$(function(){

    $('div.content input[type=button]').click(function(){
        mark_click($(this));
    });

    $('body').click(function(){
        if (Math.round(new Date().getTime() / 100) > window.btn_clicked_time)
            unset_delete_marks();
    });
});

function mark_click(obj)
{
    window.btn_clicked_time = Math.round(new Date().getTime() / 100);

    obj
        .val('Confirm delete')
        .addClass('redBtn')
        .unbind('click')
        .click(function(){
            window.btn_clicked_time = Math.round(new Date().getTime() / 100);
            $this = this;
            $.post('/admin/ajax/delete/celebrity', { id: obj.data('id') }, function(result){
                if (result.success)
                    show_removed($($this));
            }, 'json')
        });
}

function show_removed(obj)
{
    p = obj.parent();
    divc.push(p);
    p.addClass('item-deleted').html('Permanently deleted');
    setTimeout(function(){
        p = divc.shift();
        p.removeClass('item-deleted').addClass('item-old-deleted');
    }, 1500);
    obj.remove();
}

function unset_delete_marks()
{
    $.each($('div.content input[class=redBtn]'), function(nil, obj){
        $(obj)
            .val('x')
            .removeClass('redBtn')
            .unbind('click')
            .click(function(){
                mark_click($(this));
            });
    });
}

// eof
