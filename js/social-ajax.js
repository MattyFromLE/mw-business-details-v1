jQuery(document).ready( function($){

	/* ==================================================
	ADD NEW 
	================================================== */

    $('body').on( 'click', '#addNew', function(event){

        event.preventDefault();

        var addNewSocial = $('input.addNewSocialNetwork').val();

        if ( addNewSocial === '' ) {

            alert('Enter a Value, first.');

        } else if ( $('.addNew').size() >= '2' ) {

            alert('You can only add one at a time.');

        } else {

            social_ajax(addNewSocial);

        }

    });

    $('body').on( 'click', '#deleteSocialOption', function(event){

    	event.preventDefault();

        var deleteSocialOption = $(this).parent().find('input').attr('id');

        console.log( deleteSocialOption );

        social_ajax_delete( deleteSocialOption );

    });

	//Main ajax function
    function social_ajax( addNewSocial ) {

        var ajax_url = social_ajax_params.ajax_url;

        $.ajax({

            type: 'GET',
            url: ajax_url,
            dataType: 'text',
            data: {

                action: 'social_ajax',
                id: addNewSocial,

            },

            beforeSend: function () {


            },

            success: function(data) {

            	console.log(data);
                $(data).insertAfter('.addNew:last');

            },

            error: function(){
                

            }

        }); 

    }

	function social_ajax_delete( deleteSocialOption ) {

		var ajax_url = social_ajax_params.ajax_url;

	    $.ajax({

	    	type: 'GET',
	    	url: ajax_url,
	    	dataType: 'text',
	    	data: {

	    		action: 'social_ajax_delete',
                id: deleteSocialOption,

	    	},

	    	beforeSend: function () {


	    	},

	    	success: function(data) {

                $('tr.' + deleteSocialOption ).remove();
		
	    	},

	    	error: function(){
	    		

	    	}

     	});	

	}


});