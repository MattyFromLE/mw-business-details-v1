/*--------------------------------------------------------------
Maps
--------------------------------------------------------------*/


var addressName = mw_map_vars.addressName,
	long = mw_map_vars.long,
	lat = mw_map_vars.lat,
	googleMapsLink = mw_map_vars.googleMapsLink,
	mapStyle = mw_map_vars.mapStyle,
	mapMarker = mw_map_vars.mapMarker,
	pinOrCustom = mw_map_vars.pin,
	pinImage = mw_map_vars.pinImage,
	radius = mw_map_vars.radiusDistance,
	mile = 1609.344;
	pluginUrl = mw_map_vars.pluginUrl,
	autoAddress = mw_map_vars.autoAddress;


// single map function, only called if google map address is equal to a string
function singleMap() {
	
	var geocoder = new google.maps.Geocoder();

	geocoder.geocode( { 'address': autoAddress }, function(results, status) {
		  
		if ( status == 'OK' ) {

			var mwLat = results[0].geometry.location.lat(),
				mwLong = results[0].geometry.location.lng();
				
		}

		var myLatlng = new google.maps.LatLng( mwLat, mwLong ); // Add the coordinate

		// center map when info window is visible
		google.maps.Map.prototype.setCenterWithOffset= function(latlng, offsetX, offsetY) {
		
			var map = this;
			
			var ov = new google.maps.OverlayView();
			
			ov.onAdd = function() {
			   
			    var proj = this.getProjection();
			    var aPoint = proj.fromLatLngToContainerPixel(latlng);
			    aPoint.x = aPoint.x+offsetX;
			    aPoint.y = aPoint.y+offsetY;
			    map.setCenter(proj.fromContainerPixelToLatLng(aPoint));
			
			}; 
			
			ov.draw = function() {}; 
			
			ov.setMap(this); 
		
		};
		
		currentLatLong = myLatlng;
		
			var mapOptions = {

				zoom: parseInt(mw_map_vars.zoom), // The initial zoom level when your map loads (0-20)
				minZoom: 0, // Minimum zoom level allowed (0-20)
				maxZoom: 20, // Maximum soom level allowed (0-20)
				zoomControl:true, // Set to true if using zoomControlOptions below, or false to remove all zoom controls.
				zoomControlOptions: {
					style:google.maps.ZoomControlStyle.SMALL, // Change to SMALL to force just the + and - buttons.
					position: google.maps.ControlPosition.RIGHT_TOP
				},
				center: myLatlng, // Centre the Map to our coordinates variable
				mapTypeId: google.maps.MapTypeId.ROADMAP, // Set the type of Map
				scrollwheel: false,
				panControl: false, // Set to false to disable
				panControlOptions: {
					position: google.maps.ControlPosition.RIGHT_TOP
				},
				mapTypeControl:false, // Disable Map/Satellite switch
				scaleControl: false, // Set to false to hide scale
				streetViewControl:false, // Set to disable to hide street view
				overviewMapControl:false, // Set to false to remove overview control
				rotateControl:false // Set to false to disable rotate control
			
			};

		var map = new google.maps.Map(document.getElementById('map-wrapper'), mapOptions); 
		
		google.maps.event.addDomListener(window, 'resize', function() { map.setCenter(currentLatLong); });

		if ( pinOrCustom == 'custom' ) { 

			var image = new google.maps.MarkerImage( ""+ pinImage +"", null, null, null, new google.maps.Size( parseInt(mw_map_vars.markerWidth), parseInt(mw_map_vars.markerHeight) )); // Create a variable for our marker image.

		} else {

			var image = new google.maps.MarkerImage( pluginUrl +"/image/map-marker.png", null, null, null, new google.maps.Size(46,71)); // Create a variable for our marker image.

		};
			
		var marker = new google.maps.Marker({ // Set the marker
			position: myLatlng, // Position marker to coordinates
			icon:image, //use our image as the marker
			map: map, // assign the market to our map variable
			title: 'Click to view in Google Maps' // Marker ALT Text
		});	

		if ( mapMarker == "radius" ) { 

			// hide Icon
			marker.setVisible(false);	

			radiusCalc = mile * radius;
			console.log(radiusCalc);

			// set radius
			var circle = new google.maps.Circle({
		  		map: map,
		  		radius: radiusCalc,
		  		fillColor: '#a54fee',
		        strokeColor: '#CD0000',
		   		strokeOpacity: 0,		
			});

			circle.bindTo('center', marker, 'position');	

		} 

		var infowindow = new google.maps.InfoWindow({ // Create a new InfoWindow
				
			content: '<h3>'+ addressName +'</h3><p class="map-address">'+ autoAddress +'</p><p><a target="_blank" href="' + googleMapsLink + '"> View on Google Maps</a></p>' // HTML contents of the InfoWindow
		
		});

		if ( mw_map_vars.showInfoWindow == "show" ) { 

			infowindow.open(map,marker); // Open our InfoWindow

			google.maps.event.addListener(marker, 'click', function() { // Add a Click Listener to our marker
					
				infowindow.open(map,marker); // Open our InfoWindow

			});

			map.setCenterWithOffset(currentLatLong, 0, -110);
				
				google.maps.event.addDomListener(window, 'resize', function() { 
				
					map.setCenterWithOffset(currentLatLong, 0, -110);
					
			});

		} else { 

			google.maps.event.addListener(marker, 'click', function() { // Add a Click Listener to our marker
					
				infowindow.open(map,marker); // Open our InfoWindow

			});

		}

		if ( mapStyle === "colourful" ) { 

			var styles = [{"featureType":"road","elementType":"labels","stylers":[{"visibility":"simplified"},{"lightness":20}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#a1cdfc"},{"saturation":30},{"lightness":49}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"hue":"#f49935"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"hue":"#fad959"}]}];

		} else if ( mapStyle === "grey" ) {


			var styles = [{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}];

		} else if ( mapStyle === "pale" ) {

			var styles = [{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"landscape","stylers":[{"color":"#f2e5d4"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"administrative","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"road"},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{},{"featureType":"road","stylers":[{"lightness":20}]}];


		} else if ( mapStyle === "custom" ) {

			var styles = jQuery.parseJSON(mw_map_vars.customMap);

		}

		map.setOptions({ styles: styles });

	});

}

function staticMap() {

	var myLatlng = new google.maps.LatLng( lat, long ); // Add the coordinate

	// center map when info window is visible
	google.maps.Map.prototype.setCenterWithOffset= function(latlng, offsetX, offsetY) {
	
		var map = this;
		
		var ov = new google.maps.OverlayView();
		
		ov.onAdd = function() {
		   
		    var proj = this.getProjection();
		    var aPoint = proj.fromLatLngToContainerPixel(latlng);
		    aPoint.x = aPoint.x+offsetX;
		    aPoint.y = aPoint.y+offsetY;
		    map.setCenter(proj.fromContainerPixelToLatLng(aPoint));
		
		}; 
		
		ov.draw = function() {}; 
		
		ov.setMap(this); 
	
	};
	
	currentLatLong = myLatlng;
	
		var mapOptions = {

			zoom: parseInt(mw_map_vars.zoom), // The initial zoom level when your map loads (0-20)
			minZoom: 0, // Minimum zoom level allowed (0-20)
			maxZoom: 20, // Maximum soom level allowed (0-20)
			zoomControl:true, // Set to true if using zoomControlOptions below, or false to remove all zoom controls.
			zoomControlOptions: {
				style:google.maps.ZoomControlStyle.SMALL, // Change to SMALL to force just the + and - buttons.
				position: google.maps.ControlPosition.RIGHT_TOP
			},
			center: myLatlng, // Centre the Map to our coordinates variable
			mapTypeId: google.maps.MapTypeId.ROADMAP, // Set the type of Map
			scrollwheel: false,
			panControl: false, // Set to false to disable
			panControlOptions: {
				position: google.maps.ControlPosition.RIGHT_TOP
			},
			mapTypeControl:false, // Disable Map/Satellite switch
			scaleControl: false, // Set to false to hide scale
			streetViewControl:false, // Set to disable to hide street view
			overviewMapControl:false, // Set to false to remove overview control
			rotateControl:false // Set to false to disable rotate control
		
		};

	var map = new google.maps.Map(document.getElementById('map-wrapper'), mapOptions); 
	
	google.maps.event.addDomListener(window, 'resize', function() { map.setCenter(currentLatLong); });

	if ( pinOrCustom == 'custom' ) { 

		var image = new google.maps.MarkerImage( ""+ pinImage +"", null, null, null, new google.maps.Size( parseInt(mw_map_vars.markerWidth), parseInt(mw_map_vars.markerHeight) )); // Create a variable for our marker image.

	} else {

		var image = new google.maps.MarkerImage( pluginUrl +"/image/map-marker.png", null, null, null, new google.maps.Size(46,71)); // Create a variable for our marker image.

	};
		
	var marker = new google.maps.Marker({ // Set the marker
		position: myLatlng, // Position marker to coordinates
		icon:image, //use our image as the marker
		map: map, // assign the market to our map variable
		title: 'Click to view in Google Maps' // Marker ALT Text
	});	

	if ( mapMarker == "radius" ) { 

		// hide Icon
		marker.setVisible(false);	

		radiusCalc = mile * radius;
		console.log(radiusCalc);

		// set radius
		var circle = new google.maps.Circle({
	  		map: map,
	  		radius: radiusCalc,
	  		fillColor: '#a54fee',
	        strokeColor: '#CD0000',
	   		strokeOpacity: 0,		
		});

		circle.bindTo('center', marker, 'position');	

	} 

	var infowindow = new google.maps.InfoWindow({ // Create a new InfoWindow
			
		content: '<h3>'+ addressName +'</h3><p class="map-address">3 Avon Valley Business Park, Chapel Way, St Annes,  Avon Bristol BS4 4EU</p><p><a target="_blank" href="' + googleMapsLink + '"> View on Google Maps</a></p>' // HTML contents of the InfoWindow
	
	});

	if ( mw_map_vars.showInfoWindow == "show" ) { 

		infowindow.open(map,marker); // Open our InfoWindow

		google.maps.event.addListener(marker, 'click', function() { // Add a Click Listener to our marker
				
			infowindow.open(map,marker); // Open our InfoWindow

		});

		map.setCenterWithOffset(currentLatLong, 0, -110);
			
			google.maps.event.addDomListener(window, 'resize', function() { 
			
				map.setCenterWithOffset(currentLatLong, 0, -110);
				
		});

	} else { 

		google.maps.event.addListener(marker, 'click', function() { // Add a Click Listener to our marker
				
			infowindow.open(map,marker); // Open our InfoWindow

		});

	}

	if ( mapStyle === "colourful" ) { 

		var styles = [{"featureType":"road","elementType":"labels","stylers":[{"visibility":"simplified"},{"lightness":20}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#a1cdfc"},{"saturation":30},{"lightness":49}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"hue":"#f49935"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"hue":"#fad959"}]}];

	} else if ( mapStyle === "grey" ) {


		var styles = [{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}];

	} else if ( mapStyle === "pale" ) {

		var styles = [{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"landscape","stylers":[{"color":"#f2e5d4"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"administrative","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"road"},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{},{"featureType":"road","stylers":[{"lightness":20}]}];


	} else if ( mapStyle === "custom" ) {

		var styles = jQuery.parseJSON(mw_map_vars.customMap);

	}

	map.setOptions({ styles: styles });

}

function multiMap() {

	var geocoder = new google.maps.Geocoder();

	// create empty array for addresses
	var addresses = [];

	// get addresses into an array
	for ( var i = 0; i < autoAddress.length; i++ ) {

		addresses.push({

			'address': autoAddress[i]['address'] 

		});

	};

	var mapOptions = {

		zoom: parseInt(mw_map_vars.zoom), // The initial zoom level when your map loads (0-20)
		minZoom: 0, // Minimum zoom level allowed (0-20)
		maxZoom: 20, // Maximum soom level allowed (0-20)
		zoomControl:true, // Set to true if using zoomControlOptions below, or false to remove all zoom controls.
		zoomControlOptions: {
			style:google.maps.ZoomControlStyle.SMALL, // Change to SMALL to force just the + and - buttons.
			position: google.maps.ControlPosition.RIGHT_TOP
		},
		mapTypeId: google.maps.MapTypeId.ROADMAP, // Set the type of Map
		scrollwheel: false,
		panControl: false, // Set to false to disable
		panControlOptions: {
			position: google.maps.ControlPosition.RIGHT_TOP
		},
		mapTypeControl:false, // Disable Map/Satellite switch
		scaleControl: false, // Set to false to hide scale
		streetViewControl:false, // Set to disable to hide street view
		overviewMapControl:false, // Set to false to remove overview control
		rotateControl:false // Set to false to disable rotate control
	
	};

	var map = new google.maps.Map(document.getElementById('map-wrapper'), mapOptions); 

	for (var i = 0; i < addresses.length; i++) {

		geocoder.geocode( { 'address': addresses[i]['address']}, function(results, status) {

			if ( status == google.maps.GeocoderStatus.OK ) {

				var marker = new google.maps.Marker({

					map: map,
					position: results[0].geometry.location,
					icon:image, //use our image as the marker

				});

				markerBounds.extend(results[0].geometry.location);

				map.fitBounds(markerBounds);

				// alert( status );
			
			} else {

				alert( "Geocode was not successful for the following reason: " + status );

			}

		});

	}

	var markerBounds = new google.maps.LatLngBounds();

	if ( pinOrCustom == 'custom' ) { 

		var image = new google.maps.MarkerImage( ""+ pinImage +"", null, null, null, new google.maps.Size( parseInt(mw_map_vars.markerWidth), parseInt(mw_map_vars.markerHeight) )); // Create a variable for our marker image.

	} else {

		var image = new google.maps.MarkerImage( pluginUrl +"/image/map-marker.png", null, null, null, new google.maps.Size(46,71)); // Create a variable for our marker image.

	};

	if ( mapStyle === "colourful" ) { 

		var styles = [{"featureType":"road","elementType":"labels","stylers":[{"visibility":"simplified"},{"lightness":20}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#a1cdfc"},{"saturation":30},{"lightness":49}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"hue":"#f49935"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"hue":"#fad959"}]}];

	} else if ( mapStyle === "grey" ) {


		var styles = [{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}];

	} else if ( mapStyle === "pale" ) {

		var styles = [{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"landscape","stylers":[{"color":"#f2e5d4"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"administrative","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"road"},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{},{"featureType":"road","stylers":[{"lightness":20}]}];


	} else if ( mapStyle === "custom" ) {

		var styles = jQuery.parseJSON(mw_map_vars.customMap);

	}

	map.setOptions({ styles: styles });

}

/* ==================================================
Google Map singleMap
load map once you've scrolled to it
================================================================= */

jQuery(document).ready( function($){

	function mwMapLoad() {

		var mapWrapper = $('#map-wrapper'),
			windowHeight = $(window).height(),
			mapWrapperPosition = mapWrapper.offset().top,
			mapWrapperScroll = $(window).scrollTop(),
			mapWrapperDistance = ( mapWrapperPosition - windowHeight );
	
		if ( mapWrapper.length > 0 ) {
		   
		    if ( parseInt(mapWrapperScroll) > parseInt(mapWrapperDistance) ) { 

				if ( mapWrapper.children().length < 1 ) {

					if ( long || lat ) {

							// is static
							staticMap();

					} else { 

			    		if( typeof autoAddress != 'string' ) {

							// is array
							multiMap();

			    		} else {

			    			// is string
				    		singleMap();

			    		}
					
					}		

		  		} 

		    }

		}		
	
	}

	$(window).scroll(function(){
	
		mwMapLoad();

	});

	mwMapLoad();


});