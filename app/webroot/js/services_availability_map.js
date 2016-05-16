var map;
var markersArray = [];

// Deletes all markers in the array by removing references to them
function deleteMarkers() {
  if (markersArray) {
    for (i in markersArray) {
      markersArray[i].setMap(null);
    }
    markersArray.length = 0;
  }
}

function ohpAddMarkers(){

  // Delete markers
  deleteMarkers();

  // Add new markers
  var minLat=0;
  var minLng=0;
  var maxLat=0;
  var maxLng=0;
  var count = 0;
  $('.service').each(function(i){
    var ohPin = new Object();
    ohPin.location = $(this).data('location');
    ohPin.content = $(this).data('content');
    ohPin.slug = $(this).data('slug');
    ohPin.id = $(this).data('id');

    ohPin.lat = $(this).data('lat');
    ohPin.lng = $(this).data('lng');
    if(ohPin.lat == '' || ohPin.lng == ''){
      return true;
    }
    ohPin.location = new google.maps.LatLng(parseFloat(ohPin.lat),parseFloat(ohPin.lng));

    ohPin.marker = new RichMarker({
      position: ohPin.location,
      title: ohPin.title,
      map: map,
      content: ohPin.content,
      shadow:false,
      zIndex:5
    });

    markersArray.push(ohPin.marker);
    google.maps.event.addListener( ohPin.marker, 'click', function() {
      //loadServiceWithSlug(ohPin.slug);
      $('#service-' + ohPin.id).find('a.more').click();
    });

    // Associate with service
    $('#service-' + ohPin.id).data( 'marker', ohPin.marker );

    // Bring to front and hover the service, if exists
    google.maps.event.addListener(ohPin.marker, "mouseover", function() {
      $('#service-' + ohPin.id).addClass('active');
      this.setOptions({zIndex:10});
    });
    google.maps.event.addListener(ohPin.marker, "mouseout", function() {
      this.setOptions({zIndex:5});
      $('#service-' + ohPin.id).removeClass('active');
    });


  //  ohPin.marker.ohPin_index = i;
  /*
    if( ohPin.infowindow ){
      google.maps.event.addListener( ohPin.marker, 'click', function() {
        ohpShowMapItem( this.ohPin_index );
      });
      if( ohPins.length == 1 ) ohpShowMapItem( i );
    }
*/
    if( minLat==0 || ohPin.location.lat() < minLat ) minLat = ohPin.location.lat();
    if( minLng==0 || ohPin.location.lng() < minLng ) minLng = ohPin.location.lng();
    if( maxLat==0 || ohPin.location.lat() > maxLat ) maxLat = ohPin.location.lat();
    if( maxLng==0 || ohPin.location.lng() > maxLng ) maxLng = ohPin.location.lng();
    count++;
  });

  // Add "You are here"
  var hereLat = $('#latField').val();
  var hereLng = $('#lngField').val();

  if( hereLat && hereLng ){
    var hereLocation = new google.maps.LatLng(parseFloat(hereLat),parseFloat(hereLng));
    var youAreHere = new RichMarker({
      position: hereLocation,
      map: map,
      content: "<div class='oh-pin you-are-here'>You are here</div>",
      shadow:false
    });

    markersArray.push( youAreHere );
    if( minLat==0 || hereLocation.lat() < minLat ) minLat = hereLocation.lat();
    if( minLng==0 || hereLocation.lng() < minLng ) minLng = hereLocation.lng();
    if( maxLat==0 || hereLocation.lat() > maxLat ) maxLat = hereLocation.lat();
    if( maxLng==0 || hereLocation.lng() > maxLng ) maxLng = hereLocation.lng();
    count++;
  }

  // Auto adjust bounds
  if( count > 0 ){
    //Set minimum bounds
    var latMile = 0.014461316;
    var lngMile = 0.023969319;
    var minLatDiff = latMile/4;
    var minLngDiff = lngMile/4;
    if(maxLat - minLat < minLatDiff){
      maxLat += minLatDiff/2;
      minLat -= minLatDiff/2
    }
    if(maxLng - minLng < minLngDiff){
      maxLng += minLngDiff/2;
      minLng -= minLngDiff/2;
    }
    //Fit to bounds
    botLeft = new google.maps.LatLng( minLat, minLng );
    topRight = new google.maps.LatLng( maxLat, maxLng );

    bounds = new google.maps.LatLngBounds(botLeft, topRight);
    map.fitBounds(bounds);
  }

}


( function( $ ){
  $( function(){
    // Sanity
    if( !document.getElementById("oh-pin-map") ) return;

    // Setup map
    var latlng = new google.maps.LatLng(53.477422,-2.241211);
    var ohMapOptions = {
      zoom: 10,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      navigationControl: true,
      mapTypeControl: false,
      scaleControl: true,
      streetViewControl: true
    };

    map = new google.maps.Map(document.getElementById("oh-pin-map"), ohMapOptions);
    geocoder = new google.maps.Geocoder();

    // Add markers
    ohpAddMarkers();

  });
} )( jQuery );
