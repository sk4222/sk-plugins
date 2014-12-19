<?php 
function cjfm_slides_metabox( $meta_boxes ) {
	$prefix = '_cmb_'; // Prefix for all fields

	/*	
	$show_on = '';
	if(isset($_GET['post']) && @$_GET['action'] == 'edit'){
		$page_template = get_post_meta($_GET['post'], '_wp_page_template', true);
		if(is_admin() && $page_template == 'page-templates/page-nav-menu.php'){
			$show_on = array('page');
		}
	}
	*/

	$meta_boxes[] = array(
		'id' => 'test_metabox',
		'title' => __('Slide Options', 'cjfm'),
		'pages' => array('slides'), // post type
		'context' => 'advanced',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __('Slide type', 'cjfm'),
				'desc' => '',
				'id' => $prefix . 'slide_type',
				'type' => 'select',
				'options' => array(
					array('name' => __('Image - (Must Set Featured Image)', 'cjfm'), 'value' => 'image'),
					array('name' => __('Video - (Must Specify Video URL)', 'cjfm'), 'value' => 'video'),
					array('name' => __('Contact Map - (Must Specify Map URL)', 'cjfm'), 'value' => 'contact-map'),
				)
			),
			array(
				'name' => __('Main Heading', 'cjfm'),
				'desc' => '',
				'id' => $prefix . 'main_heading',
				'type' => 'text'
			),
			array(
				'name' => __('Sub Heading', 'cjfm'),
				'desc' => '',
				'id' => $prefix . 'sub_heading',
				'type' => 'text'
			),
			array(
				'name' => __('Content', 'cjfm'),
				'desc' => __('Feel free to use any shortcodes', 'cjfm'),
				'id' => $prefix . 'content',
				'type' => 'wysiwyg'
			),
			array(
				'name' => __('Video URL', 'cjfm'),
				'desc' => __('Require for video slides', 'cjfm'),
				'id' => $prefix . 'video_url',
				'type' => 'text'
			),
			array(
				'name' => __('Map URL', 'cjfm'),
				'desc' => __('Require for contact map slides', 'cjfm'),
				'id' => $prefix . 'map_url',
				'type' => 'text'
			),
			array(
				'name' => __('Animation', 'cjfm'),
				'desc' => __('Animation will be applied to all <u>valid</u> html elements, based on slide layout.', 'cjfm'),
				'id' => $prefix . 'animation',
				'type' => 'select',
				'options' => array(
						array('name' => 'Option One', 'value' => 'standard'),
						array('name' => 'Option Two', 'value' => 'custom'),
						array('name' => 'Option Three', 'value' => 'none')				
					)
			),
			array(
				'name' => __('Animation Delay', 'cjfm'),
				'desc' => __('Specify delay in miliseconds. <code>e.g. 250</code> (1000ms = 1sec)', 'cjfm'),
				'id' => $prefix . 'animation_delay',
				'type' => 'text'
			),
		),
	);

	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'cjfm_slides_metabox' );