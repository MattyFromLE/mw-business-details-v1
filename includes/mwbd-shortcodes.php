<?php 

/** 
*
* ==== KEY ==== 
* 
* 1 - Construct
* 2 - Register Shortcodes
* 3 - Company Logo
* 4 - Company Name
* 5 - Company Number
* 6 - Google Maps
* 7 - Phone Click Tracking
* 8 - Social
* 9 - Get Number
* 10 - Get Address
* 11 - List Numbers
* 12 - Main Number
* 13 - Alternative Number
* 14 - Fax Number
* 15 - E-Mail Address
* 16 - Full Schema
* 17 - Opening Times
* 
**/

class mw_business_details_shortcodes {

	/**
	* Construct
	*
	* @since 1.0
	*/

	public function __construct() {

		$this->register_shortcodes();
		// add_action('wp_head', array(&$this, 'mw_scripts'), 6);  
		add_action('wp_head', array(&$this, 'socialIcons'), 6);  
		add_action('wp_head', array(&$this, 'mw_tracking'));  
		add_action('wp_head', array(&$this, 'mw_social_styles'), 6);  

	}

	/**
	* Register Shortcodes
	* Function to register our shortcodes
	*
	* @since 1.0
	*/

	public function register_shortcodes(){
		
	   add_shortcode('companyLogo', array(&$this, 'mwCompanyLogo'));
	   add_shortcode('fullSchema', array(&$this, 'mwFullSchema'));
	   add_shortcode('getAddress', array(&$this, 'mwGetAddress'));
	   add_shortcode('getNumber', array(&$this, 'mwGetNumber'));
	   add_shortcode('socialLinks', array(&$this, 'mwSocialLinks'));
	   add_shortcode('mainNumber', array(&$this, 'mwMainNumber'));
	   add_shortcode('listNumbers', array(&$this, 'mwListNumbers'));
	   add_shortcode('altNumber', array(&$this, 'mwAltNumber'));
	   add_shortcode('faxNumber', array(&$this, 'mwFaxNumber'));
	   add_shortcode('email', array(&$this, 'mwEmail'));
	   add_shortcode('companyName', array(&$this, 'mwCompanyName'));
	   add_shortcode('companyNumber', array(&$this, 'mwCompanyNumber'));
	   add_shortcode('mapWrapper', array(&$this, 'mwMapWrapper'));
	   add_shortcode('getMap', array(&$this, 'mwGetMap'));
	   add_shortcode('openingTimes', array(&$this, 'mwOpeningTimes'));

	}

	/**
	* Sluganator
	* Function to turn string into slugs, or not - as the case may be
	*
	* @since 1.0
	*/

	public function mwSluganator( $string, $slug = 'slug' ) {

		if ( $slug == 'deslug' ) {

			$string = ucwords( $string );
			$string = str_replace( '-', ' ', $string );
			return $string;

		} else {

			$string = strtolower( $string );
			$string = str_replace( ' ', '-', $string );
			return $string;

		}

	}

	/**
	* Company Logo
	* Shortcode for retrieving the company logo
	*
	* @since 1.0
	*/

	public function mwCompanyLogo( $atts ) {

		$logoID = get_option('business_logo_id');
		$logoSrc = wp_get_attachment_image_src( $logoID, 'mw-logo-size' );

		if ( $logoID ) {

			$html = ' ';

			$html .= '<div id="'.$atts["id"].'-logo" class="logo" itemscope itemtype="http://schema.org/localBusiness">';

			$html .= '<a itemprop="url" href="'. get_bloginfo('url') .'" rel="home" >';
			
			$html .= '<img itemprop="logo" src="'. $logoSrc[0] .'" id="mainlogo" alt="'.get_bloginfo("name").' Logo" />';
			
			$html .= '</a>';

			$html .= '</div>';

			return $html;

		}

	}

	/**
	* Company Name
	* Shortcode for retrieving the company name
	*
	* @since 1.0
	*/

	public function mwCompanyName() {

		$defaultName = get_bloginfo("name");
	    $customName = get_option( "company_name" );

	    if ( $customName ) { 

	    	$companyName = '<span>'.$customName.'</span>'; 

	    } else { 

	    	$companyName = '<span>'.$defaultName.'</span>'; 

	    }
	   
	    return $defaultName;

	}

	/**
	* Company Number
	* Shortcode for retrieving the company number
	*
	* @since 1.0
	*/

	public function mwCompanyNumber() {

		$companyNumber = get_option( "company_no" );

		if ( $companyNumber ) {
	 
			$html = ' ';
			$html .= $companyNumber;

			return $html;

		}


	}

	/**
	* Google Maps
	* Control google maps found @ mwbd-map-settings
	*
	* @since 1.0
	*/

	public function mwGetMap( $atts ) {

		/* ==================================================
		get the company address
		================================================== */

		$defaultName = get_bloginfo("name");
	    $customName = get_option( "company_name" );

	    if ( $customName ) { 

	    	$companyName = $customName; 

	    } else { 

	    	$companyName = $defaultName; 

	    }

		$addressName = $companyName;
	
		/* ==================================================
		only enqueue scripts on selected
		================================================== */

		$mapShowPage = get_option( "mapShowPage", array() );

		foreach ($mapShowPage as $page) {
		  
		  $pageID[] = $page;

		}

		if ( is_page( $pageID ) ) {

			// Enqueue google API for Google Maps
			wp_register_script( 'add-google-script', 'https://maps.googleapis.com/maps/api/js?sensor=false' );
			wp_enqueue_script( 'add-google-script' );  
			wp_register_script( 'maps_scripts', plugin_dir_url( dirname(__FILE__) ) . 'js/maps.js','','', '' );
			wp_enqueue_script( 'maps_scripts' );				

			// enqueue map styles 
			wp_enqueue_style( 'mw-frontend-theme', plugin_dir_url( dirname(__FILE__) ) .'css/mw-business-details-frontend.css','', null );

		}

		/* ==================================================
		global vars within maps
		================================================== */

		$pluginUrl = plugins_url();
		$businessAddresses = get_option( 'business_address' );
		$autoAddressArray = array();

		/* ==================================================
		is the address being set in the shortcode?
		================================================== */

		if ( isset($atts['address']) ) {

			$mapAddress = $atts['address'];

		} else {

			$mapAddress = get_option('addressChoice');

		}

		if ( isset($atts['address']) && $mapAddress != 'all' ) {
		
		// if address is being set in the shortcode, and doesn't equal all, use this map

			foreach ( $businessAddresses as $businessAddressName => $businessAddressDetails ) {

				if ( $businessAddressName == $mapAddress ) {

					$streetAddress = strip_tags($businessAddressDetails['street_address']);
					$addressLocality = strip_tags($businessAddressDetails['address_locality']);
					$addressLocality = str_replace( ',', '', $addressLocality );
					$postCode = strip_tags($businessAddressDetails['postal_code']);
					$postCode = str_replace( ',', '', $postCode );
					$autoAddressArray = $streetAddress .', '. $addressLocality .', '. $postCode;

				}

			}

		} else if ( isset($atts['address']) && $mapAddress == 'all' ) {
		
		// if the address is being set in the shortcode but does equal all

			foreach ( $businessAddresses as $businessAddressName => $businessAddressDetails ) {

				$autoAddressArray[] = array( 

					'name' => $businessAddressName,
					'address' => $businessAddressDetails['street_address'].', '.$businessAddressDetails['postal_code'].', UK',

				);
			}

		} else if ( !isset($atts['address']) && $mapAddress != 'all' ) {
		
		// if the address isn't being set in the shortcode, use the default setting in wp

			foreach ( $businessAddresses as $businessAddressName => $businessAddressDetails ) {

				if ( $businessAddressName == $mapAddress ) {

					$streetAddress = strip_tags($businessAddressDetails['street_address']);
					$addressLocality = strip_tags($businessAddressDetails['address_locality']);
					$addressLocality = str_replace( ',', '', $addressLocality );
					$postCode = strip_tags($businessAddressDetails['postal_code']);
					$postCode = str_replace( ',', '', $postCode );
					$autoAddressArray = $streetAddress .', '. $addressLocality .', '. $postCode;

				}

			}

		} else if ( !isset($atts['address']) && $mapAddress == 'all' ) { 
		
		// if the address isn't being set in the shortcode and equals all, use the multi map

			foreach ( $businessAddresses as $businessAddressName => $businessAddressDetails ) {

				$autoAddressArray[] = array( 

					'name' => $businessAddressName,
					'address' => $businessAddressDetails['street_address'].', '.$businessAddressDetails['postal_code'].', UK',

				);
			}

		}

		// map position
		$lat = get_option( "lat" );
		$long = get_option( "long" );

		// infowindow
		$showInfoWindow = get_option( "showInfoWindow" );

		// map style
		$zoom = get_option( "zoom" );
		$customMap = get_option( "customMap" );
		$style = get_option( "mapStyle" );

		// marker
		$mapMarker = get_option( "mapMarker" );
		$pin = get_option( "pin" );
		$pinImage = get_option( "pinImage" );
		$markerWidth = get_option( "markerWidth" );
		$markerHeight = get_option( "markerHeight" );

		// google plus link
		$googleMapsLink = get_option( "googleMapsLink" );

		// radius
		$radiusDistance = get_option('radiusDistance');

		if ( $autoAddressArray || $addressName || $lat || $long || $zoom || $customMap || $style || $mapMarker || $pinImage || $radiusDistance || $googleMapsLink ) { 

			wp_localize_script('maps_scripts', 'mw_map_vars', array(

					// plugin url
					'pluginUrl' => __( $pluginUrl, 'mw-business-details' ),

					// address name
					'addressName' => __( $addressName, 'mw-business-details' ),
					'autoAddress' => __( $autoAddressArray, 'mw-business-details' ),

					// map position
					'lat' => __( $lat, 'mw-business-details'),
					'long' => __( $long, 'mw-business-details'),
					'zoom' => __( $zoom, 'mw-business-details'),

					// map style
					'customMap' => $customMap,
					'mapStyle' => __( $style, 'mw-business-details' ),

					// show info window
					'showInfoWindow' => __( $showInfoWindow, 'mw-business-details' ),

					// marker
					'mapMarker' => __( $mapMarker, 'mw-business-details' ),
					'markerWidth' => __( $markerWidth, 'mw-business-details' ),
					'markerHeight' => __( $markerHeight, 'mw-business-details' ),
					'markerHeight' => __( $markerHeight, 'mw-business-details' ),
					'pin' => $pin,
					'pinImage' => $pinImage,

					// radius
					'radiusDistance' => __( $radiusDistance, 'mw-business-details' ),

					// google map link
					'googleMapsLink' => __( $googleMapsLink, 'mw-business-details' ),

				) 

			);

		}

		// print the html onto the page for the map to show
		$html = '<div id="map-wrapper"></div>';

		return $html;	

	}

	/**
	* Tracking
	* Control tracking alerts on phone numbers
	*
	* @since 1.0
	*/

	public function mw_tracking(){

		$tracking = get_option( 'autoTracking' );

		if ( $tracking !== "1" ) {

			wp_register_script( 'tracking_scripts', plugin_dir_url( dirname(__FILE__) ) . '/js/min/tracking-min.js','','', '' );
			wp_enqueue_script( 'tracking_scripts' );

			$showTrackingAlert = get_option( 'showTrackingAlert' ); //var_dump($showTrackingAlert);

				wp_localize_script ('tracking_scripts', 'mw_tracking_vars', array(

						// plugin url
					'showTrackingAlert' => __( $showTrackingAlert, 'mw-business-details' )

				)

			);

		}

	}

	/**
	* Social Styles
	* Function to enqueue social styles
	*
	* @since 1.0
	*/

	public function mw_social_styles() {

		$socialStyles = get_option( 'mwSocialStyles' );

		if ( $socialStyles === 'enqueue' ) {

			wp_enqueue_style( 'mw-social-styles', plugin_dir_url( dirname(__FILE__) ) . 'css/mw-business-details-social-styles.css','', null );

		}	

	}

	/**
	* Social Icons
	* Enqueue SVG Icons for Social Icons
	*
	* @since 1.0
	*/

	public function socialIcons() {

		// enqueue map styles 
		wp_enqueue_style( 'mw-social-icons', plugin_dir_url( dirname(__FILE__) ) .'css/icomoon-style.css','', null );
		
	}

	/**
	* Social Links
	* Controls social links frontend html
	*
	* @since 1.0
	*/

	public function mwSocialLinks( $atts ) {
			
		// ============================================================
		// company name
		// ============================================================

		$defaultName = get_bloginfo( "name" );
		$companyName = get_option( "company_name" );
		$newSocialOptions = get_option( "newSocialNetwork" );
		
		if ( $companyName ) { 

			$defaultName = $companyName;

		};
		
		// ============================================================
		// custom title
		// ============================================================

		if ( isset( $atts['title'] ) ) {

			$mwTitle = $atts['title'];

		}

		// ============================================================
		// yoast checks
		// ============================================================

		$yoastSocial = get_option('wpseo_social'); 
		$yoastFacebook = $yoastSocial['facebook_site'];
		$yoastTwitter = $yoastSocial['twitter_site'];
		$yoastGooglePlus = $yoastSocial['plus-publisher'];

		// if yoast facebook option is being used
		if ( $yoastFacebook ) {

			$mwFacebook = $yoastFacebook;

		} else { 

			$mwFacebook = get_option('facebook');

		}

		// if yoast twitter option is being used
		if ( $yoastTwitter ) {

			$mwTwitter = $yoastTwitter;

		} else { 

			$mwTwitter = get_option('twitter');

		}

		// if yoast g-plus option is being used
		if ( $yoastGooglePlus ) {

			$mwGooglePlus = $yoastGooglePlus;

		} else { 

			$mwGooglePlus = get_option('googlePlus');

		}

		// not included in yoast anyway
		$mwLinkedIn = get_option('linkedIn');

 		if ( isset($atts['class']) ) { 

			$containerClass = $atts['class']; 

		} else { 

			$containerClass = ''; 

		};

		$html = ' ';

		// ============================================================
		// social checks
		// ============================================================

		if ( $mwTwitter || $mwFacebook || $mwLinkedIn || $mwGooglePlus || $newSocialOptions ) { 

		$html .= '<div class="mw-business-details '. $containerClass .'">'; 

		if ( isset($mwTitle) ) {

			$html .= '<p class="schemaTitle">'.$mwTitle.'</p>';

		}

		$html .= '<ul class="social-methods" id="'.$atts["id"].'-social-links">';

			if ( $mwTwitter ) { $html .= '<li><a target="_blank" class="twitter icon-twitter" href="https://twitter.com/'.$mwTwitter.'" title="View '.$defaultName.' on Twitter"></a></li>'; }

			if ( $mwFacebook ) { $html .= '<li><a target="_blank" class="facebook icon-facebook" href="'.$mwFacebook.'" title="View '.$defaultName.' on Facebook"></a></li>'; }

			if ( $mwLinkedIn ) { $html .= '<li><a target="_blank" class="linkedIn icon-linkedin" href="'.$mwLinkedIn.'" title="View '.$defaultName.' on LinkedIn"></a></li>'; }

			if ( $mwGooglePlus) { $html .= '<li><a target="_blank" class="googleplus icon-google-plus" href="'.$mwGooglePlus.'" title="View '.$defaultName.' on Google Plus"> </a></li>'; }

			if ( $newSocialOptions ) {

				foreach ( $newSocialOptions as $newSocialOptionName => $newSocialOptionDetails ) {

					$html .= '<li><a target="_blank" class="'.$newSocialOptionDetails['icon'].'" href="'.$newSocialOptionDetails['url'].'" title="View '.$defaultName.' on '.ucwords($newSocialOptionName).'"></a></li>';

				}

			}
							
		$html .= '</ul></div> ';

		}

		return $html;
			
	}

	/**
	* Get Number
	* Dynamically retrieve a telephone number
	*
	* @since 1.0
	*/

	public function mwGetNumber( $atts ) {

		$mainAddresses = get_option('business_address');
		$html = '';
		$pageID = $atts['id'];
		$numberTitle = $atts['title'];
		
		$html .= '<div class="mw-business-details mw-business-details-section numbers">';
		$html .= '<p class="h3 schemaTitle">'. $numberTitle .'</p>';

		if ( $atts['address'] ) {

			$numberChoice = $atts['address'];

			foreach ( $mainAddresses as $mainAddressName => $mainAddressDetails ) {

				$mainAddressNameSlug = $mainAddressName;
				$mainAddressName = str_replace( '-', ' ', $mainAddressName);
				$mainAddressName = ucwords($mainAddressName);
				$telNumber = $mainAddressDetails['telephone_number'];

				if ( $numberChoice == $mainAddressNameSlug ) {
				
					$html .= '<a class="phone" itemprop="telephone"href="tel:'.$telNumber.'" title="Call Today" id="'. $pageID .'-'.$mainAddressNameSlug.'-phone"><span>'.$mainAddressName.': </span><span class="calltrack_number">'.$telNumber.'</span></a><br/>';

				}

			}

		} else {

			foreach ( $mainAddresses as $mainAddressName => $mainAddressDetails ) {

				$mainAddressNameSlug = $mainAddressName;
				$mainAddressName = str_replace( '-', ' ', $mainAddressName);
				$mainAddressName = ucwords($mainAddressName);
				$telNumber = $mainAddressDetails['telephone_number'];
				$html .= '<a class="phone" itemprop="telephone"href="tel:'.$telNumber.'" title="Call Today" id="'. $pageID .'-'.$mainAddressNameSlug.'-phone"><span>'.$mainAddressName.': </span><span class="calltrack_number">'.$telNumber.'</span></a><br/>';

			}			
		}

		$html .= '</div>';

		return $html;

	}

	/**
	* Get Address
	* Shortcode to retrieve a custom address
	*
	* @since 1.0
	*/

	public function mwGetAddress( $atts ) {

			$defaultName = get_bloginfo( "name" );
			$companyName = get_option( "company_name" );
			$businessType = get_option( "businessType" );
			$mainAddresses = get_option('business_address');

			if ( isset($atts["address"]) ) {

				$addressChoice = $atts["address"];

			} else {

				$addressChoice = $this->mwSluganator( get_option( 'addressChoice' ), 'slug' );

			}

			// dynamic naming
			if ( $companyName ) { 

				$defaultName = $companyName; 

			};

			if ( isset( $atts['title'] ) ){

				$mwTitle = $atts['title']; 

			}

			$mainBusiness = get_option('business_address');
			$addressCount = '1';

			$html = '';

			if ( isset($atts["schema"] ) && $atts["schema"] == "show" ) {

				$html = '<div id="'.$atts["id"].'-address" class="mw-business-details mw-business-details-section" itemscope="" itemtype="http://schema.org/'.$businessType.'">';
				
				if ( isset($mwTitle) ) {

					$html .= '<p class="schemaTitle">'.$mwTitle.'</p>';

				}

				foreach ( $mainAddresses as $mainAddressName => $mainAddressDetails ) {

					$mainAddressNameSlug = $mainAddressName;
					$mainAddressName = $this->mwSluganator( $mainAddressName, 'deslug' );
					$streetAddress = $mainAddressDetails['street_address'];
					$addressLocality = $mainAddressDetails['address_locality'];
					$addressRegion = $mainAddressDetails['address_region'];
					$postCode = $mainAddressDetails['postal_code'];
					$telNumber = $mainAddressDetails['telephone_number'];
					$telNumberSlug = str_replace( ' ', '', $telNumber );

					if ( $addressChoice === $mainAddressNameSlug ) {
					
						$html .= '<p class="schemaTitle" itemprop="name"><strong>'.$mainAddressName.'</strong></p>';
						$html .= '<div class="address" itemscope itemtype="http://schema.org/PostalAddress">';
						$html .= '<ul>';
						$html .= '<li itemprop="streetAddress">'.$streetAddress.'</li>';
						$html .= '<li itemprop="addressLocality">'.$addressLocality.'</li>';
						$html .= '<li itemprop="addressRegion">'.$addressRegion.'</li>';
						$html .= '<li itemprop="postalCode">'.$postCode.'</li>';
						$html .= '</ul>';
						$html .= '<a class="phone" itemprop="telephone"href="tel:'.$telNumberSlug.'" title="Call Today" id="'.$mainAddressNameSlug.'-phone"><span class="calltrack_number">'.$telNumber.'</span></a>';
						$html .= '</div>';

					} 

				}

				$html .= "</div>";
				
			} else {

				foreach ( $mainAddresses as $mainAddressName => $mainAddressDetails ) {

					$mainAddressNameSlug = $mainAddressName;
					$mainAddressName = $this->mwSluganator( $mainAddressName, 'deslug' );
					$streetAddress = $mainAddressDetails['street_address'];
					$addressLocality = $mainAddressDetails['address_locality'];
					$addressRegion = $mainAddressDetails['address_region'];
					$postCode = $mainAddressDetails['postal_code'];
					$telNumber = $mainAddressDetails['telephone_number'];
					$telNumberSlug = str_replace( ' ', '', $telNumber );

					if ( $addressChoice === $mainAddressNameSlug ) {
					
						$html .= '<div class="address mw-business-details mw-business-details-section">';

						if ( isset($mwTitle) ) {

							$html .= '<p class="schemaTitle">'.$mwTitle.'</p>';

						}

						$html .= '<p class="schemaTitle"><strong>'.$mainAddressName.'</strong></p>';
						$html .= '<ul>';
						$html .= '<li>'.$streetAddress.'</li>';
						$html .= '<li>'.$addressLocality.'</li>';
						$html .= '<li>'.$addressRegion.'</li>';
						$html .= '<li>'.$postCode.'</li>';
						$html .= '</ul>';
						$html .= '<a class="phone" href="tel:'.$telNumberSlug.'" title="Call Today" id="'.$mainAddressNameSlug.'-phone"><span class="calltrack_number">Telephone: '.$telNumber.'</span></a>';
						$html .= '</div>';

					}

				}

			}
		
		//closing div
		return $html;

	}

	/**
	* List Numbers
	* Function to list all numbers
	*
	* @since 1.0
	*/

	public function mwListNumbers( ) {

		$addresses = get_option( 'business_address' );
		$addressChoice = get_option( 'main_address' );

		$html = '';
		
		$html .= '<ul class="numbers">';

		foreach ($addresses as $addressName => $addressDetails) {

			if ( $addressName == $addressChoice ) {

				$schema = ' itemprop="telephone"';

			} else {

				$schema = '';

			}

			$telephoneNumberSlug = str_replace( ' ', '', $addressDetails['telephone_number']);
			
			$html .= '<li>'.$addressDetails['address_name'].' - <a '.$schema .' class="phone" id="'.$addressName.'-phone" href="tel:'.$telephoneNumberSlug.'">'.$addressDetails['telephone_number'].'</a></li>';

		}

		$html .= '</ul>';

		return $html;

	}

	/**
	* Main Number
	* Shortcode to show the main number
	*
	* @since 1.0
	*/

	public function mwMainNumber( $atts ) {

		$telNumber = get_option( "tel_no" );
		$telNumberSlug = preg_replace('/[\s-]+/', '', $telNumber);
		$telNumberSlug = str_replace(array( '(', ')' ), '', $telNumberSlug);

		$html = ' ';
		$html .= '<a class="mwMainNumber phone" href="tel:'.$telNumberSlug.'" title="Call Today" id="'.$atts["id"].'-phone"> <span class="calltrack_number">'.$telNumber.'</span></a>';
		return $html;

	}

	/**
	* Alternative Number
	* Shortcode to show the alternative number
	*
	* @since 1.0
	*/

	public function mwAltNumber( $atts ) {

		$altNumber = get_option( "alt_no" );
		$altNoSpace = preg_replace("/[\s-]+/", "", $altNumber);
		$html = ' ';
		$html = '<a class="phone" href="tel:'.$altNoSpace.'" title="Call Today" id="'.$atts["id"].'-alt-phone">'.$altNumber.'</a>';
		return $html;
		
	}

	/**
	* Fax Number
	* Shortcode to show a fax number
	*
	* @since 1.0
	*/

	public function mwFaxNumber( ) {

		$fax_no = get_option( "fax_no" );
		$html = ' ';
		$html .= '<li class="fax">'.$fax_no.'</li>';
		return $html;

	}

	/**
	* E-Mail
	* Shortcode to show the company e-mail
	*
	* @since 1.0
	*/

	public function mwEmail( $atts ) {

		// address
		$defaultEmail = get_bloginfo( "email" );
		$customEmail = get_option( "e-mail_address" );
		
		if ( $customEmail ) {

			$email = $customEmail;

		} else { 

			$email = $defaultEmail;

		}

		$html = ' ';
		$html .= '<a class="email" href="mailto:'.$email.'" title="E-Mail Us" id="'.$atts["id"].'-email">'.$email.'</a>';
		return $html;
		
	}

	/**
	* Full Schema
	* Shortcode to show the full schema
	*
	* @since 1.0
	*/

	public function mwFullSchema( $atts ) {
		
			// company details
			$defaultName = get_bloginfo( "name" );
			$companyName = get_option( "company_name" );
			
			if ( $companyName ) {

				$defaultName = $companyName;

			};

			// other 
			$businessType = get_option( "businessType" );
			$contactPageText = get_option( "mw-contact-text" );
			$mainBusiness = get_option( 'business_address' );
			$addressCount = '1';
			
			if ( isset( $atts['title'] ) ) {

				$mwTitle = $atts['title'];

			}

			$googlePlus = get_option( "googlePlus" );

			// contact numbers
			$mainNumber = get_option( "tel_no" );
			$mainNumberNoSpace = preg_replace("/[\s-]+/", "", $mainNumber);
			$mainNumberNoBrackets = str_replace( array( '(', ')' ), '', $mainNumberNoSpace);
			$faxNumber = get_option( "fax_no" );
			$altNumber = get_option( "alt_no" );
			$altNoSpace = preg_replace("/[\s-]+/", "", $altNumber);
			$emailAddress = get_option( "e-mail_address" );

			$html = ' ';
				
			$html = '<div id="'.$atts["id"].'-address" class="mw-business-details" itemscope="" itemtype="http://schema.org/'.$businessType.'">';

				// telephones
				if ( $mainNumber || $altNumber || $faxNumber ) {

					if ( isset($mwTitle) ) {

						$html .= '<p class="schemaTitle">'.$mwTitle.'</p>';

					}

					if ( $contactPageText ) {

						$html .= '<p>'.$contactPageText.'</p>';

					}

					$html .= '<div class="mw-business-details-section contact-details"><p class="schemaTitle">Contact Details</p>';

					$html .= $this->mwListNumbers();

					if ($faxNumber) {

						$html .= '<p class="fax">'.$faxNumber.'</p>';
					
					}
				
					if ($altNumber) {
					
						$html .= '<p class="phone altnumber"><a href="tel:'.$altNoSpace.'" title="Call Today" id="contact-mobile-phone">'.$altNumber.'</a></p>';
					
					}

					if ($emailAddress) {
					
						$html .= '<p class="email"><a href="mailto:'.$emailAddress.'" title="E-Mail Us Today" id="email">' .$emailAddress.'</a></p>';
					
					}
					
					$html .= '</div>';

				}

				$mainAddresses = get_option('business_address');
				$addressChoice = get_option('main_address');

				foreach ( $mainAddresses as $mainAddressName => $mainAddressDetails ) {

					$mainAddressNameSlug = $mainAddressName;
					$mainAddressName = str_replace( '-', ' ', $mainAddressName);
					$mainAddressName = ucwords($mainAddressName);
					$streetAddress = $mainAddressDetails['street_address'];
					$addressLocality = $mainAddressDetails['address_locality'];
					$addressRegion = $mainAddressDetails['address_region'];
					$postCode = $mainAddressDetails['postal_code'];

					if ( $addressChoice === $mainAddressNameSlug ) {
					
						$html .= '<div class="mw-business-details-section address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';
						$html .= '<p class="schemaTitle" itemprop="name"><strong>'.$defaultName.'</strong></p>';
						$html .= '<ul>';
						$html .= '<li itemprop="streetAddress">'.$streetAddress.'</li>';
						$html .= '<li itemprop="addressLocality">'.$addressLocality.'</li>';
						$html .= '<li itemprop="addressRegion">'.$addressRegion.'</li>';
						$html .= '<li itemprop="postalCode">'.$postCode.'</li>';
						$html .= '</ul>';
						$html .= '</div>';

					}


				}

				// opening times
				$html .= '<div class="mw-business-details-section">'.$this->mwOpeningTimes( $atts=array( 'title' => 'Opening Times', 'schema' => 'hide' ) ).'</div>';

			// closing div
			$html .= '</div>';

		return $html;
		
	}

	/**
	* Opening Times
	* Shortcode to show the opening times
	*
	* @since 1.0
	*/

	public function mwOpeningTimes( $atts ) {
		
		// ============================================================
		// opening times format
		// ============================================================

		$businessType = get_option( "businessType" );
		$openingTimesFormat = get_option( "openingTimeFormat" );
		$twentyFourSeven = get_option( "twentyFourSeven" );
		
		// ============================================================
		// mon-fri times
		// ============================================================

		$monFriTimes = get_option( "monFriTimes" );
		
		// ============================================================
		// weekday times
		// ============================================================

		$monday = get_option( "monday" );
		$tuesday = get_option( "tuesday" );
		$wednesday = get_option( "wednesday" );
		$thursday = get_option( "thursday" );
		$friday = get_option( "friday" );
		$saturday = get_option( "saturday" );
		$sunday = get_option( "sunday" );

		// ============================================================
		// title
		// ============================================================
		
		if ( isset( $atts['title'] ) ) {

			$mwTitle = $atts['title'];

		}


		if ( isset( $atts['schema'] ) ) {

			$mwSchema = $atts['schema'];

		}

		$html = ' ';

		if ( isset($mwSchema) && $mwSchema === "show" ) {

			$html .= '<div class="mw-business-details opening-times" itemscope="" itemtype="http://schema.org/'.$businessType.'">';

		} else {

			$html .= '<div class="mw-business-details opening-times">';

		}

		if ( isset($mwTitle) ) {

			$html .= '<p class="schemaTitle">'.$mwTitle.'</p>';

		}

		if ( $openingTimesFormat || $monFriTimes || $monday || $tuesday || $wednesday || $thursday || $friday || $saturday || $sunday ) {

			$html .= '<ul>';

			if ( $openingTimesFormat === 'monFri' ) {

				if ( $monFriTimes ) {
					$html .= '<li><meta itemprop="openingHours" content="Mo-Fr '.$monFriTimes.'" ><strong>Monday - Friday</strong>: '.$monFriTimes.'</li>';
					if ( $saturday ) {
						$html .= '<li><meta itemprop="openingHours" content="Sa '.$saturday.'" ><strong>Saturday</strong>: '.$saturday.'</li>';
					} else { 
						$html .= '<li><strong>Saturday</strong>: Closed</li>'; 
					}
					
					if ( $sunday ) {
						$html .= '<li><meta itemprop="openingHours" content="Su '.$sunday.'" ><strong>Sunday</strong>: '.$sunday.'</li>';
					} else { 
						$html .= '<li><strong>Sunday</strong>: Closed</li>';
				 	}

				}

			} else if ( $openingTimesFormat === 'everyday' ) {
				
				if ( $monday ) {
					$html .= '<li><meta itemprop="openingHours" content="Mo '.$monday.'" ><strong>Monday</strong>: '.$monday.'</li>';
				} else { 
					$html .= '<li><strong>Monday</strong>: Closed</li>'; 
				}
				
				if ( $tuesday ) {
					$html .= '<li><meta itemprop="openingHours" content="Tu '.$tuesday.'" ><strong>Tuesday</strong>: '.$tuesday.'</li>';
				} else {
					$html .= '<li><strong>Tuesday</strong>: Closed</li>'; 
				}
				
				if ( $wednesday ) {
					$html .= '<li><meta itemprop="openingHours" content="We '.$wednesday.'" ><strong>Wednesday</strong>: '.$wednesday.'</li>';
				} else { 
					$html .= '<li><strong>Wednesday</strong>: Closed</li>'; 
				}
				
				if ( $thursday ) {
					$html .= '<li><meta itemprop="openingHours" content="Th '.$thursday.'" ><strong>Thursday</strong>: '.$thursday.'</li>';
				} else { 
					$html .= '<li><strong>Thursday</strong>: Closed</li>'; 
				}
				
				if ( $friday ) {
					$html .= '<li><meta itemprop="openingHours" content="Fr '.$friday.'" ><strong>Friday</strong>: '.$friday.'</li>';
				} else { 
					$html .= '<li><strong>Friday</strong>: Closed</li>'; 
				}

				if ( $saturday ) {
					$html .= '<li><meta itemprop="openingHours" content="Sa '.$saturday.'" ><strong>Saturday</strong>: '.$saturday.'</li>';
				} else { 
					$html .= '<li><strong>Saturday</strong>: Closed</li>'; 
				}
			
				if ( $sunday ) {
					$html .= '<li><meta itemprop="openingHours" content="Su '.$sunday.'" ><strong>Sunday</strong>: '.$sunday.'</li>';
				} else { 
					$html .= '<li><strong>Sunday</strong>: Closed</li>'; 
				}

			} else if ( $openingTimesFormat === "twentyfour" ) { 

				if ( $twentyFourSeven ) {
					$html .= '<li><meta itemprop="openingHours" content="Mo-Su" ><strong>'.$twentyFourSeven.'</strong></li>';
				}
			}
	 
			$html .= '</ul>';

		}

		$html .= '</div>';

		return $html;

	}

}