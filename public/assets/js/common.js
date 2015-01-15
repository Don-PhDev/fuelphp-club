var mysetting = {
	mousehoverbg: '#54A7E5',

	/**
	 * jQuery UI Date Picker Settings
	 */
	yearRange : 'c-90:c+20', 
	changeMonth: true, 
	changeYear: true, 
	dateFormat: 'yy-mm-dd', 
	defaultDate: '1990-01-01',

	/**
	 * Pages where input auto focus is not desired
	 */
	// no_auto_focus: [
	// 	'/about'
	// ]
}

$.fn.sort_selection = function(){
    var my_options = $(this).children('option');
    my_options.sort(function(a,b) {
        if (a.text > b.text) return 1;
        else if (a.text < b.text) return -1;
        else return 0
    })
   return my_options;
}

$(function()
{
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
	 * Code by previous developer
	 */
	$(function() {
		$('#da-slider').cslider({
			autoplay	: false
		});
	});

	$('#tl_content').easyTabs({defaultContent:1});
	$('#cs_content').easyTabs({defaultContent:1});
	
	$(".youtube").colorbox({iframe:true, innerWidth:425, innerHeight:344});
	/**
	 * end
	 */

    /**
     * Focus on data-focus with or without delay
     */
    f = $('*[data-focus]');
    if (f.data('focus') == '1')
        f.focus();
    else
        setTimeout('f.focus()', f.data('focus'));

	/**
	 * Assign "form_" + name as object id
	 */
	$.each($('input, select, textarea'), function(nil, obj){
		name = $(obj).attr('name');
		if (name !== undefined)
			$(obj)
				.attr('id', "form_" + name)
				// .addClass($(obj).get(0).tagName.toLowerCase());
	});

    $.each($('input[data-href]'), function(nil, obj){
        $(obj).click(function(){
            window.location = $(this).data('href');
        })
    });

	$('textarea.autosize')
		.focus(function() {
			current_textarea = $(this).attr('id');
			$(this).animate({height:'228px'});
		})
		.blur(function() {
			setTimeout("minimize_textarea('" + $(this).attr('id') + "')", 1000);
		});

	/**
	 * Automatically assign to empty href
	 */
	if ($('a[href=""]').length != 0)
	{
		$.each($('a[href=""]'), function(nil, b){
			$(b).attr('href', 'javascript:void(0)');
		});
	}

	/**
	 * Automatically hide options column if it exists
	 */
	if ($('th.opt').length != 0)
	{
		$('th.opt').html('&raquo;');
		col_option(0);
	}

	/**
	 * Disappear alert system messages after 10 seconds if they exists
	 */
	if ($('.okhide, .okhidelow').length != 0)
		hide_flash($('.okhide, .okhidelow'), 2000);

	/**
	 * Hide alert system messages after 10 seconds if they exists
	 */
	if ($('table.grid').length != 0)
	{
		$('body').append('<div id="confirm-delete"><p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>This items will be permanently deleted and cannot be recovered. Are you sure?</p></div>');
		define_confirm_delete();
	}

	/**
	 * Allow definition of <tr data-click="click" data-location="/go/to/this/path"...
	 *  and redirect if clicked
	 */
	$('tr[data-type=click]').click(function(){
		window.location = $(this).data('location');
	});

	row = $('tr[data-type=click]');
	row.mouseenter(function(){
		window.hold_bg = row.children('td').css('backgroundColor');
		row.children('td').css('backgroundColor', mysetting.mousehoverbg);
	}).mouseleave(function(){
		row.children('td').css('backgroundColor', window.hold_bg);
	});

	/**
	 * Apply select, option style depending on selected option
	 */
	$('select').change(function(){
		select_style(this, (this.value != ''));
	});

	$('input.date').datepicker({
		yearRange: window.mysetting.yearRange, 
		changeMonth: window.mysetting.changeMonth, 
		changeYear: window.mysetting.changeYear, 
		dateFormat: window.mysetting.dateFormat, 
		defaultDate: window.mysetting.defaultDate
	});

	/**
	 * Hide error message after field is changed
	 */
	$('input, select').change(function(){
		$(this).siblings('div.flderrmsg').slideUp('slow');
		// $(this).parent().removeClass('bgred').addClass('bg');
	});

	$.each($('button[data-href]'), function(nil, btn){
		$(btn).on('click', function(e){e.preventDefault(); window.location=$(this).data('href')});
	});

	$.each($('button[data-click]'), function(nil, btn){
		$(btn).on('click', function(e){
			eval('result='+$(this).data('click'));
			if (result !== true)
				e.preventDefault();
		});
	});

	/**
	 * Helper for file upload.  Note that works only for one upload per form.
	 */
	window.f4s = $('input[type=file].hide4span');
	f4s.parent()
		.append($('<span></span>').addClass('span4upload').html($('input[type=file].hide4span').data('val') || '&nbsp;').click(function(){f4s.click()}))
		.append($('<span></span>').addClass('span4uploadbtn').html('Browse...').click(function(){f4s.click()}));
	f4s.change(function(){f4s.siblings('.span4upload').html($(this).val())})
});

function define_confirm_delete()
{
	/**
	 * Hide div dialog
	 */
    $('#confirm-delete').hide();

	$.each($('table.grid a.delete'), function(nil, a){
		$(a).click(function(){
			confirm_delete($(this).attr('data-id'), $(this).attr('data-path'), $(this).attr('data-name'));
		});
	});
}

function confirm_delete(id, path, name)
{
    $('#confirm-delete')
    	.attr('title', 'Delete Confirmation')
    	.dialog({
    		open: function(e,u){
				$(this).parent().find('span').first().html('Delete ' + name);
    		},
	        resizable: false,
	        modal: true,
	        buttons: {
	            "Delete Confirmation": function() {
					window.location = '/' + path + '/delete/' + id;
	            },
	            Cancel: function() {
	                $( this ).dialog( "close" );
	            }
	        }
	    });
}

function hide_flash(obj, delay)
{
	if (delay === undefined)
		// $(obj).slideUp('slow');
		$(obj).fadeOut('slow');
	else
		// setTimeout(function(){$(obj).slideUp('slow')}, delay);
		setTimeout(function(){$(obj).fadeOut('slow')}, delay);
}

function col_option(show)
{

	if (show)
	{
		$('th.opt')
			.html('&laquo; Options')
			.attr('title', 'Hide options')
			.unbind('click')
			.click(function(){
				col_option(false);
			});

		$('td.opt').slideDown();
	}
	else
	{
		$('th.opt')
			.html('&raquo;')
			.attr('title', 'Show options')
			.unbind('click')
			.click(function(){
				col_option(true);
			});

		if (false === show)
			$('td.opt').slideUp();
		else
			$('td.opt').hide();
	}
}

/**
 * Set minimum height of textarea to 40px
 */
function minimize_textarea(obj_id)
{
	if ('228px' == $('#'+obj_id).css('height'))
		$('#'+obj_id).animate({height:'40px'});
}

function select_style(sel, selected)
{
	if (selected === undefined)
		if (sel.val() == '')
			selected = false;
		else
			selected = true;
	
	if (selected)
		$(sel).css({color:'#000', fontStyle:'normal'});
	else
		$(sel).css({color:'#74AD74', fontStyle:'italic'});

	opts = $(sel).children('option');
	opts.css({color:'#000', fontStyle:'normal'});	

	if (opts.filter(':first').val() == '')
		opts.filter(':first').css({color:'#74AD74', fontStyle:'italic'});
}

function read_url_improved(input, img_obj)
{
    if (input.files && input.files[0])
    {
        var reader = new FileReader();
        reader.onload = function(e) {
            img_obj.attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function read_url(input)
{
	if (input.files && input.files[0])
	{
		var reader = new FileReader();
		reader.onload = function (e)
		{
			$('aside img')
				.css({height:'0', width:'0'})
				.attr('src', e.target.result);

			setInterval("$('aside img').css({height:'50%', width:'50%'})", 1000);
		};
		reader.readAsDataURL(input.files[0]);
	}
}

function captcha_validation()
{
	return true; /* using honeypot captcha for now */

    // $('#captcha_message').removeClass('flderrmsg').addClass('fldokmsg').html("Validating captcha...").show();

    // jQuery.ajaxSetup({async:false});

    // $.get('/ajax/recaptcha'
    //         + '/{{ C4c.recaptcha_private_key }}'
    //         + '/{{ app_server_ip }}'
    //         + '/' + $('div.recaptcha_input_area > span > input').val()
    //         + '/' + $('#form_recaptcha_response_field').val(),
    //     function(data){
    //         if (data.incorrect)
    //         {
    //             $('#form_recaptcha_response_field').focus().select();

    //             if ('incorrect-captcha-sol' == data.message)
    //                 $('#captcha_message').removeClass('fldokmsg').addClass('flderrmsg')
    //                     .html("You mistyped the captcha text above.  Please try again.");
    //             else
    //                 $('#captcha_message').removeClass('fldokmsg').addClass('flderrmsg')
    //                     .html("Captcha validation failure (" + data.message + ")");
    //         }
    //         window.recaptcha_validated = data.success;
    //     },
    //     'json'
    // );

    // return window.recaptcha_validated;
}

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
