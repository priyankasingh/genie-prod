// Font resize init
(function($){
    $(function(){
        FontResize.init();
    });
})(jQuery);

// Clear inputs on focus
(function($){
	$(function() {

		var replaceableSelector = "input[type=text], input[type=email], textarea";

		// Setup placeholder labels
		$(".placeholder-labels label").each( function(){
			var labelFor = $( "input[id='" + $(this).attr('for') + "'], textarea[id='" + $(this).attr('for') + "']" );

			if( labelFor.is(replaceableSelector) ){
				$.data( labelFor[0], 'original', $( this ).text() );
				$(this).hide();
			}
		});
		// Change values on focus/blur
		$( replaceableSelector ).each( function(){
			$(this).blur( function(){
				var original = $.data( $(this)[0], 'original' );
				if( original && $(this).val() == '' ){
					$(this).val( original );
				}
			});
			$(this).focus( function(){
				var original = $.data( $(this)[0], 'original' );
				if( original && $(this).val() == original ){
					$(this).val( '' );
				}
			});
			$(this).blur();
		});
		// Change values on submit
		$('form').submit(function(){
			$(replaceableSelector).each( function(){
				var original = $.data( $(this)[0], 'original' );
				if( $(this).val() == original ) $(this).val( '' );
			});
		});

		// HTML 5 Placeholders
		$('.login-form input').placeholder();

		// Bind print class to print event
		$('.print').click(function(){
			window.print();
		})

		// High contrast mode
		var isContrast = $.cookie('contrast');

		if( isContrast ){
			$('body').addClass('high-contrast');
		} else {
			$('body').removeClass('high-contrast');
		}

		$('#contrast-on').click(function(){
			$('body').addClass('high-contrast');
			$.cookie("contrast", 'contrast', { path: '/', expires: 7 });
		})

		$('#contrast-off').click(function(){
			$('body').removeClass('high-contrast');
			$.cookie("contrast", 'contrast', { path: '/', expires: -1 });
		});

		// Network Type Dialog
		/*$.fancybox({
		    href : $(this).attr("href")
	    });*/
		$('.fancybox-ajax').fancybox({
			ajax : {
				type	: "GET",
			}
		});

	});
})(jQuery);
