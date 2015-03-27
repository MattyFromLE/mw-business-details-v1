
jQuery(document).ready( function( $ ){
	$("a[id$='-phone']").each(function () {

		$(this).click( function(){ 

			var deets = $(this).attr("id");

			ga('send', 'event', { eventCategory: 'event', eventAction: 'link', eventLabel: deets });

			var showTrackingAlert = mw_tracking_vars.showTrackingAlert;

			// show an alert so developer can test if tracking is working
			if ( showTrackingAlert == 'show' ) {

				alert('Currently Tracking: ' + deets );

				/* 

				ONLY NEEDED FOR DEVELOPMENT PURPOSES
				console.log( "ga('send', 'event', { eventCategory: 'event', eventAction: 'link', eventLabel:" + deets + " });" );

				*/

			}

		});

	});
});
