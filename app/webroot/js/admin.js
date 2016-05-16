(function($){
	// CKEDITOR
	function initCkEditor(){
		$('textarea').each( function(){
			var name = $(this).attr('name');
			if(name) CKEDITOR.replace( name );
		} );
	}
	
	$(function(){
		initCkEditor();
	
	// Mapping toolset 	
		$( "#mappingSearchField" ).autocomplete({
		      source: function( request, response ) {
		      $('#search-loader').fadeIn();
		        $.ajax({
		          url: "/admin/services/autocomplete",
		          dataType: "json",
		          type: 'GET',
		          data: {
				    name_startsWith: request.term
		          },
		          success: function( data ) {
		          response(data);
		          $('#search-loader').fadeOut();    
		          }
		        });
		      },
		      minLength: 2,
		      select: function( event, ui ) {
			      $('#mappingSearchField').val(ui.item.value);
				$('#serviceId').val(ui.item.id);
				$(".mapping-current-service").text($("#mappingSearchField").val());
				$('.mapping-second-stage').fadeIn();
				
			},
		      open: function() { 
		      //console.log("its opened");
		        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		      },
		      close: function() {
		        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		      }
		});

		// Quick-add services
		$( '#mapping-service-form form' ).submit(function(){
			$(this).find('.ajax-loader').fadeIn();
			$.ajax({
				type: "POST",
				url: $(this).attr('action'),
				data: $(this).serialize(),
				success: function( data ){
					$('#mapping-service-form form').replaceWith(data);
					initMap();
					initCkEditor();
				},
				dataType: 'html',
			});
			return false;
		});
			
		$("#mapping-search form").submit(function(){
		// Fill the quick add form with selected service
		
		$('#search-loader2').fadeIn();
		$.ajax({
			type: "GET",
			url: "/admin/services/add" + '/' + $("#serviceId").val(),
			success: function( data ){
				$('#mapping-service-form form').replaceWith(data);
				$('#search-loader2').fadeOut();
				initMap();
				initCkEditor();
			},
			dataType: 'html',
		});

		// Set google links
		
		$(".google-search-box").fadeIn();
			$("a#google-link").attr("href" , "https://www.google.com/search?q=" +  $('#mappingSearchLocation').val() + "+" +  $('#mappingSearchServiceType').val());
			$("a#google-map").attr("href" , "https://www.google.com/maps/preview?q=" +  $('#mappingSearchLocation').val() + "+" +  $('#mappingSearchServiceType').val());

			return false;
		});
	
	});
	

	// MULTI-ROW
	function adminReplaceAttrNums( elem, attrName, newVal ){
		elem.attr(attrName, elem.attr(attrName).replace( /[0-9]+/, newVal ));
		return elem;
	}
	
	$(function(){
		var newId = $( '.multi-row' ).length;
		$( '.multi-row-add' ).click(function(){
			var rows = $(this).parents('.multi-rows');
			var newElem = rows.find( '.multi-row:last' ).clone( true );
			
			// Edit ids
			newId++;
			
			// Labels
			newElem.find('label').each(function(){
				adminReplaceAttrNums( $(this), 'for', newId);
			});
			
			// Inputs
			newElem.find('textarea, input, select').each(function(){
				adminReplaceAttrNums( $(this), 'id', newId);
				adminReplaceAttrNums( $(this), 'name', newId);
			});
			newElem.find('input, textarea').val('');
			newElem.find('input[name*="[id]"]').remove();
			
			// Append
			newElem.appendTo( rows.find('.multi-rows-inner') );
			return false;
		});
		
		$( '.multi-row-remove' ).click(function(){
			if( $( this ).parents( '.multi-rows' ).find( '.multi-row' ).length > 1 )
				$( this ).parents( '.multi-row' ).remove();
			else
				$( this ).parents( '.multi-row' ).find('input, textarea, select').val('');
			return false;
		});
	});

		
	// ADMIN MAP
	function updateCoords( coordsStr ){
		var coords = coordsStr.replace( '(', '' ).replace( ')', '' ).split(',');
		$('#ServiceLat').val(coords[0]);
		$('#ServiceLng').val(coords[1]);
	}
	
	function getCoords(){
		return [
			$('#ServiceLat').val(),
			$('#ServiceLng').val()
		];
	}
	
	$( function(){
		initMap();
	});
	
	function initMap(){
		// Quit if no map
		if( 0 == $('#AdminMap').length ) return;
		
		// Setup map
		var latlng = new google.maps.LatLng(53.626502, -2.048950);
		var ohMapOptions = {
			zoom: 5,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			navigationControl: true,
			mapTypeControl: false,
			scaleControl: true
		};
		map = new google.maps.Map(document.getElementById("AdminMap"), ohMapOptions);
		geocoder = new google.maps.Geocoder();
			
		// Add marker
		var coords = getCoords();
		var defaultPos;
		var lat = null;
		var lng = null;
		
		if( coords ){
			lat = coords[0];
			lng = coords[1];
		}
		defaultPos = ( lat && lng  )? new google.maps.LatLng(lat,lng) : new google.maps.LatLng(53.970224,-1.939087);

		place_marker = new google.maps.Marker({
			position: defaultPos,
			map: map,
			draggable:true
		});

		google.maps.event.addListener( place_marker, "drag", function(){
			updateCoords( ''+place_marker.getPosition() );
		});
			
		google.maps.event.addListener( map, "click", function(event){
			if( place_marker != null ){
				place_marker.setPosition(event.latLng);
				updateCoords( ''+event.latLng );
			}
		});
		
		// Setup form
		$('#AdminMapSearchSubmit').click( function(){
			var request = {
				address: $('#AdminMapSearch').val(),
				region: 'uk',
			}
			
			geocoder.geocode( request, function( results, status ){
				if( status == google.maps.GeocoderStatus.OK ){
					map.fitBounds( results[0].geometry.bounds );
					place_marker.setPosition(results[0].geometry.location);
					updateCoords( results[0].geometry.location.toString() );
				} else {
					alert("Sorry, we couldn't find that address. Please try a postcode.");
				}
			} );
			return false;
		});
		
		$('#AdminMapSearch').keypress( function( e ){
            code = ( e.keyCode ? e.keyCode : e.which );
            if (code == 13){
				e.preventDefault();
				$('#AdminMapSearchSubmit').click();
				return false;
			}
        });
		
		$('#ServicePostcode').change( function( e ){
            $('#AdminMapSearch').val( $('#ServicePostcode').val() );
			$('#AdminMapSearchSubmit').click();
        });
	}
})(jQuery);
