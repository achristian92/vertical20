<?php 
	/*	
	*	Goodlayers Function Inclusion File
	*	---------------------------------------------------------------------
	*	This file contains the script to includes necessary function to the theme
	*	---------------------------------------------------------------------
	*/

	// Set the content width based on the theme's design and stylesheet.
	if( !isset($content_width) ){
		$content_width = str_replace('px', '', '1150px'); 
	}

	// Add body class for page builder
	add_filter('body_class', 'infinite_body_class');
	if( !function_exists('infinite_body_class') ){
		function infinite_body_class( $classes ) {
			$classes[] = 'infinite-body';
			$classes[] = 'infinite-body-front';

			// layout class
			$layout = infinite_get_option('general', 'layout', 'full');
			if( $layout == 'boxed' ){
			 	$classes[] = 'infinite-boxed';

			 	$border = infinite_get_option('general', 'enable-boxed-border', 'disable');
			 	if( $border == 'enable' ){
			 		$classes[] = 'infinite-boxed-border';
			 	}
			}else{
				$classes[] = 'infinite-full';
			}

			// background class
			if( $layout == 'boxed' ){
				$post_option = infinite_get_post_option(get_the_ID());
				if( empty($post_option['body-background-type']) || $post_option['body-background-type'] == 'default' ){
					$background = infinite_get_option('general', 'background-type', 'color');
				 	if( $background == 'pattern' ){
				 		$classes[] = 'infinite-background-pattern';
				 	}
				}
			}

			// header style
			$header_style = infinite_get_option('general', 'header-style', 'plain');
			if( !in_array($header_style, array('side', 'side-toggle')) ){
				if( is_page() ){
					$post_option = infinite_get_post_option(get_the_ID());
				}

				if( empty($post_option['sticky-navigation']) || $post_option['sticky-navigation'] == 'default' ){
					$sticky_menu = infinite_get_option('general', 'enable-main-navigation-sticky', 'enable');
				}else{
					$sticky_menu = $post_option['sticky-navigation'];
				}
				if( $sticky_menu == 'enable' ){
					$classes[] = ' infinite-with-sticky-navigation';
					
					$sticky_menu_logo = infinite_get_option('general', 'enable-logo-on-main-navigation-sticky', 'enable');
					if( $sticky_menu_logo == 'disable' ){
						$classes[] = ' infinite-sticky-navigation-no-logo';
					}
				}
			}

			// blog style
			if( is_single() && get_post_type() == 'post' ){
				$blog_style = infinite_get_option('general', 'blog-style', 'style-1');
				$classes[] = ' infinite-blog-' . $blog_style;
			}

			// blockquote style
			$blockquote_style = infinite_get_option('general', 'blockquote-style', 'style-1');
			$classes[] = ' infinite-blockquote-' . $blockquote_style;
			
			return $classes;
		}
	}

	// Set the neccessary function to be used in the theme
	add_action('after_setup_theme', 'infinite_theme_setup');
	if( !function_exists( 'infinite_theme_setup' ) ){
		function infinite_theme_setup(){
			
			// define textdomain for translation
			load_theme_textdomain('infinite', get_template_directory() . '/languages');

			// add default posts and comments RSS feed links to head.
			add_theme_support('automatic-feed-links');

			// declare that this theme does not use a hard-coded <title> tag in <head>
			add_theme_support('title-tag');

			// tmce editor stylesheet
			add_editor_style('/css/editor-style.css');

			// define menu locations
			register_nav_menus(array(
				'main_menu' => esc_html__('Primary Menu', 'infinite'),
				'right_menu' => esc_html__('Secondary Menu', 'infinite'),
				'top_bar_menu' => esc_html__('Top Bar Menu', 'infinite'),
				'mobile_menu' => esc_html__('Mobile Menu', 'infinite'),
			));

			// enable support for post formats / thumbnail
			add_theme_support('post-thumbnails');
			add_theme_support('post-formats', array('aside', 'image', 'video', 'quote', 'link', 'gallery', 'audio')); // 'status', 'chat'
			
			// switch default core markup
			add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
			
			// add custom image size
			$thumbnail_sizes = infinite_get_option('plugin', 'thumbnail-sizing');
			if( !empty($thumbnail_sizes) ){
				foreach( $thumbnail_sizes as $thumbnail_size ){
					add_image_size($thumbnail_size['name'], $thumbnail_size['width'], $thumbnail_size['height'], true);
				}
			}

		}
	}

	// turn the page comment off by default
	add_filter( 'wp_insert_post_data', 'infinite_page_default_comments_off' );
	if( !function_exists('infinite_page_default_comments_off') ){
		function infinite_page_default_comments_off( $data ) {
			if( $data['post_type'] == 'page' && $data['post_status'] == 'auto-draft' ) {
				$data['comment_status'] = 0;
			} 

			return $data;
		}
	}

	// top bar menu
	if( !function_exists('infinite_get_top_bar_menu') ){
		function infinite_get_top_bar_menu($position = 'left'){
			if( has_nav_menu('top_bar_menu') ){
				wp_nav_menu(array(
					'menu_id' => 'infinite-top-bar-menu',
					'theme_location'=>'top_bar_menu', 
					'container'=> '', 
					'menu_class'=> 'sf-menu infinite-top-bar-menu infinite-top-bar-' . esc_attr($position) . '-menu',
					'walker' => new infinite_menu_walker()
				));
			}
		}
	}

	// logo displaying
	if( !function_exists('infinite_get_logo') ){
		function infinite_get_logo($settings = array()){

			$enable_logo = get_post_meta(get_the_ID(), 'gdlr-enable-logo', true);
			if( empty($enable_logo) ){
				$enable_logo = infinite_get_option('general', 'enable-logo', 'enable');
			}
			if( $enable_logo == 'disable' ){ return ''; }
			
			$extra_class  = (isset($settings['padding']) && $settings['padding'] === false)? '': ' infinite-item-pdlr';
			$extra_class .= empty($settings['wrapper-class'])? '': ' ' . $settings['wrapper-class'];
			
			$ret  = '<div class="infinite-logo ' . esc_attr($extra_class) . '">';
			$ret .= '<div class="infinite-logo-inner">';
		
			// fixed nav logo
			$orig_logo_class = ''; 
			$enable_fixed_nav = infinite_get_option('general', 'enable-main-navigation-sticky', 'enable');
			$fixed_nav_sticky = infinite_get_option('general', 'enable-logo-on-main-navigation-sticky', 'enable');
			$fixed_nav_logo = infinite_get_option('general', 'fixed-navigation-bar-logo', '');
			$fixed_nav_logo2x = infinite_get_option('general', 'fixed-navigation-bar-logo2x', '');
			if( $enable_fixed_nav == 'enable' && $fixed_nav_sticky == 'enable' && !empty($fixed_nav_logo) ){
				$srcset = '';
				if( !empty($fixed_nav_logo2x) ){
					$srcset  = gdlr_core_get_image_url($fixed_nav_logo, 'full', false) . ' 1x, ';
					$srcset .= gdlr_core_get_image_url($fixed_nav_logo2x, 'full', false) . ' 2x';
					$srcset = ' srcset="' . esc_attr($srcset) . '" ';
				}

				$ret .= '<a class="infinite-fixed-nav-logo" href="' . esc_url(home_url('/')) . '" >';
				$ret .= gdlr_core_get_image($fixed_nav_logo, 'full', array('srcset' => $srcset));
				$ret .= '</a>';

				$orig_logo_class = ' infinite-orig-logo'; 
			}
		
			// print logo / mobile logo
			if( !empty($settings['mobile']) ){
				$logo_id = infinite_get_option('general', 'mobile-logo');
			} 
			if( empty($logo_id) ){
				$logo_id = infinite_get_option('general', 'logo');
				$logo_id2x = infinite_get_option('general', 'logo2x');
			}
			if( is_numeric($logo_id) && !wp_attachment_is_image($logo_id) ){
				$logo_id = '';
			}
			$ret .= '<a class="' . esc_attr($orig_logo_class) . '" href="' . esc_url(home_url('/')) . '" >';
			if( empty($logo_id) ){
				$srcset  = get_template_directory_uri() . '/images/logo.png 1x, ';
				$srcset .= get_template_directory_uri() . '/images/logo2x.png 2x';
				$srcset = ' srcset="' . esc_attr($srcset) . '" ';

				$ret .= gdlr_core_get_image(get_template_directory_uri() . '/images/logo.png', 'full', array('srcset' => $srcset));
			}else{
				$srcset = '';
				if( !empty($logo_id2x) ){
					$srcset  = gdlr_core_get_image_url($logo_id, 'full', false) . ' 1x, ';
					$srcset .= gdlr_core_get_image_url($logo_id2x, 'full', false) . ' 2x';
					$srcset = ' srcset="' . esc_attr($srcset) . '" ';
				}

				$ret .= gdlr_core_get_image($logo_id, 'full', array('srcset' => $srcset));
			}
			$ret .= '</a>';

			$ret .= '</div>';
			$ret .= '</div>';

			return $ret;
		}	
	}

	// set anchor color
	add_action('wp_enqueue_scripts', 'infinite_set_anchor_color', 12);
	if( !function_exists('infinite_set_anchor_color') ){
		function infinite_set_anchor_color(){
			$post_option = infinite_get_post_option(get_the_ID());

			$anchor_css = '';
			if( !empty($post_option['bullet-anchor']) ){
				foreach( $post_option['bullet-anchor'] as $anchor ){
					if( !empty($anchor['title']) ){
						$anchor_section = str_replace('#', '', $anchor['title']);

						if( !empty($anchor['anchor-color']) ){
							$anchor_css .= '.infinite-bullet-anchor[data-anchor-section="' . esc_attr($anchor_section) . '"] a:before{ background-color: ' . esc_html($anchor['anchor-color']) . '; }';
						}
						if( !empty($anchor['anchor-hover-color']) ){
							$anchor_css .= '.infinite-bullet-anchor[data-anchor-section="' . esc_attr($anchor_section) . '"] a:hover, ';
							$anchor_css .= '.infinite-bullet-anchor[data-anchor-section="' . esc_attr($anchor_section) . '"] a.current-menu-item{ border-color: ' . esc_html($anchor['anchor-hover-color']) . '; }';
							$anchor_css .= '.infinite-bullet-anchor[data-anchor-section="' . esc_attr($anchor_section) . '"] a:hover:before, ';
							$anchor_css .= '.infinite-bullet-anchor[data-anchor-section="' . esc_attr($anchor_section) . '"] a.current-menu-item:before{ background: ' . esc_html($anchor['anchor-hover-color']) . '; }';
						}
					}
				}
			}

			if( !empty($anchor_css) ){
				wp_add_inline_style('infinite-style-core', $anchor_css);
			}
		}
	}

	// remove id from nav menu item
	add_filter('nav_menu_item_id', 'infinite_nav_menu_item_id', 10, 4);
	if( !function_exists('infinite_nav_menu_item_id') ){
		function infinite_nav_menu_item_id( $id, $item, $args, $depth ){
			return '';
		}
	}

	// add additional script
	add_action('wp_head', 'infinite_header_script', 99);
	if( !function_exists('infinite_header_script') ){
		function infinite_header_script(){
			$header_script = infinite_get_option('plugin', 'additional-head-script', '');
			if( !empty($header_script) ){
				echo '<script>' . $header_script . '</script>';
			}

			$header_script2 = infinite_get_option('plugin', 'additional-head-script2', '');
			if( !empty($header_script2) ){
				echo gdlr_core_text_filter($header_script2);
			}

		}
	}
	add_action('gdlr_core_after_body', 'infinite_after_body_script');
	if( !function_exists('infinite_after_body_script') ){
		function infinite_after_body_script(){
			$body_script = infinite_get_option('plugin', 'additional-body-script', '');
			if( !empty($body_script) ){
				echo gdlr_core_text_filter($body_script);
			}

		}
	}
	add_action('wp_footer', 'infinite_footer_script');
	if( !function_exists('infinite_footer_script') ){
		function infinite_footer_script(){
			$footer_script = infinite_get_option('plugin', 'additional-script', '');
			if( !empty($footer_script) ){
				echo '<script>' . $footer_script . '</script>';
			}

		}
	}

	remove_action('tgmpa_register', 'newsletter_register_required_plugins');