jQuery(document).ready(function( $ ){

	// custom uploader
    var custom_uploader;
 
    $('.uploadButton').click(function(e) {
 
        e.preventDefault();

        $(this).addClass("uploadActive");
 
		//stop custom uploader from instantiating more than twice...
		//If the uploader object has already been created, reopen the dialog
        /*if (custom_uploader) {
            custom_uploader.open();
            return;
        }*/
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        var myinput = $(this).attr('id');
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function( ) {

            attachment = custom_uploader.state().get('selection').first().toJSON();

            if ( $('.uploadButton').hasClass("uploadActive") ) {

                $(".uploadActive").parent().find('input.imageUrl').val(attachment.url);
                $(".uploadActive").parent().find('input.imageSrc').val(attachment.id);

                // console.log(attachment.sizes[ { 'medium': 'height' } ]);

            }

        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });

    // ======================================================================
    // TAB FUNCTIONALITY
    // switches containers in the backend to go between sections
    // ======================================================================

    $("h2 a:first").addClass("nav-tab-active");
    $(".section:first").delay(250).fadeIn(250);

    $("h2 a").click(function(){

    	var tabMatch = $(this).attr("class").split(' ')[0];

    	$(this).addClass("nav-tab-active").siblings().removeClass("nav-tab-active");
    	$("#"+ tabMatch).fadeIn(250).siblings().fadeOut(0);

    });

    // ======================================================================
    // GOOGLE MAPS
    // checks the select for two variables, shows/hides corresponding format
    // ======================================================================

        function pinOrRadius(){

            var pinOrRadius = $("select#mapMarker").find(":selected").val();

            if ( pinOrRadius === "pin" ) {

                $(".pin").fadeIn(250);
                $(".radius").fadeOut(0);

            } else if ( pinOrRadius === "radius" ) {

                $(".pin").fadeOut(250);
                $(".radius").fadeIn(0);

            }

        }

        // ======================================================================
        // MAP STYLE
        // checks map style select button
        // ======================================================================

        function mapStyle(){

            var mapStyle = $("select#mapStyle").find(":selected").val();

            if ( mapStyle === "custom" ) {

                $(".custom-map").fadeIn(250);

            } else {

                $(".custom-map").fadeOut(250);

            }

        }

        $("select#mapMarker").change(function(){

            pinOrRadius();

        });

        $("select#mapStyle").change(function(){

            mapStyle();

        });

        // ======================================================================
        // DEFAULT PIN OR CUSTOM
        // checks if default pin or custom pin wanted
        // ======================================================================

        function defaultOrCustomPin() {

            var defaultOrCustomPin = $("select#pin").find(":selected").val();

            if ( defaultOrCustomPin === "custom" ) {

                $(".defaultOrCustomPin").each(function( i ){

                    $(this).fadeIn(100);

                });

            } else { 

                $(".defaultOrCustomPin").each(function( i ){

                    $(this).fadeOut(100);

                });
            }

        }

        $("select#pin").change(function(){

            defaultOrCustomPin();

        });

        defaultOrCustomPin();
        pinOrRadius();
        mapStyle();

    // ======================================================================
    // OPENING TIMES
    // checks the select for two variables, shows/hides corresponding format
    // ======================================================================

    	function openingTime(){

    		var openingOption = $("select#openingTimeFormat").find(":selected").val();

    		if ( openingOption === "monFri" ) {

    			$(".monFri, .weekend").fadeIn(250);
    			$(".weekdays, .twentyfour").fadeOut(0);

            } else if ( openingOption === "everyday" ) {

                $(".monFri, .twentyfour").fadeOut(250);
                $(".weekdays, .weekend").fadeIn(0);

            } else if ( openingOption === "twentyfour" ) {

                $(".monFri, .weekdays, .weekend").fadeOut(250);
                $(".twentyfour").fadeIn(0);

            }

    	}

        $("select#openingTimeFormat").change(function(){

        	openingTime();
            $(".tab-options").fadeIn(250);

        });

        if ( $('select#openingTimeFormate') != 'none' ) {

            $(".tab-options").fadeIn(250);

        }

        openingTime();

    /* ==================================================
    address shortcode
    ================================================== */

    if ( $('.shortcodeGen').attr('id') === 'address' ) {

        var addressOrNumber = $('select#addressOrNumber').find('option:selected').val();

        $('select#addressOrNumber').change(function(){

            var addressOrNumber = $('select#addressOrNumber').find('option:selected').val();

            if ( addressOrNumber != 'getAddress' ) {

                $('.schema').hide();

            } else {

                $('.schema').show();

            }

        });

        $('body').on( 'click', '#generateBtn', function(){

            var chosenAddress = $('#addressSchemaChoice').find('option:selected').val(),
                chosenId = $('#shortcodeId').val(),
                chosenSchema = $('#showSchema').find('option:selected').val(),
                chosenTitle = $('#shortcodeTitle').val(),
                addressOrNumber = $('select#addressOrNumber').find('option:selected').val();

            if ( addressOrNumber == 'getNumber' ) { 

                if ( chosenId == '' && chosenTitle == '' ) {

                    alert('Fill in an ID, and Title first.');

                } else {

                    var generatedShortcode = '['+ addressOrNumber +' address="'+chosenAddress+'" id="'+chosenId+'" title="'+chosenTitle+'" ]';

                    $('#generatedShortcode').val( generatedShortcode );

                }

            } else if ( addressOrNumber == 'getAddress' ) {

                if ( chosenId == '' && chosenTitle == '' && chosenSchema == '' ) {

                    alert('Fill in an ID, Schema Choice and Title first.');

                } else {

                    var generatedShortcode = '['+ addressOrNumber +' address="'+chosenAddress+'" id="'+chosenId+'" schema="'+chosenSchema+'" title="'+chosenTitle+'" ]';

                    $('#generatedShortcode').val( generatedShortcode );

                }

            }

        });

    } else if ( $('.shortcodeGen').attr('id') === 'help' ) {

        $('select#shortcodeName').change(function(){

            var shortcodeName = $(this).find('option:selected').val();

            if ( shortcodeName == 'openingTimes' || shortcodeName == 'socialLinks' ) {

                $('.shortcodeTitle, .schema, .shortcodeID').show();

            } else if ( shortcodeName == 'fullSchema' || shortcodeName == 'mainNumber' || shortcodeName == 'companyLogo' ) {

                $('.shortcodeID').show();

            } else  {

                $('.shortcodeTitle, .schema, .shortcodeID').hide();   

            }

        });

        var generatedShortcode = '[ '+ shortcodeName +' ]';

        $('body').on( 'click', '#generateBtn', function(){

            var title = '',
                schema = '',
                ID = '';

            if ( $('.shortcodeTitle').is(':visible') ) {

                var shortcodeTitle = $('input#shortcodeTitle').val();
                    title = ' title="' + shortcodeTitle + '"';
                
            }

            if ( $('.schema').is(':visible') ) {

                var schemaToggle = $('select#showSchema').find('option:selected').val();
                    schema = ' schema="'+ schemaToggle +'"'; 
                
            }

            if ( $('.shortcodeID').is(':visible') ) {

                var shortcodeID = $('input#shortcodeId').val();
                    ID = ' id="'+ shortcodeID +'"';

            }

            var shortcodeName = $('select#shortcodeName').find('option:selected').val(),
                generatedShortcode = '['+ shortcodeName +''+ title +''+ schema +''+ ID +']';

            $('#generatedShortcode').val( generatedShortcode );

        });

    }
 
    /* ==================================================
    address schema
    show and hide individual addresses
    ================================================== */

    $('.address-wrapper:first').find('.inside').fadeIn(150);

    $('.address-wrapper').on( 'click', 'h3', function(){

        $('.inside').fadeOut(0);
        $(this).next('.inside').fadeToggle(150);

    });

    $('.select-address').on('click',function(event){

        event.preventDefault();

        var addressClass = $(this).attr('class').split(' ')['1'];

        console.log( addressClass );

        $("html, body").animate({ 

            scrollTop: $('.address-wrapper#' +addressClass).offset().top

        }, 500 );

    });

});