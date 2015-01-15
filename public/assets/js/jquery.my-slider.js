(function($) {
    $.fn.mySlider = function(b) {
        var c = {
            transition: 'slide',
            automatic: false,
            speed: 1000,
            pause: 2000
        },
            b = $.extend(c, b),
            btn = true;

        if (b.pause <= b.speed)
            b.pause = b.speed + 100;

        return this.each(function() {
            var a = $(this);
            var pp = a.parent();
            a.wrap('<div class="slider-wrap" style="float:left" />');
            if (a.children().length > 1)
            {
                // pp.prepend(
                //     $('<div/>').attr('type', 'button').css({width:'22px', float: 'left', height: a.children().css('height'), background:'url(/assets/img/arrow_left.gif) no-repeat center center'})
                //         .click(function(){
                //             slide_prev();
                //         })
                // );
                pp.append(
                    $('<div/>').attr('type', 'button').css({width:'22px', float: 'left', height: a.children().css('height'), background:'url(/assets/img/arrow_right.gif) no-repeat center center'})
                        .click(function(){
                            slide_next();
                        })
                );
            }
            a.css({
                'width': '99999px',
                'position': 'relative',
                'padding': 0
            });
            if (b.transition === 'slide') {
                a.children().css({
                    'float': 'left',
                    'list-style': 'none'
                });
                $('.slider-wrap').css({
                    'width': a.children().width(),
                    'overflow': 'hidden'
                })
            }
            if (b.transition === 'fade') {
                a.children().css({
                    'width': a.children().width(),
                    'position': 'absolute',
                    'left': 0
                });
                for(var i = a.children().length, y = 0; i > 0; i--, y++) {
                    a.children().eq(y).css('zIndex', i + 99999)
                }
                fade()
            }

            if (b.automatic)
                if (b.transition === 'slide') slide();

            function slide() {
                setInterval(function() {
                    a.animate({
                        'left': '-' + a.parent().width()
                    }, b.speed, function() {
                        a.css('left', 0).children(':first').appendTo(a)
                    })
                }, b.pause)
            }
            function fade() {
                setInterval(function() {
                    a.children(':first').animate({
                        'opacity': 0
                    }, b.speed, function() {
                        a.children(':first').css('opacity', 1).css('zIndex', a.children(':last').css('zIndex') - 1).appendTo(a)
                    })
                }, b.pause)
            }
            function slide_prev() {
                if ( ! btn) return;
                btn = false;
                a.children(':last').css('marginLeft', -1 * a.parent().width()).prependTo(a);

                a.animate({
                    'left': '+' + a.parent().width()
                }, b.speed, function() {
                    a.css('left', 0);
                    a.children(':first').css('marginLeft', '');
                    btn = true;
                })
            }
            function slide_next() {
                if ( ! btn) return;
                btn = false;
                a.animate({
                    'left': '-' + a.parent().width()
                }, b.speed, function() {
                    a.css('left', 0).children(':first').appendTo(a)
                    btn = true;
                })
            }
        })
    }
})(jQuery);