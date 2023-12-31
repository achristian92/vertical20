<?php
	/* a template for displaying the header area */

	// header container
	$body_layout = infinite_get_option('general', 'layout', 'full');
	$body_margin = infinite_get_option('general', 'body-margin', '0px');
	$header_width = infinite_get_option('general', 'header-width', 'boxed');
	$header_background_style = infinite_get_option('general', 'header-background-style', 'solid');
	
	if( $header_width == 'boxed' ){
		$header_container_class = ' infinite-container';
	}else if( $header_width == 'custom' ){
		$header_container_class = ' infinite-header-custom-container';
	}else{
		$header_container_class = ' infinite-header-full';
	}

	$header_style = infinite_get_option('general', 'header-plain-style', 'menu-right');
	$navigation_offset = infinite_get_option('general', 'fixed-navigation-anchor-offset', '');

	$header_wrap_class  = ' infinite-style-' . $header_style;
	$header_wrap_class .= ' infinite-sticky-navigation';
	if( $header_style == 'center-logo' || $body_layout == 'boxed' || 
		$body_margin != '0px' || $header_background_style == 'transparent' ){
		
		$header_wrap_class .= ' infinite-style-slide';
	}else{
		$header_wrap_class .= ' infinite-style-fixed';
	}
?>	
<header class="infinite-header-wrap infinite-header-style-plain <?php echo esc_attr($header_wrap_class); ?>" <?php
		if( !empty($navigation_offset) ){
			echo 'data-navigation-offset="' . esc_attr($navigation_offset) . '" ';
		}
	?> >
	<div class="infinite-header-background" ></div>
	<div class="infinite-header-container <?php echo esc_attr($header_container_class); ?>">
			
		<div class="infinite-header-container-inner clearfix">
			<?php

				if( $header_style == 'splitted-menu' && has_nav_menu('main_menu') ){
					global $infinite_center_nav_item;
					$infinite_center_nav_item = infinite_get_logo();
				}else{
					echo infinite_get_logo();
				}

				$navigation_class = '';
				if( infinite_get_option('general', 'enable-main-navigation-submenu-indicator', 'disable') == 'enable' ){
					$navigation_class = 'infinite-navigation-submenu-indicator ';
				}
			?>
			<div class="infinite-navigation infinite-item-pdlr clearfix <?php echo esc_attr($navigation_class); ?>" >
			<?php
				$enable_search = (infinite_get_option('general', 'enable-main-navigation-search', 'enable') == 'enable')? true: false;
				$enable_cart = (infinite_get_option('general', 'enable-main-navigation-cart', 'enable') == 'enable' && class_exists('WooCommerce'))? true: false;

				// print main menu
				if( has_nav_menu('main_menu') ){
					echo '<div class="infinite-main-menu" id="infinite-main-menu" >';
					wp_nav_menu(array(
						'theme_location'=>'main_menu', 
						'container'=> '', 
						'menu_class'=> 'sf-menu',
						'walker' => new infinite_menu_walker()
					));

					if( ($enable_search || $enable_cart) && $header_style == 'menu-left' ){
						
						if( $enable_search ){
							$search_icon = infinite_get_option('general', 'main-navigation-search-icon', 'fa fa-search');
							echo '<div class="infinite-main-menu-search" id="infinite-top-search" >';
							echo '<i class="' . esc_attr($search_icon) . '" ></i>';
							echo '</div>';
							infinite_get_top_search();

							$enable_search = false;
						}

						if( $enable_cart ){
							$cart_icon = infinite_get_option('general', 'main-navigation-cart-icon', 'fa fa-shopping-cart');
							echo '<div class="infinite-main-menu-cart" id="infinite-menu-cart" >';
							echo '<i class="' . esc_attr($cart_icon) . '" data-infinite-lb="top-bar" ></i>';
							infinite_get_woocommerce_bar();
							echo '</div>';
						
							$enable_cart = false;
						}
						
					}
					
					infinite_get_navigation_slide_bar();

					echo '</div>';
				}

				if( $header_style == 'splitted-menu' ){
					$infinite_center_nav_item = '';
				}
				
				// menu right side
				$menu_right_class = '';
				if( in_array($header_style, array('center-menu', 'center-logo', 'splitted-menu')) ){
					$menu_right_class = ' infinite-item-mglr infinite-navigation-top';
				}

				$enable_right_button = (infinite_get_option('general', 'enable-main-navigation-right-button', 'disable') == 'enable')? true: false;
				$custom_main_menu_right = apply_filters('infinite_custom_main_menu_right', '');
				if( has_nav_menu('right_menu') || $enable_search || $enable_cart || $enable_right_button || !empty($custom_main_menu_right) ){
					echo '<div class="infinite-main-menu-right-wrap clearfix ' . esc_attr($menu_right_class) . '" >';

					// search icon
					if( $enable_search ){
						$search_icon = infinite_get_option('general', 'main-navigation-search-icon', 'fa fa-search');
						echo '<div class="infinite-main-menu-search" id="infinite-top-search" >';
						echo '<i class="' . esc_attr($search_icon) . '" ></i>';
						echo '</div>';
						infinite_get_top_search();
					}

					// cart icon
					if( $enable_cart ){
						$cart_icon = infinite_get_option('general', 'main-navigation-cart-icon', 'fa fa-shopping-cart');
						echo '<div class="infinite-main-menu-cart" id="infinite-menu-cart" >';
						echo '<i class="' . esc_attr($cart_icon) . '" data-infinite-lb="top-bar" ></i>';
						infinite_get_woocommerce_bar();
						echo '</div>';
					}

					// menu right button
					if( $enable_right_button ){
						$button_class = 'infinite-style-' . infinite_get_option('general', 'main-navigation-right-button-style', 'default');
						$button_link = infinite_get_option('general', 'main-navigation-right-button-link', '');
						$button_link_target = infinite_get_option('general', 'main-navigation-right-button-link-target', '_self');
						if( !empty($button_link) ){
							echo '<a class="infinite-main-menu-right-button infinite-button-1 ' . esc_attr($button_class) . '" href="' . esc_url($button_link) . '" target="' . esc_attr($button_link_target) . '" >';
							echo infinite_get_option('general', 'main-navigation-right-button-text', '');
							echo '</a>';
						}

						$button_class = 'infinite-style-' . infinite_get_option('general', 'main-navigation-right-button-style-2', 'default');
						$button_link = infinite_get_option('general', 'main-navigation-right-button-link-2', '');
						$button_link_target = infinite_get_option('general', 'main-navigation-right-button-link-target-2', '_self');
						if( !empty($button_link) ){
							echo '<a class="infinite-main-menu-right-button infinite-button-2 ' . esc_attr($button_class) . '" href="' . esc_url($button_link) . '" target="' . esc_attr($button_link_target) . '" >';
							echo infinite_get_option('general', 'main-navigation-right-button-text-2', '');
							echo '</a>';
						}
					}

					// custom menu right
					if( !empty($custom_main_menu_right) ){
						echo gdlr_core_text_filter($custom_main_menu_right);
					}

					// print right menu
					if( has_nav_menu('right_menu') && $header_style != 'splitted-menu' ){
						infinite_get_custom_menu(array(
							'container-class' => 'infinite-main-menu-right',
							'button-class' => 'infinite-right-menu-button infinite-top-menu-button',
							'icon-class' => 'fa fa-bars',
							'id' => 'infinite-right-menu',
							'theme-location' => 'right_menu',
							'type' => infinite_get_option('general', 'right-menu-type', 'right')
						));
					}

					echo '</div>'; // infinite-main-menu-right-wrap

					if( has_nav_menu('right_menu') && $header_style == 'splitted-menu'  ){
						echo '<div class="infinite-main-menu-left-wrap clearfix infinite-item-pdlr infinite-navigation-top" >';
						infinite_get_custom_menu(array(
							'container-class' => 'infinite-main-menu-right',
							'button-class' => 'infinite-right-menu-button infinite-top-menu-button',
							'icon-class' => 'fa fa-bars',
							'id' => 'infinite-right-menu',
							'theme-location' => 'right_menu',
							'type' => infinite_get_option('general', 'right-menu-type', 'right')
						));
						echo '</div>';
					}
				}
			?>
			</div><!-- infinite-navigation -->

		</div><!-- infinite-header-inner -->
	</div><!-- infinite-header-container -->
</header><!-- header -->