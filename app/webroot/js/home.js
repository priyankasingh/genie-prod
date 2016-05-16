$(document).ready(function() {
	var removeNetworkMemberQuestions = function(){
		$('.js-networkMemberQuestion').remove();
	}

	var addQuestionsForNetworkMembers = function () {
		removeNetworkMemberQuestions();

		var networkMembers = $('[name^="data[NetworkMember]"][name*="[name]"][value!=""][value!="Name"]:not([name^="data[NetworkMember][-1]"])');
		var questionTemplates = $('.question-categories-network-template');

		for (var i = 0; i < networkMembers.length; i++) {
			var networkMemberIndex;

			(function(index){
				// console.log(index);
				var networkMemberIndex,
						networkMemberName;

				networkMemberIndex = $(networkMembers[index]).attr('name').match('[0-9]+');
				networkMemberName = $(networkMembers[index]).val();
				// console.log(networkMemberName);

				$('.question-categories-network-template').each(function(){

					var el,
							parent,
							elementToAddMemberNameTo,
							elementsToChangeNameOf,
							elementsToChangeIdOf,
							elementsToChangeForOf;
					el = $(this).clone();
					parent = el.parent();

					el = el.removeClass('question-categories-network-template');
					el = el.addClass('member-network-question-categories');
					el = el.addClass('js-networkMemberQuestion');

					elementToAddMemberNameTo = el.find('.networkMemberName');
					elementToAddMemberNameTo.html(networkMemberName);

					elementsToChangeNameOf = el.find('[name*="NetworkMemberIndex"]');
					elementsToChangeNameOf.each(function(){
						var elName;
						elName = $(this).attr('name');
						elName = elName.replace('NetworkMemberIndex', index);
						$(this).attr('name', elName);
					});

					elementsToChangeIdOf = el.find('[id*="NetworkMemberIndex"]');
					elementsToChangeIdOf.each(function(){
						var elId;
						elId = $(this).attr('id');
						elId = elId.replace('NetworkMemberIndex', index);
						$(this).attr('id', elId);
					});

					elementsToChangeForOf = el.find('[for*="NetworkMemberIndex"]');
					elementsToChangeForOf.each(function(){
						var elFor;
						elFor = $(this).attr('for');
						elFor = elFor.replace('NetworkMemberIndex', index);
						$(this).attr('for', elFor);
					});

					el.insertAfter($(this));
				});
			})(i);
		};
	}

	var removeNetworkMemberQuestions = function(){
		$('.js-networkMemberQuestion').remove();
	}

	// POSTCODE SEARCH
	$('#ResponsePostcode').change(function(){
		$('#ResponseLat').val('');
		$('#ResponseLng').val('');
	});

	$('#postcode-form, #ResponseAddForm').submit(function(evt){
		var latField = $(this).find('input[name=latitude], input[name="data[Response][lat]"]');
		var lngField = $(this).find('input[name=longitude], input[name="data[Response][lng]"]');
		var postcodeField = $(this).find('input[name=postcode], input[name="data[Response][postcode]"]');
		var processingForm = $(this);


		if( !latField.val() || !lngField.val() || latField.val()==0 || lngField.val() == 0 ){
			evt.preventDefault();

			//Geocode
			var geocoder = new google.maps.Geocoder();

			var request = {
				address: postcodeField.val(),
				region: 'uk',
			}
			geocoder.geocode( request, function( results, status ){
				if( status == google.maps.GeocoderStatus.OK ){
					latField.val(results[0].geometry.location.lat());
					lngField.val(results[0].geometry.location.lng());

					if( latField.val() && lngField.val() && latField.val() != 0 && lngField.val() != 0 ){
						processingForm.submit();
					}
				}else if(status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
					alert("You are over your request limit. Please wait a few minutes and try again.");
				}else{
					alert("Sorry, we couldn't find that postcode. Please check the formatting.");
				}
			});


			return false;
		}
	});

	// QUESTION BOXES
	$('.question-box').hide();
	if( window.location.hash ){
		var hashBox = window.location.hash.replace('#','');
		if( hashBox && $('#' + hashBox).length ){
			$('#default-box').hide();
			$('.main-holder').hide();
			$('#' + hashBox).show();
		}
	}
	$('.question-button').click(function(){
		var newBox = $(this).attr('href').split('#')[1];
		$('.question-box').hide();
		$('#'+newBox ).show();
		$('#default-box').hide();
		$('.main-holder').hide();
	});

	$('.close-button').click(function(){
		$('#default-box').show();
		$('.main-holder').show();
		$('.question-box').hide();
		return false;
	});

	// SECONDARY QUESTIONS
	$(".question-categories").each(function(){
		if( $(this).parents('.statement').find('.question-choice input[type="radio"]:checked').val() ==0 )
			$(this).hide();
	});

	$('.question-choice input[type="radio"]').click(function(){
		if ($(this).is(':checked') && ( $(this).val() == '2' || $(this).val() == '1' ) ){
			$(this).parents('.statement').find(".question-categories").fadeIn();
			var checkboxes = $(this).parents('.statement').find('.question-categories input[type="checkbox"]');
			if( checkboxes.length == 1 ) checkboxes.prop('checked', true);
		} else if($(this).is(':checked') && $(this).val() == '0'){
			$(this).parents('.statement').find(".question-categories").fadeOut();
		}
	});

	// QUESTIONNAIRE PAGINATION
	function switchQuestionnairePage( forwards ){
		var pages = $('#questionnaire .questionnaire-page');
		var qIndex = $('#questionnaire .questionnaire-page.active').index()-1;
		var count = pages.length - 1;

		if( forwards && qIndex < count ){ // Next
			qIndex++;
			if( qIndex == count ) $('.question-box .next-button').hide();
			$('.question-box .prev-button').show();
		} else if( !forwards && qIndex > 0 ){ // Prev
			qIndex--;

			if( qIndex == 0 ) $('.question-box .prev-button').hide();
			$('.question-box .next-button').show();
		}
		pages.removeClass('active').hide(); // Close old panel
		pages.eq(qIndex).addClass('active').show(); // Open new panel
		prepareQuestionnairePage( pages.eq(qIndex).attr('id') ); // Set-up panel

		if ($('.question-page-1').hasClass('active')) {
			addQuestionsForNetworkMembers();
		};

	}
	$('#questionnaire .questionnaire-page:first').addClass('active');
	$('#questionnaire .questionnaire-page').not(':first').hide();
	$('.question-box .prev-button').hide();
	$('.question-box .next-button, .question-box .prev-button').click( function(){
		switchQuestionnairePage( $(this).hasClass( 'next-button' ) );
		return false;
	});

	function prepareQuestionnairePage( id ){
		if( id == 'network-members' ) setupMyNetworkPage();
	}

	// QUESTIONNAIRE - MY NETWORK
	function ohReplaceAttrNums( elem, attrName, newVal ){
		elem.attr(attrName, elem.attr(attrName).replace( /[0-9]+/, newVal ));
		return elem;
	}

	function makePinDraggable( pin ){
		pin.draggable({
			stop: function( event, ui ) {
				ui.helper.find('input[name$="[diagram_x]"]').val( ui.position.left );
				ui.helper.find('input[name$="[diagram_y]"]').val( ui.position.top );
			},
			revert: function(destination) {
				if( !$(this).find('input[name$="[name]"]').val() || $(this).find('input[name$="[name]"]').val() == 'Name' ||
						!$(this).find('input[name$="[network_category_id]"]').val() )
					return true;

				return !destination; // Revert back if we're not over the diagram
			}
		}).css('position', 'absolute');
	}

	function createNewPin( currentPin ){
		// Prepare new pin
		var newId = parseInt( currentPin.attr('id').replace('drag_', '') ) + 1;
		var newPin = currentPin.clone( false );
		currentPin.addClass('network-pin-placed');

		// Attribures and fields
		ohReplaceAttrNums( newPin, 'id', newId);
		newPin.find('.network-pin-name').each(function(){
			$(this).empty();
		});
		newPin.find('input').each(function(){
			ohReplaceAttrNums( $(this), 'id', newId);
			ohReplaceAttrNums( $(this), 'name', newId);
		});
		newPin.find('input[name$="[dummy_pin]"]').val('1');
		currentPin.find('input[name$="[dummy_pin]"]').val('0');

		newPin.removeAttr('style');
		newPin.appendTo('.network-pin-area');

		makePinDraggable( newPin );

		// Clear fields
		$('#NetworkMember-1Name').val("");
		newPin.find('input[name$="[name]"]').val('');
	}

	function getCurrentPin(){
		return $('.network-pin-area .network-pin:last');
	}

	// Name in the middle of the circle
	$('#ResponseName').bind("change propertychange keyup input paste blur", function(event){
		$('.network-circle-name').text($(this).val());

		if( !$(this).val()|| $(this).val() == "Name" ) {
			$('.network-circle-name').text("You");
		} else{
			$('.network-circle-name').text($(this).val());
		}
	});

	// Initialize the first draggables
	$('.network-pin').each(function() {
		makePinDraggable( $(this) );
	});

	// Initialize the diagram droppable
	$('#network-circle').droppable({
		accept:'.network-pin',
		drop: function( event, ui ) {
			if( !ui.draggable.hasClass('network-pin-placed') ){
				createNewPin( ui.draggable );
			}
		}
	});

	// Delete function droppable
	$("#network-trash-can").droppable({
		accept:'.network-pin-placed',
		hoverClass: "network-trash-hover",
		drop: function(event, ui){
			$(ui.draggable).remove();
		}
	});

	// Change name
	$('#NetworkMember-1Name').bind("change propertychange keyup input paste", function(event){
		getCurrentPin().find('.network-pin-name').text($(this).val());
	});

	// Change role
	$("#other-name").bind("change propertychange keyup input paste", function(event){
		getCurrentPin().find('.network-pin-role').text($(this).val());
	});
	$(".network-child-category select").change(function(){
		if($(this).find("option[value='" + $(this).val() + "']").text() == "Other"){
			$("#other-name").fadeIn();
			$("#other-name").change();
		} else {
			$("#other-name").fadeOut();
			getCurrentPin().find('.network-pin-role').text($(this).find("option:selected").text());
		}
	});

	// Change frequency
	$("#NetworkMember-1Frequency").change(function(){
		var id = $(this).find("option:selected").attr("value");
		getCurrentPin().removeClass().addClass('network-pin network-pin-'+id);
	});

	// Dropdowns appear
	$("#NetworkMember-1NetworkCategoryParentId").change(function(){
		$(".network-child-category, #other-name").hide();

		if($("#NetworkMember-1NetworkCategoryParentId option[value='" + $(this).val() + "']").text() == "Other"){
			$("#other-name").fadeIn().change();
		}
		else{
			$("#other-name").fadeOut();
			$(".dropdown_category_" + $(this).val()).closest(".network-child-category").fadeIn().find('select').change();
		}

	});

	// Set up hidden fields
	$('#add-pin-form').find('input, select').bind("change propertychange keyup input paste", function(event){
		$('#add-pin-form').find('input, select:visible').each( function(){
			var nameSuffix = $(this).attr('name').split('][');
			nameSuffix = nameSuffix[ nameSuffix.length - 1 ].replace(']','');
			getCurrentPin().find('input[name$="['+nameSuffix+']"]').val( $(this).val() );
		});
	});

	// Initial values setup
	function setupMyNetworkPage(){
		$('#ResponseName').change();

		$(".network-child-category, #other-name").hide();
		$("#NetworkMember-1NetworkCategoryParentId").change();

		$('#add-pin-form').find('input:visible, select:visible').change();
	}
});
