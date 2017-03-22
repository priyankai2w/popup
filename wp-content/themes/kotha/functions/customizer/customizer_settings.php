<?php

//////////////////////////////////////////////////////////////////
// Customizer - Add Settings
//////////////////////////////////////////////////////////////////
function kotha_register_theme_customizer( $wp_customize ) {
 	
	// Add Sections
	$wp_customize->add_section( 'kotha_new_section_footer' , array(
   		'title'      => __('Footer Settings', 'kotha'),
   		'priority'   => 103,
	) );
	$wp_customize->add_section( 'kotha_new_section_page' , array(
   		'title'      => __('Page Settings', 'kotha'),
   		'priority'   => 102,
	) );
	$wp_customize->add_section( 'kotha_new_section_post' , array(
   		'title'      => __('Post Settings', 'kotha'),
   		'priority'   => 101,
	) );
	$wp_customize->add_section( 'kotha_new_section_general' , array(
   		'title'      => __('General Settings', 'kotha'),
   		'priority'   => 100,
	) );
	
	
	
	// Add Setting
		
		// General

		$wp_customize->add_setting(
	        'kotha_home_layout',
	        array(
	            'default'     => 'rightsidebar',
	            'sanitize_callback' => 'esc_attr'
	        )
	    );

		$wp_customize->add_setting(
	        'kotha_blog_pagination',
	        array(
	            'default'     => 'pagination',
	            'sanitize_callback' => 'esc_attr'
	        )
	    );

		$wp_customize->add_setting(
	        'kotha_preloader',
	        array(
	            'default'     => false,
	            'sanitize_callback' => 'esc_attr'
	        )
	    );

		
		// Header and logo
		$wp_customize->add_setting(
	        'kotha_logo',
	        array(
	        	'sanitize_callback' => 'esc_url'
	        )
	    );
		
		// Post Settings
		$wp_customize->add_setting(
	        'kotha_post_author_name',
	        array(
	            'default'     => false,
	            'sanitize_callback' => 'esc_attr'
	        )
	    );
	    $wp_customize->add_setting(
	        'kotha_post_date',
	        array(
	            'default'     => false,
	            'sanitize_callback' => 'esc_attr'
	        )
	    );
	    $wp_customize->add_setting(
	        'kotha_post_cat',
	        array(
	            'default'     => false,
	            'sanitize_callback' => 'esc_attr'
	        )
	    );
	    $wp_customize->add_setting(
	        'kotha_post_tags',
	        array(
	            'default'     => false,
	            'sanitize_callback' => 'esc_attr'
	        )
	    );
		$wp_customize->add_setting(
	        'kotha_post_author',
	        array(
	            'default'     => false,
	            'sanitize_callback' => 'esc_attr'
	        )
	    );
	    $wp_customize->add_setting(
	        'kotha_post_nav',
	        array(
	            'default'     => false,
	            'sanitize_callback' => 'esc_attr'
	        )
	    );
		$wp_customize->add_setting(
	        'kotha_post_related',
	        array(
	            'default'     => false,
	            'sanitize_callback' => 'esc_attr'
	        )
	    );
		
		
		// Page Settings
		$wp_customize->add_setting(
	        'kotha_page_comments',
	        array(
	            'default'     => false,
	            'sanitize_callback' => 'esc_attr'
	        )
	    );
		$wp_customize->add_setting(
	        'kotha_page_layout',
	        array(
	            'default'     => 'rightsidebar',
	            'sanitize_callback' => 'esc_attr'
	        )
	    );


		
		// Footer Options
		$wp_customize->add_setting(
	        'kotha_back_to_top',
	        array(
	            'default'     => false,
	            'sanitize_callback' => 'esc_attr'
	        )
	    );
		$wp_customize->add_setting(
	        'kotha_footer_copyright',
	        array(
	            'sanitize_callback' => 'wp_kses'
	        )
	    );
		
		// Color Options

			// Color general
			$wp_customize->add_setting(
				'kotha_theme_color',
				array(
					'default'     => '#00ACDF',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);
			$wp_customize->add_setting(
				'kotha_anchor_color',
				array(
					'default'     => '#23b2dd',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);
			$wp_customize->add_setting(
				'kotha_anchor_hover_color',
				array(
					'default'     => '#00ACDF',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);


    // Add Control
		
		// General
		
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_home_layout',
				array(
					'label'          => __('Homepage Layout', 'kotha'),
					'section'        => 'kotha_new_section_general',
					'settings'       => 'kotha_home_layout',
					'type'           => 'radio',
					'priority'	 => 2,
					'choices'        => array(
						'full'   => __('Full Posts', 'kotha'),
						'rightsidebar'  => __('Right Sidebar', 'kotha')
					)
				)
			)
		);
		
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_blog_pagination',
				array(
					'label'          => __('Blog Pagination or Navigation', 'kotha'),
					'section'        => 'kotha_new_section_general',
					'settings'       => 'kotha_blog_pagination',
					'type'           => 'radio',
					'priority'	 => 3,
					'choices'        => array(
						'pagination'   => __('Pagination', 'kotha'),
						'navigation'  => __('Navigation', 'kotha')
					)
				)
			)
		);
		
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_preloader',
				array(
					'label'      => __('Disable Preloader', 'kotha'),
					'section'    => 'kotha_new_section_general',
					'settings'   => 'kotha_preloader',
					'type'		 => 'checkbox',
					'priority'	 => 4
				)
			)
		);
		
		// Header and Logo
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'kotha_logo',
				array(
					'label'      => __('Upload Logo', 'kotha'),
					'section'    => 'title_tagline',
					'settings'   => 'kotha_logo',
					'priority'	 => 60
				)
			)
		);
		
		
		// Post Settings

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_post_author_name',
				array(
					'label'      => __('Hide Author Name', 'kotha'),
					'section'    => 'kotha_new_section_post',
					'settings'   => 'kotha_post_author_name',
					'type'		 => 'checkbox',
					'priority'	 => 1
				)
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_post_date',
				array(
					'label'      => __('Hide Date', 'kotha'),
					'section'    => 'kotha_new_section_post',
					'settings'   => 'kotha_post_date',
					'type'		 => 'checkbox',
					'priority'	 => 2
				)
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_post_cat',
				array(
					'label'      => __('Hide Category', 'kotha'),
					'section'    => 'kotha_new_section_post',
					'settings'   => 'kotha_post_cat',
					'type'		 => 'checkbox',
					'priority'	 => 3
				)
			)
		);
		
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_post_tags',
				array(
					'label'      => __('Hide Tags', 'kotha'),
					'section'    => 'kotha_new_section_post',
					'settings'   => 'kotha_post_tags',
					'type'		 => 'checkbox',
					'priority'	 => 4
				)
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_post_author',
				array(
					'label'      => __('Hide Author Box', 'kotha'),
					'section'    => 'kotha_new_section_post',
					'settings'   => 'kotha_post_author',
					'type'		 => 'checkbox',
					'priority'	 => 5
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_post_nav',
				array(
					'label'      => __('Hide Next/Prev Post Navigation', 'kotha'),
					'section'    => 'kotha_new_section_post',
					'settings'   => 'kotha_post_nav',
					'type'		 => 'checkbox',
					'priority'	 => 6
				)
			)
		);
		
		// Page settings
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_page_comments',
				array(
					'label'      => __('Hide Comments', 'kotha'),
					'section'    => 'kotha_new_section_page',
					'settings'   => 'kotha_page_comments',
					'type'		 => 'checkbox',
					'priority'	 => 1
				)
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_page_layout',
				array(
					'label'          => __('Page Layout', 'kotha'),
					'section'        => 'kotha_new_section_page',
					'settings'       => 'kotha_page_layout',
					'type'           => 'radio',
					'priority'	 => 2,
					'choices'        => array(
						'full'   => __('Fullwidth', 'kotha'),
						'rightsidebar'  => __('Right Sidebar', 'kotha')
					)
				)
			)
		);
		
		// Footer Settings

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_back_to_top',
				array(
					'label'      => __('Disable Back to top', 'kotha'),
					'section'    => 'kotha_new_section_footer',
					'settings'   => 'kotha_back_to_top',
					'type'		 => 'checkbox',
					'priority'	 => 1
				)
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'kotha_footer_copyright',
				array(
					'label'      => __('Footer Copyright Text', 'kotha'),
					'section'    => 'kotha_new_section_footer',
					'settings'   => 'kotha_footer_copyright',
					'type'		 => 'textarea',
					'priority'	 => 2
				)
			)
		);
		
		// Color Settings
			
			// Colors general
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'kotha_theme_color',
					array(
						'label'      => __('Theme Color', 'kotha'),
						'section'    => 'colors',
						'settings'   => 'kotha_theme_color',
						'priority'	 => 1
					)
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'kotha_anchor_color',
					array(
						'label'      => __('Anchor Color', 'kotha'),
						'section'    => 'colors',
						'settings'   => 'kotha_anchor_color',
						'priority'	 => 2
					)
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'kotha_anchor_hover_color',
					array(
						'label'      => __('Anchor Hover Color', 'kotha'),
						'section'    => 'colors',
						'settings'   => 'kotha_anchor_hover_color',
						'priority'	 => 3
					)
				)
			);

		
	

	// Remove Sections
	$wp_customize->remove_section( 'static_front_page');
	
 
}
add_action( 'customize_register', 'kotha_register_theme_customizer' );
?>