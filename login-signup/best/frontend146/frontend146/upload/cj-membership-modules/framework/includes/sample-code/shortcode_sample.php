<?php 
function cjfm_test_shortcode( $atts, $content) {
	global $wpdb, $current_user, $post;
	$defaults = array( 
		'return' => null,
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );
	
	$options = array(
		'stype' => 'closed', // single or closed
		'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa cum sociis natoque penatibus et magnis.',
		/*'default_content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa cum sociis natoque penatibus et magnis.',
		'heading' => array(__('Heading Text', 'cjfm'), 'heading', 'Default Value'),
		'paragraph' => array(__('Peragraph Text here: Lorem ipsum Magna ut est anim velit voluptate aliqua in commodo cillum sunt nostrud labore consectetur.', 'cjfm'), 'paragraph', 'Default Value'),
		'textbox' => array(__('Textbox Input', 'cjfm'), 'text', 'Default Value', 'information'),
		'textarea' => array(__('Textarea Input', 'cjfm'), 'textarea', 'default value', 'information'),
		'dropdown' => array(__('Select Options', 'cjfm'), 'dropdown', array('option 1' => 'option 1','option 2' => 'option 2'), 'information'),
		'color' => array(__('Pick Color', 'cjfm'), 'color', null, 'information'),*/
		'number' => array(__('Number', 'cjfm'), 'number', '101', 'information'),
	);

	if(!is_null($return)){ return serialize($options); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}
	$display[] = '';

	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

}
add_shortcode( 'cjfm_test_shortcode', 'cjfm_test_shortcode' );



function cjfm_test_shortcode_two( $atts, $content) {
	global $wpdb, $current_user, $post;
	$defaults = array( 
		'return' => null,
		'number' => 10,
		'textarea' => 'default texarea value',
	);
	$atts = extract( shortcode_atts( $defaults ,$atts ) );
	
	$options = array(
		'stype' => 'single', // single or closed
		'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa cum sociis natoque penatibus et magnis.',
		'default_content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa cum sociis natoque penatibus et magnis.',
		'heading' => array(__('Heading Text', 'cjfm'), 'heading', 'Default Value'),
		'paragraph' => array(__('Peragraph Text here: Lorem ipsum Magna ut est anim velit voluptate aliqua in commodo cillum sunt nostrud labore consectetur.', 'cjfm'), 'paragraph', 'Default Value'),
		'textbox' => array(__('Textbox Input', 'cjfm'), 'text', 'Default Value', 'information'),
		'textarea' => array(__('Textarea Input', 'cjfm'), 'textarea', 'default value', 'information'),
		'dropdown' => array(__('Select Options', 'cjfm'), 'dropdown', array('option 1' => 'option 1','option 2' => 'option 2'), 'information'),
		'number' => array(__('Number', 'cjfm'), 'number', '101', 'information'),
		'color' => array(__('Pick Color', 'cjfm'), 'color', null, 'information'),
	);

	if(!is_null($return)){ return serialize($options); } foreach ($defaults as $key => $value) { if($$key == ''){ $$key = $defaults[$key]; }}
	


	$display[] = $number;
	$display[] = $textarea;

	if($return == null){
	    return implode('', $display);
	}else{
	    return serialize($options);
	}

}
add_shortcode( 'cjfm_test_shortcode_two', 'cjfm_test_shortcode_two' );