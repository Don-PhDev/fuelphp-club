$(function(){
    /**
     * Filter entry for numeric
     */
    $("input.numeric").keydown(function(event) {
        // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });

    /**
     * Focus on data-focus with or without delay
     */
    f = $('*[data-focus]');
    if (f.data('focus') == '1')
        f.focus();
    else
        setTimeout('f.focus()', f.data('focus'));

    /**
     * Assign "form_" + name as object id if nothing was assigned
     */
    $.each($('input, select, textarea'), function(nil, obj){
        name = $(obj).attr('name');
        if (name !== undefined && $(obj).attr('id') === undefined)
            $(obj)
                .attr('id', "form_" + name)
                .addClass($(obj).get(0).tagName.toLowerCase());
    });

    /**
     * a href="submit" to submit form
     */
    $('a[href=submit]').unbind('click').click(function(e){
        e.preventDefault();
        /**
         * hidden submit
         */
        $('button.hidden_submit').click();
    });

    /**
     * Automatically append button when a[href=submit] exists on a form
     */
    $.each($('form a[href=submit]'), function(){
        $("<button/>").addClass('hidden_submit').insertAfter($(this));
    });

    /**
     * Disappear alert system messages after 10 seconds if they exists
     */
    if ($('.okhide, .okhidelow').length != 0)
        hide_flash($('.okhide, .okhidelow'), 2000);

    /**
     * div to show when ajax is called
     */
    $('div#loading')
        .hide()
        .ajaxStart(function(){
            $(this).show();
        })
        .ajaxStop(function(){
            $(this).hide();
        });

});

function hide_flash(obj, delay)
{
    if (delay === undefined)
        // $(obj).slideUp('slow');
        $(obj).fadeOut('slow');
    else
        // setTimeout(function(){$(obj).slideUp('slow')}, delay);
        setTimeout(function(){$(obj).fadeOut('slow')}, delay);
}

function read_url(input, selector)
{
    if (input.files && input.files[0])
    {
        var reader = new FileReader();
        reader.onload = function (e)
        {
            if (typeof selector == 'string')
            {
                $(selector)
                    .css({height:'0', width:'0'})
                    .attr('src', e.target.result);

                setInterval("$('" + selector + "').css({height: 'auto', width: 'auto', minWidth:'180px', maxHeight:'180px', maxWidth:'180px'})", 1000);
            }
            else
            {
                console.log($(selector).html());
                selector
                    .css({height:'0', width:'0'})
                    .attr('src', e.target.result);

                setInterval(function(){$(selector).css({height: 'auto', width: 'auto', minWidth:'180px', maxHeight:'180px', maxWidth:'180px'})}, 1000);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
 
function is_valid_email(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

/**
 * Input with search
 */

$(function()
{
    /**
     * Search field on keyup
     */
    $('input.input_search').keyup(function()
    {
        if (window.tmp_input_search !== this.value)
        {
            window.tmp_input_search = this.value;

            if ('' === this.value)
                $('div#' + $(this).attr('name') + '_search_result').html('');
            else
            {
                $this = this;
                ajax_path = $(this).data('ajax');
                $.getJSON(ajax_path + this.value, function(result){
                    if (result.success)
                        eval(ajax_path.slice(1, -1).replace(/\//g, '_') + "(result.data, $($this))");
                });
            }
        }
    });
});

function set_autocomplete(obj, tags)
{
    obj.autocomplete({
        source: function( request, response ) {
            var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
            response( $.grep( tags, function( item ){
                return matcher.test( item );
            }) );
        }
    });    
}

// eof
