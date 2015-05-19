<?php 

/**
* Class for extra functions 
*/
class mw_business_details_other_functions {

	public function __construct() {

		if ( get_option('favicon') ) { 

		    add_action('wp_head', array(&$this, 'add_favicon'), '');

		}

		if ( get_option('apple_touch') ) { 

		    add_action('wp_head', array(&$this, 'add_apple_touch'), '');

		}

		if ( get_option('mw_typekit') ) { 

		    add_action('wp_head', array(&$this, 'add_typekit'), '');

		}

		if ( get_option('mw_google_font') ) { 

		    add_action('wp_head', array(&$this, 'add_google_font'), '');

		}

		if ( get_option('mw_edge') ) { 

		    add_action('wp_head', array(&$this, 'add_edge'), '');

		}

	    add_action('wp_head', array(&$this, 'add_new_analytic'), '');

	}

	function add_favicon() { 

		$faviconSrc = wp_get_attachment_image_src( get_option('favicon_id'), null ); 
		echo '<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="'. $faviconSrc[0] .'" sizes="16x16 32x32" />';
		echo "\n";


	} 

	function add_apple_touch() { 

		$appleTouchSrc = wp_get_attachment_image_src( get_option('apple_touch_id'), null ); 
		echo '<link rel="apple-touch-icon" href="'. $appleTouchSrc[0] .'" />';
		echo "\n";


	} 

    function add_new_analytic() { ?>

		<script>

		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', '<?php echo get_option("analytics_profile"); ?>', '<?php echo str_replace("www.",'',$_SERVER["HTTP_HOST"]); ?>');
		  ga('send', 'pageview');

		</script>

	<?php }

		function add_google_font() { 

		    wp_register_style('googlefont', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://fonts.googleapis.com/css?family=" . get_option('mw_google_font'),'',null,'all');
		    wp_enqueue_style('googlefont');

		}

		function add_typekit() { 

		    wp_register_script( 'add-typekit', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://use.typekit.net/" . get_option('mw_typekit') . ".js",'',null , false);
		    wp_enqueue_script('add-typekit');
		
		}

		function add_edge() {     

		    wp_register_script( 'add-edge', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://use.edgefonts.net/" . get_option('mw_edge') . ".js",'',null , false);
		    wp_enqueue_script('add-edge');
		
		}

} ?>