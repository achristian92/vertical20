<?php 
	/*	
	*	Goodlayers Script Inclusion File
	*	---------------------------------------------------------------------
	*	This file contains the script that helps you include the script to 
	* 	the location you want
	*	---------------------------------------------------------------------
	*/	

	// return the custom stylesheet path
	if( !function_exists('infinite_get_style_custom') ){
		function infinite_get_style_custom($local = false){

			$upload_dir = wp_upload_dir();
			$filename = '/' . INFINITE_SHORT_NAME . '-style-custom.css';
			$local_file = $upload_dir['basedir'] . $filename;
			
			if( $local ){
				return $local_file;
			}else{
				if( file_exists($local_file) ){
					$filemtime = filemtime($local_file);

					if( is_ssl() ){
						$upload_dir['baseurl'] = str_replace('http://', 'https://', $upload_dir['baseurl']);
					}
					return $upload_dir['baseurl'] . $filename . '?' . $filemtime;
				}else{
					return get_template_directory_uri() . '/css/style-custom.css';
				}
			}
		}
	}

	// use to load theme style.css and necessary wordpress script on specific page
	add_action( 'wp_enqueue_scripts', 'infinite_include_scripts', 11 );
	if( !function_exists('infinite_include_scripts') ){
		function infinite_include_scripts(){
			
			// include plugin css
			if( !function_exists('gdlr_core_front_script') ){
				wp_enqueue_style('font-awesome', get_template_directory_uri() . '/plugins/font-awesome/css/font-awesome.min.css');
				wp_enqueue_style('elegant-font', get_template_directory_uri() . '/plugins/elegant-font/style.css');
			}

			// include site style
			wp_enqueue_style('infinite-style-core', get_template_directory_uri() . '/css/style-core.css');
			if( is_rtl() ){
				wp_enqueue_style('infinite-frontend-rtl', get_template_directory_uri() . '/css/frontend-rtl.css');
			}
			
			if( !is_customize_preview() ){
				wp_enqueue_style('infinite-custom-style', infinite_get_style_custom());
			}
			
			// include site script
			wp_enqueue_script('jquery-mmenu', get_template_directory_uri() . '/js/jquery.mmenu.js', array('jquery', 'jquery-effects-core'), '1.0.0', true );
			wp_enqueue_script('jquery-superfish', get_template_directory_uri() . '/js/jquery.superfish.js', array('jquery', 'jquery-effects-core'), '1.0.0', true );
			wp_enqueue_script('infinite-script-core', get_template_directory_uri() . '/js/script-core.js', array('jquery', 'jquery-effects-core'), '1.0.0', true );

			// wordpress comments script
			if( is_singular() && comments_open() && get_option('thread_comments') ){
				wp_enqueue_script( 'comment-reply' );
			}			

			// lower than ie9 script
			wp_enqueue_script('html5js', get_template_directory_uri() . '/js/html5.js');
			wp_script_add_data('html5js', 'conditional', 'lt IE 9');
		
		}
	}
	add_action( 'wp_enqueue_scripts', 'infinite_include_child_scripts', 999 );
	if( !function_exists('infinite_include_child_scripts') ){
		function infinite_include_child_scripts(){
			if( is_child_theme() ){
				wp_enqueue_style('infinite-child-theme-style', get_stylesheet_uri());
			}
		}
	}