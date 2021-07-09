<?php
/**
 * Defines customizer options
 *
 * @package Customizer Library Demo
 */

function customizer_library_demo_options() {

	// Theme defaults
	$primary_color = '#2da6e9';
	$secondary_color = '#ffbe02';
	$newsletter_bg_color = "";
	$footer_bg_color ="#4d626e";
	// Stores all the controls that will be added
	$options = array();

	// Stores all the sections to be added
	$sections = array();

	// Stores all the panels to be added
	$panels = array();

	// Adds the sections to the $options array
	$options['sections'] = $sections;

	// More Examples
	$section = 'examples';

	// Arrays 

	$layout_choices = array(
		'choice-1' => 'Responsive Layout',
		'choice-2' => 'Fixed Layout'
	);

	$sections[] = array(
		'id' => $section,
		'title' => __( 'Theme Settings', 'keyword' ),
		'priority' => '10'
	);

	$options['logo'] = array(
		'id' => 'logo',
		'label'   => __( 'Logo', 'keyword' ),
		'section' => $section,
		'type'    => 'image',
		'default' => get_template_directory_uri().'/assets/img/logo.png'
	);

	$options['favicon'] = array(
		'id' => 'favicon',
		'label'   => __( 'Favicon', 'keyword' ),
		'section' => $section,
		'type'    => 'image',
		'default' => ''
	);	

	$options['newsletter-bg-color'] = array(
		'id' => 'newsletter-bg-color',
		'label'   => __( 'Newsletter Background Color', 'keyword' ),
		'section' => $section,
		'type'    => 'color',
		'default' => $newsletter_bg_color // hex
	);

	$options['site-layout'] = array(
		'id' => 'site-layout',
		'label'   => __( 'Site Layout', 'keyword' ),
		'section' => $section,
		'type'    => 'radio',
		'choices' => $layout_choices,
		'default' => 'choice-1'
	);

	$options['header-search-on'] = array(
		'id' => 'header-search-on',
		'label'   => __( 'Display header search form', 'keyword' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 1,
	);	

	$options['entry-excerpt-length'] = array(
		'id' => 'entry-excerpt-length',
		'label'   => __( 'Number of words to show on excerpt', 'keyword' ),
		'section' => $section,
		'type'    => 'text',
		'default' => '45',		
	);
	
	$options['featured-content-on'] = array(
		'id' => 'featured-content-on',
		'label'   => __( 'Display featured content on homepage', 'keyword' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 1,
	);

	$options['featured-content-heading'] = array(
		'id' => 'featured-content-heading',
		'label'   => __( 'Featured content section heading', 'keyword' ),
		'section' => $section,
		'type'    => 'text',
		'default' => 'Headlines',		
	);

	$options['featured-num'] = array(
		'id' => 'featured-num',
		'label'   => __( 'Number of featured posts to show', 'keyword' ),
		'section' => $section,
		'type'    => 'text',
		'default' => '4',		
	);

	$options['featured-content-url'] = array(
		'id' => 'featured-content-url',
		'label'   => __( 'Featured content page URL', 'keyword' ),
		'section' => $section,
		'type'    => 'url',
		'default' => home_url() . '/featured-news',
	);	
		
	$options['single-featured-on'] = array(
		'id' => 'single-featured-on',
		'label'   => __( 'Display featured image on single posts', 'keyword' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => true,
	);	

	$options['author-box-on'] = array(
		'id' => 'author-box-on',
		'label'   => __( 'Display author box on single posts', 'keyword' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 1,
	);	

	$options['footer-widgets-on'] = array(
		'id' => 'footer-widgets-on',
		'label'   => __( 'Display footer widgets', 'keyword' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 1,
	);		

	//$options['example-range'] = array(
	//	'id' => 'example-range',
	//	'label'   => __( 'Example Range Input', 'keyword' ),
	//	'section' => $section,
	//	'type'    => 'range',
	//	'input_attrs' => array(
	//      'min'   => 0,
	//        'max'   => 10,
	//        'step'  => 1,
	//       'style' => 'color: #0a0',
	//	)
	//);

	// Adds the sections to the $options array
	$options['sections'] = $sections;

	// Adds the panels to the $options array
	$options['panels'] = $panels;

	$customizer_library = Customizer_Library::Instance();
	$customizer_library->add_options( $options );

	// To delete custom mods use: customizer_library_remove_theme_mods();

}
add_action( 'init', 'customizer_library_demo_options' );