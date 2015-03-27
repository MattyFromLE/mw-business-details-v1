jQuery(document).ready(function($){

	var ajax_url = address_ajax_params.ajax_url;

	$('input.address-name').change(function(){

		var addressWrapper = $(this).closest('.address-wrapper'),
			addressName = addressWrapper.attr('id'),
			inputs = addressWrapper.find('input');

		inputs.each(function(){

			var newAddressName = $(this).attr('name').replace( addressName, $('input.address-name').val() ).toLowerCase();

			$(this).attr( 'name', newAddressName.replace(/ /g, '-') )

		});

		$(this).closest('.address-wrapper').attr( 'id', $(this).val().replace(/ /g, '-').toLowerCase() );

	});

	// create new address
	$('body').on( 'click', '#addNewAddress', function( event ){

		event.preventDefault();

		var newAddressName = $(this).parentsUntil('tr').find('input').val();

		if ( newAddressName != '' ) {

			add_new_address( newAddressName );

		} else {

			alert('Enter an Address Name First');

		}

	});

	// delete address
	$('body').on( 'click', '#deleteAddress', function(event){

		event.preventDefault();

	    var deleteAddress = $(this).closest('.address-wrapper').attr('id');

	    delete_business_address( deleteAddress );

	});


	//Main ajax function
    function add_new_address( newAddressName ) {

        $.ajax({

            type: 'GET',
            url: ajax_url,
            dataType: 'text',
            data: {

                action: 'new_business_address',
                addressName: newAddressName,

            },

            beforeSend: function () {


            },

            success: function(data) {

            	$('.business_addresses').append(data);
            	$('#new_business_address').val( '' );

            },

            error: function(){
                

            }

        }); 

    }

	function delete_business_address( deleteAddress ) {

	    $.ajax({

	    	type: 'GET',
	    	url: ajax_url,
	    	data: {

	    		action: 'delete_business_address',
                id: deleteAddress,

	    	},

	    	beforeSend: function () {


	    	},

	    	success: function(data) {

                $('#' + deleteAddress ).remove();
		
	    	},

	    	error: function(){
	    		

	    	}

     	});	

	}


});
