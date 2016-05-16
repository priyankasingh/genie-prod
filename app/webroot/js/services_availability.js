function addLocation() {
  // force conversion to numeric
  var varid = (1 * $('.js-locationWrapper').length) - 1;
  var div = $('.js-locationWrapper:last');
  var markup = '<div class="js-locationWrapper">'
              + $(div).html().replace(new RegExp("\\[" + varid + "\\]", "g"), "[" + (varid + 1) + "]");
  if (varid != 0) {
    markup += '</div>';
  } else {
    markup += '<button type="button" class="button button-link button-remove-location" onclick="removeLocation(this);">Remove</button></div>';
  }
  $(div).after(markup);
  clearLastLocationValue();
}
function clearLastLocationValue() {
  var div = $('.js-locationWrapper:last');
  $(div).find('[name$="[name]"]').attr("value", "");
}
function removeLocation(el) {
  console.log(el);
  $(el).closest('.js-locationWrapper').remove();
}

var postcode_text = 'Change your postcode';
var postcode = '';
var activeMarker = false;
var protectActiveMarker = false;

function setActiveMarker( marker ){
  if( marker && marker.setContent ){
    if( marker != activeMarker )
      marker.setContent( marker.getContent().replace('class=\'', 'class=\'active ') );
    marker.setOptions({zIndex:10});

    activeMarker = marker;
  }
}
function lockActiveMarker(){
  protectActiveMarker = true;
}
function unLockActiveMarker(){
  protectActiveMarker = false;
}
function clearActiveMarker(){
  if( activeMarker && activeMarker.setContent && !protectActiveMarker ){
    activeMarker.setContent( activeMarker.getContent().replace('class=\'active ', 'class=\'') );
    activeMarker.setOptions({zIndex:5});
    activeMarker = false;
  }
}

function loadServiceWithSlug(slug){
  //console.log(slug);
  $('#results-list').hide();
  $('.aside-holder').hide();
  $('.service').hide();
  //console.log($('.service-'+slug));
    $('.service-'+slug).show();
}
function showServiceList(){
  $('.service').hide();
  $('#results-list').show();
  $('.aside-holder').show();
}

function clearSearch(){
  $('#GroupSearch').val('').blur();
  $('#searchField').val('');
}

var Query = "";
function bindLinks(){

  // My EU-GENIE clicked services functionality
  var i = 0;
  /*var timeoutHandle;

  $("#filter-list a").click(function(e){
    var State = History.getState();
    var tempString = State.url;

    if(!$('#parent-helper').hasClass("serviceParent") || tempString.indexOf("my-map") == -1 ) return true;

    if($(this).parent('li').hasClass("all-results")){
      Query = "";
    }
    else {
      if($(this).parent('li').hasClass("active")){
        $(this).parent('li').removeClass("active");
        Query = Query.replace($( this ).data( 'cat') + ",","");
      }else{
        $(this).parent('li').addClass('active');
        Query = Query + $( this ).data( 'cat') + ",";
      }
    }

    if(i > 0){
      clearTimeout(timeoutHandle);
    }

    timeoutHandle = setTimeout(
    function() {
      url = "/services/my-map/" + Query;
      History.pushState(null, document.title, url);
    }, 1000);

    i++;
    clearSearch();
    e.stopImmediatePropagation();
    return false;
  });*/


  // Most link calls
  $('a.ajax, li.ajax a').not('a.ajax.more, a.ajax.favourite-link').click(function(evt) {
    if( $('.content-box').hasClass('favourites') ){
      return true;
    }

    evt.preventDefault();
    var url = $(this).attr('href');

    clearSearch(); // Clear search

    if($('#postcodeField').val() != postcode_text && postcode != ''){
      //Strip off get params (cos paginate adds them)
      var index = url.lastIndexOf("?");
      if(index > 0){
        url = url.substring(0,index);
      }
      //Add current postcode params (so category filters work with postcode)
      url += '?' + $('#postcode-form').serialize();
    }
    History.pushState(null, document.title, url);
  });
  var currentMarker;
  var oldCenter;
  // Individual service links
  $('a.ajax.more').click(function(evt){
    evt.preventDefault();
    var url = $(this).attr('href');
    var index = url.lastIndexOf("/");
    var slug = url.substring(index+1);
    var marker = $(this).parents('.results-list').data('marker');
    loadServiceWithSlug(slug);
    setActiveMarker(marker);
    lockActiveMarker();
    if (markersArray) {
      for (i in markersArray) {
        if(markersArray[i] != marker){
          markersArray[i].setVisible(false);
        }
      }

    }
    oldCenter = map.getCenter();
    map.setCenter(marker.position);
    map.setZoom(map.getZoom() + 2);
    currentMarker = marker;

  });

  // Associated pin behaviour
  $('.results-list').hover( function(){
    var marker = $(this).data('marker');
    setActiveMarker( marker );
  }, clearActiveMarker );

  clearActiveMarker();

  // Trigger street view
  $('.street-start').click(function(){
    var viewLocation = new google.maps.LatLng($(this).data('lat'),$(this).data('lng'));
    var panoramaOptions = {
      position: viewLocation,
      enableCloseButton: true,
      pov: {
        heading: 34,
        pitch: 10
      }
     };
  var panorama = new google.maps.StreetViewPanorama(document.getElementById("oh-pin-map"), panoramaOptions);
  map.setStreetView(panorama);

  return false;
  })


  // Favourite buttons
  $('a.ajax.favourite-link').click(function(evt){
    evt.preventDefault();
    var url = $(this).attr('href');
    var link = $(this);

    // Got lightbox? Use that.
    if( $('#favourites-action-wrapper').length ){
      var redirectInput = $('<input>', {type:"hidden", name:"data[User][redirect]", value:url} );
      $('#favourites-action form').append( redirectInput );
      $.fancybox({
        href: '#favourites-action',
        onCleanup: function(){
          $('#favourites-action-wrapper').html( $('#fancybox-content>div').html() )
        }
      });
    } else {
      // Make ajax call
       $.get( url, {isAjax:'1'}, function(data){  // Use of data object is to prevent IE using the "full" cached version of the page.
        if(data == 'true'){
          // Switch classes, text, link
          if( link.hasClass('favourite-exists') ){
            link.removeClass('favourite-exists').text('Favourite This');
            link.attr('href', link.attr('href').replace('delete','add') );
          } else {
            link.addClass('favourite-exists').text('Remove from favourites');
            link.attr('href', link.attr('href').replace('add','delete') );
          }
        } else {
          location.href = url;
        }
      }).fail(function() {
        alert("An error occurred. Please refresh the page or try again later");
      });
    }
  });

    // Video link
    $('.video-link').click(function(){
      $.fancybox({
        href : $(this).attr("href")
      });
      return false;
    });

    // Back button
  $('.back-link').click(function(evt){
    evt.preventDefault();
    showServiceList();
    unLockActiveMarker();
    clearActiveMarker();
    if (markersArray) {
      for (i in markersArray) {
        if(markersArray[i] != currentMarker){
          markersArray[i].setVisible(true);
        }
      }
    }
    map.setZoom(map.getZoom() - 2);
    map.setCenter(oldCenter);
  });
}

function selectParentId(id){
  $('#nav li.active').removeClass('active');
  $('#nav li.category-'+id).addClass('active');
}

$(function() {

  //If we're on a results list page, hide all services
  if($('#results-list').length){
    $('#results-box div.service').hide();
  }

  // Prepare
  var History = window.History; // Note: We are using a capital H instead of a lower h
  if ( !History.enabled ) {
       // History.js is disabled for this browser.
       // This is because we can optionally choose to support HTML4 browsers or not.
      return false;
  }


  // Ajax link function
  function serviceAjax($url){
    $('#loadingspinner').fadeIn();
    $.get($url, {isAjax:'1'}, function(data){  // Use of data object is to prevent IE using the "full" cached version of the page.
    $data = $(data);
      $('#category-filter').html( $data.filter('#category-filter').html() );
      $('#results-box').html( $data.filter('#results-box').html() );
    $('#category-description').html( $data.filter('#category-description').html() );

      selectParentId($data.filter('#parent-id').html());
      var postcode = $data.filter('#postcode').html();
    if(postcode == ''){
      $('#postcodeField').val(postcode_text);
    } else{
      $('#postcodeField').val(postcode);
    }
    showServiceList();
    bindLinks();
    ohpAddMarkers();
    $('#loadingspinner').fadeOut();

    }).fail(function() {
      alert("An error occurred. Please refresh the page or try again later");
      $('#loadingspinner').fadeOut();
    });
  }

    // Bind to StateChange Event
  History.Adapter.bind(window,'statechange',function() { // Note: We are using statechange instead of popstate
    var State = History.getState();
    serviceAjax(State.url);
  });

  /*$('.pager-holder a').click(function(){
    if($('#parent-helper').hasClass("serviceParent") || tempString.indexOf("my_plans") != -1 ){
      serviceAjax($(this).attr('href'));
    }
  })*/

  //Bind to ajax links
  bindLinks();

  function finishSubmit(){
    //Strip out query string  //var url = currURL. //window.location.href.substring(0, location.href.lastIndexOf("?"));
    var url = History.getState().url;
    var questionMarkLocation = url.lastIndexOf("?");

    if( questionMarkLocation && questionMarkLocation>0 )
      url = url.substring( 0, questionMarkLocation );

    //NB: Pagination is stripped out to stop errors occuring when for eg. you're on page 2,
    //change the postcode and then there is only one page of results.
    var pagination_location = url.lastIndexOf("/page:");
    if(pagination_location > -1){
      url = url.substring(0,pagination_location);
    }
    //Push state
    History.pushState(null, document.title, url + '?' + $('#postcode-form').serialize());
  }

  // Bind to form
  $('#postcode-form').submit(function(evt){
    evt.preventDefault();

      postcode = $('#postcodeField').val();

    if( postcode != postcode_text && postcode != ''){

      //If postcode is the same as before and we already have lat and lng, no need to geocode.
      if(postcode == $('#oldPostcodeField').val() && $('#latField').val()!='' && $('#lngField').val()!=''){
        finishSubmit();
      }else{
        //Geocode
        var request = {
        address: postcode,
        region: 'uk',
      }
      geocoder.geocode( request, function( results, status ){
        if( status == google.maps.GeocoderStatus.OK ){

          $('#latField').val(results[0].geometry.location.lat());
          $('#lngField').val(results[0].geometry.location.lng());
          $('#oldPostcodeField').val(postcode);

          finishSubmit();
        }else if(status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
          alert("You are over your request limit. Please wait a few minutes and try again.");
        }else{
          alert("Sorry, we couldn't find that postcode. '"+postcode+"'");
        }
      });
      }
    }
  });

  // Bind to group search form
  $('.header-box .search-form').submit(function(evt){
    evt.preventDefault();
    $('#searchField').val( $('#GroupSearch').val() ); // Send to the postcode form instead
    finishSubmit();
    return false;
  });


  // Submit form when radio buttons pressed
  // NB: Had to hack main.js line 1234 for this to work as jcf is a selfish bastard. Sad face.
  $('#postcode-form input[type="radio"]').click(function() {
    if($('#postcodeField').val() != postcode_text){
      $('#postcode-form').submit();
    }
  });

});
