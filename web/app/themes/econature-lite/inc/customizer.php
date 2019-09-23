<?php
/**
 * Econature Lite Theme Customizer
 *
 * @package Econature Lite
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function econature_lite_customize_register( $wp_customize ) {
	
function econature_lite_sanitize_checkbox( $checked ) {
	// Boolean check.
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}
	
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		
	$wp_customize->add_setting('color_scheme', array(
		'default' => '#6ab43e',
		'sanitize_callback'	=> 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'color_scheme',array(
			'label' => __('Color Scheme','econature-lite'),
			'description'	=> __('Select color from here.','econature-lite'),
			'section' => 'colors',
			'settings' => 'color_scheme'
		))
	);
	
	$wp_customize->add_setting('topbar-color', array(
		'default' => '#6ab43e',
		'sanitize_callback'	=> 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'topbar-color',array(
			'description'	=> __('Select background color for topbar.','econature-lite'),
			'section' => 'colors',
			'settings' => 'topbar-color'
		))
	);

	
	$wp_customize->add_setting('headerbg-color', array(
		'default' => '#ffffff',
		'sanitize_callback'	=> 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'headerbg-color',array(
			'description'	=> __('Select background color for header.','econature-lite'),
			'section' => 'colors',
			'settings' => 'headerbg-color'
		))
	);
	
	$wp_customize->add_setting('footer-color', array(
		'default' => '#2e2e2e',
		'sanitize_callback'	=> 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'footer-color',array(
			'description'	=> __('Select background color for footer.','econature-lite'),
			'section' => 'colors',
			'settings' => 'footer-color'
		))
	);
	
	
	// Slider Section Start		
	$wp_customize->add_section(
        'slider_section',
        array(
            'title' => __('Slider Settings', 'econature-lite'),
            'priority' => null,
			'description'	=> __('Recommended image size (1420x567). Slider will work only when you select the static front page.','econature-lite'),	
        )
    );
	
	$wp_customize->add_setting('page-setting7',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('page-setting7',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for slide one:','econature-lite'),
			'section'	=> 'slider_section'
	));	
	
	$wp_customize->add_setting('page-setting8',array(
			'default' => '0',
			'capability' => 'edit_theme_options',	
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('page-setting8',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for slide two:','econature-lite'),
			'section'	=> 'slider_section'
	));	
	
	$wp_customize->add_setting('page-setting9',array(
			'default' => '0',
			'capability' => 'edit_theme_options',	
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('page-setting9',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for slide three:','econature-lite'),
			'section'	=> 'slider_section'
	));	
	
	$wp_customize->add_setting('slide_text',array(
			'sanitize_callback' => 'sanitize_text_field',
	));	 

	$wp_customize->add_control( 'slide_text', array(
    	   'section'   => 'slider_section',
    	   'label'     => __('Add text for slider button.','econature-lite'),
    	   'type'      => 'text'
     ));
	
	$wp_customize->add_setting('hide_slider',array(
			'default' => true,
			'sanitize_callback' => 'econature_lite_sanitize_checkbox',
			'capability' => 'edit_theme_options',
	));	 

	$wp_customize->add_control( 'hide_slider', array(
		   'settings' => 'hide_slider',
    	   'section'   => 'slider_section',
    	   'label'     => __('Check this to hide slider.','econature-lite'),
    	   'type'      => 'checkbox'
     ));	
	
	// Slider Section End
	 
	 // Topbar Section

	$wp_customize->add_section(
        'contact_section',
        array(
            'title' => __('Topbar', 'econature-lite'),
            'priority' => null,
			'description'	=> __('Add your contact info here.','econature-lite'),	
        )
    );	
	
	$wp_customize->add_setting('phone-txt',array(
			'sanitize_callback'	=> 'sanitize_text_field'
	));
	
	$wp_customize->add_control('phone-txt',array(
			'label'	=> __('Add phone number here','econature-lite'),
			'type'	=> 'text',
			'section'	=> 'contact_section'
	));
	
	$wp_customize->add_setting('email-txt',array(
			'sanitize_callback'	=> 'sanitize_email'
	));
	
	$wp_customize->add_control('email-txt',array(
			'label'	=> __('Add email address here','econature-lite'),
			'type'	=> 'text',
			'section'	=> 'contact_section'
	));

	
	// Homepage Section Start		
	$wp_customize->add_section(
        'homepage_section',
        array(
            'title' => __('Homepage Boxes', 'econature-lite'),
            'priority' => null,
			'description'	=> __('Select pages for homepage boxes. This section will be displayed only when you select the static front page.','econature-lite'),	
        )
    );	
	
	$wp_customize->add_setting('page-setting1',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('page-setting1',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for first box:','econature-lite'),
			'section'	=> 'homepage_section'
	));
	
	$wp_customize->add_setting('box-color1', array(
		'default' => '#6ab43e',
		'sanitize_callback'	=> 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'box-color1',array(
			'description'	=> __('Select color for first box.','econature-lite'),
			'section' => 'homepage_section',
			'settings' => 'box-color1'
		))
	);	
	
	$wp_customize->add_setting('page-setting2',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('page-setting2',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for second box:','econature-lite'),
			'section'	=> 'homepage_section'
	));	
	
	$wp_customize->add_setting('box-color2', array(
		'default' => '#78c04d',
		'sanitize_callback'	=> 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'box-color2',array(
			'description'	=> __('Select color for second box.','econature-lite'),
			'section' => 'homepage_section',
			'settings' => 'box-color2'
		))
	);
	
	$wp_customize->add_setting('page-setting3',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('page-setting3',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for third box:','econature-lite'),
			'section'	=> 'homepage_section'
	));	
	
	$wp_customize->add_setting('box-color3', array(
		'default' => '#81c956',
		'sanitize_callback'	=> 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'box-color3',array(
			'description'	=> __('Select color for third box.','econature-lite'),
			'section' => 'homepage_section',
			'settings' => 'box-color3'
		))
	);
	
	$wp_customize->add_setting('page-setting4',array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback'	=> 'absint'
	));
	
	$wp_customize->add_control('page-setting4',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page for fourth box:','econature-lite'),
			'section'	=> 'homepage_section'
	));
	
	$wp_customize->add_setting('box-color4', array(
		'default' => '#8ed464',
		'sanitize_callback'	=> 'sanitize_hex_color',
	));
	
	$wp_customize->add_control(
		new WP_Customize_Color_Control($wp_customize,'box-color4',array(
			'description'	=> __('Select color for fourth box.','econature-lite'),
			'section' => 'homepage_section',
			'settings' => 'box-color4'
		))
	);
	
	$wp_customize->add_setting('hide_section',array(
			'default' => true,
			'sanitize_callback' => 'econature_lite_sanitize_checkbox',
			'capability' => 'edit_theme_options',
	));	 

	$wp_customize->add_control( 'hide_section', array(
		   'settings' => 'hide_section',
    	   'section'   => 'homepage_section',
    	   'label'     => __('Check this to hide section.','econature-lite'),
    	   'type'      => 'checkbox'
     ));
	
}
add_action( 'customize_register', 'econature_lite_customize_register' );	

function econature_lite_css(){
		?>
        <style>
				a, 
				.tm_client strong,
				.postmeta a:hover,
				#sidebar ul li a:hover,
				.blog-post h3.entry-title,
				.sitenav ul li a:hover, 
				.sitenav ul li.current_page_item a, 
				.sitenav ul li:hover a.parent,
				.hright-icon{
					color:<?php echo esc_attr(get_theme_mod('color_scheme','#6ab43e')); ?>;
				}
				a.blog-more:hover,
				.nav-links .current, 
				.nav-links a:hover,
				#commentform input#submit,
				input.search-submit,
				.nivo-controlNav a.active,
				.blog-date .date,
				.section-box .sec-left a,
				#slider .slide-info .slide-button:hover,
				a.read-more:hover,
				#slider .slide-info .slide-button{
					background-color:<?php echo esc_attr(get_theme_mod('color_scheme','#6ab43e')); ?>;
				}
				#header{
					background-color:<?php echo esc_attr(get_theme_mod('headerbg-color','#ffffff')); ?>;
				}
				#topbar{
					background-color:<?php echo esc_attr(get_theme_mod('topbar-color','#6ab43e')); ?>;
				}
				.copyright-wrapper{
					background-color:<?php echo esc_attr(get_theme_mod('footer-color','#2e2e2e')); ?>;
				}
		</style>
	<?php }
add_action('wp_head','econature_lite_css');

function econature_lite_customize_preview_js() {
	wp_enqueue_script( 'econature-lite-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20141216', true );
}
add_action( 'customize_preview_init', 'econature_lite_customize_preview_js' );