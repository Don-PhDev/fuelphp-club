$(function()
{
    load_links('field');

    /**
     * Quickfix: remove right panel
     */
    $('div#cp_right').remove();
    $('div#cp_left').css('width', '100%');

    $(document).bind('mousemove', function(e){
        $('#tail').css({
            left:  e.pageX + 20,
            top:   e.pageY
        });
    });

    $('a[data-image]').mouseover(function(){
        $('#tail')
        .html($('<img/>').attr('src', '/web_data/celebrities/img/' + $(this).data('image'))).append($('<h2/>').html($(this).html())).show();
    }).mouseout(function(){
        $('#tail').hide();
    });

    $('.celeb-members').mySlider();
});

$(window).scroll(function () {
    if ($(window).scrollTop() >= $(document).height() - $(window).height() - 300) {
        load_celeb_links();
    }
});

function show_spinner(name, msg)
{
    $("#" + name + "_spinner").spin();

    $('<div/>').attr('id', name + '_spinner_msg')
        .html('<br><br>' + msg)
        .css({textAlign: 'center', margin: '50px'})
        .appendTo($('#' + name + '_spinner'));
}

function hide_spinner(name)
{
    $("#" + name + "_spinner").spin(false);
    $('div#' + name + '_spinner_msg').remove();
}

function load_links(name)
{
    container = $('div#' + name + '-links');

    show_spinner(name, "Loading " + name);

    if (container.html().trim() == "")
    {
        window.loading_wait = true;

        container.load('/ajax/loader/' + name + '_links', function() {
            hide_spinner(name);
            window.loading_wait = false;

            /**
             * Load celebrity count for each cause
             */
            load_count_foreach('field');

            /**
             * Load celebrity count for each cause
             */
            load_count_foreach('cause');

            /**
             * Load next ajax
             */
            load_celeb_links();
        });
    }
}

function load_celeb_links()
{
    if ($('div.celebrity-links').html().trim() == "")
    {
        /**
         * Ensures one call only
         */
        if ( ! parent.called)
        {
            parent.called = true;
            show_spinner('celebrities', 'Requesting all celebrities, might take some seconds...');

            $('div.celebrity-links').load('/ajax/loader/celebrities_links', function() {
                hide_spinner('celebrities');
                $('div#ads-celebrity-links').css('height', (parseInt($('div.celebrity-links').css('height')) - 70) + 'px');
            });
        }
    }
}

function load_count_foreach(category)
{
    $.each($('div.' + category + ' > span'), function(nil, obj){
        $.get('/ajax/loader/count_celeb_for_' + category + '/' + $(obj).data(category + '-id'),
            function(result){
                if (result.success)
                    $(obj).prepend(result.count);
            }, 'json'
        )
    });
}

// eof
