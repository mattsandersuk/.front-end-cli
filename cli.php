<?php
#!/usr/local/bin/php

/**
 *
 * My WP CLI Toolset
 * 
 * 
 * php cli.php <partial_type> <partial_name> <options = --js/--scss/--php>
 * 
 * NOTE : A folder must be created beforehand for the partial_type
 * 
 * Creates files:
 * 	- SCSS
 *  - partial PHP file 
 *  - JS file
 * 
 */

/*==================================
=            SETUP VARS            =
==================================*/

	/**
	 *
	 * PATHS
	 *
	 */

	$app_paths = array(
		'php' => './partials/', 
		'scss' => './assets/src/scss/', 
		'js' => './assets/src/js/', 
		'templates' => './.cli-templates/', 
		);

/*=====  End of SETUP VARS  ======*/



/*=============================
=            SETUP            =
=============================*/

	/**
	 *
	 * Partial Type
	 * e.g. block/page-section/section/etc...
	 *
	 */

	if(isset($argv[1])){	
		$partial_type = $argv[1];    
	}

	/**
	 *
	 * Partial Name
	 * e.g. 'main-nav' / 'social-share'
	 *
	 */
	
	if(isset($argv[2])){	
		$partial_name = $argv[2];
	}

	/**
	 *
	 * Options
	 * e.g. --js --php --scss --all
	 *
	 */
	
	if(isset($argv[3])) {
		$options = array_slice($argv, 3);
	}

/*=====  End of SETUP  ======*/



/*========================================
=            HELPER FUNCTIONS            =
========================================*/

	/**
	 *
	 * Function: init
	 *
	 */
	
	function init($app_paths){
		foreach ($app_paths as $app_path) {
			if( file_exists($app_path) ){
				// 
			} else {
				mkdir($app_path, '744');
			}
		}
	}

	/**
	 *
	 * Function: create_file
	 *
	 */

	function create_file($filename){
	 	if( file_exists ( $filename ) ) {
	 		//
	 	} else {

	 		$dirname = dirname($filename);
	 		if (!is_dir($dirname))
	 		{
	 			mkdir($dirname, 0755, true);
	 		}
			return fopen($filename, "w");
	 	}
	 }

	/**
	 *
	 * Create SCSS file
	 *
	 */

	function create_scss_file($partial_type, $partial_name, $app_paths){
		
		$scss_file = $app_paths['scss'] . $partial_type . 's/_' . $partial_type . '-' . $partial_name . '.scss';
		$file = create_file($scss_file);
		if($file != ""){	
			$contents = file_get_contents( $app_paths['templates'] .'template.scss');
			$contents = str_replace("%%partial_name%%", $partial_name, $contents);
			$contents = str_replace("%%partial_type%%", $partial_type, $contents);
			fwrite($file, $contents);
			fclose($file);
		}
		$usage = '@import "' . $partial_type . 's/' . $partial_type . '/' . $partial_name . '";';
		// print "SCSS: " . $scss_file . ' | ' . $usage . "\n";
		print $usage . "\n";
	}

	/**
	 *
	 * Create PHP file
	 *
	 */

	function create_php_file($partial_type, $partial_name, $app_paths){
		
		$php_file = $app_paths['php'] . $partial_type . 's/' . $partial_type . '-' . $partial_name . '.php';
		$file = create_file($php_file);

		if($file != ""){	
			$contents = file_get_contents( $app_paths['templates'] .'template.php');
			$contents = str_replace("%%partial_name%%", $partial_name, $contents);
			$contents = str_replace("%%partial_type%%", $partial_type, $contents);
			fwrite($file, $contents);
			fclose($file);
		}
		
		$usage = 'get_template_part("partials/'.$partial_type.'s/'.$partial_type.'", "' . $partial_name . '");';
		// print "PHP: " . $php_file . ' | Usage = ' . $usage . "\n";
		print $usage . "\n";
	}

	/**
	 *
	 * Create JS file
	 *
	 */

	function create_js_file($partial_type, $partial_name, $app_paths){
		$js_file = $app_paths['js'] . '/' . $partial_type . '-' . $partial_name . '.js';
		$file = create_file($js_file);

		if($file != ""){
			$contents = file_get_contents( $app_paths['templates'] .'template.js');
			$contents = str_replace("%%partial_name%%", $partial_name, $contents);
			$contents = str_replace("%%partial_type%%", $partial_type, $contents);
			fwrite($file, $contents);
			fclose($file);
		}

		$usage = '//= include '.$partial_type.'s/_'.$partial_type.'-'.$partial_name.'.js';
		// print "JS: " . $js_file . "\n";
		print $usage . "\n";
	}

/*=====  End of HELPER FUNCTIONS  ======*/



/*=========================================
=            CLI Option ROUTES            =
=========================================*/
	

	if( isset($options) ):

		/**
		 *
		 * Iterate through argv
		 *
		 */
		
		foreach ($options as $option) {

			switch ($option) {
				
				/**
				 *
				 * All
				 *
				 */
				
				case '--all':
					create_js_file($partial_type, $partial_name, $app_paths);
					create_scss_file($partial_type, $partial_name, $app_paths);
					create_php_file($partial_type, $partial_name, $app_paths);
					break;

				/**
				 *
				 * JS
				 *
				 */

				case '--js':
					create_js_file($partial_type, $partial_name, $app_paths);
					break;
				
				/**
				 *
				 * SCSS
				 *
				 */

				case '--scss':
					create_scss_file($partial_type, $partial_name, $app_paths);
					break;

				/**
				 *
				 * PHP
				 *
				 */

				case '--php':
					create_php_file($partial_type, $partial_name, $app_paths);
					break;
				
				/**
				 *
				 * DEBUG
				 *
				 */
				
				case '--init':
					init($app_paths);
					break;

				/**
				 *
				 * Default
				 *
				 */
				
				default:
						
					break;
			}

		}

	else:

		/**
		 *
		 * If no $option set, create PHP and SCSS files
		 *
		 */
		
		create_scss_file($partial_type, $partial_name, $app_paths);
		create_php_file($partial_type, $partial_name, $app_paths);
	endif;

/*=====  End of CLI Option ROUTES  ======*/

?>
