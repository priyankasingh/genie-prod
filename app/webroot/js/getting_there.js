
$(function(){
	$('#timeH, #timeM').change(function(){
		$('#mainJourneyRequestDetailstime').val( $('#timeH').val() + ':' + $('#timeM').val() + ':00' );
	});
	
	$('#dateH, #dateM').change(function(){
		var ym = $('#dateM').val().split('-');
		var y = ym[0];
		var m = ym[1];
		var d = $('#dateH').val();
		
		$('#mainJourneyRequestDetailsdate').val( d + '/' + m + '/' + y );
	});
});
