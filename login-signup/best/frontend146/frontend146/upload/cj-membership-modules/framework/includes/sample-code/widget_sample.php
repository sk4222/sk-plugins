<?php 
global $cjfm_sample_widget_args;
$cjfm_sample_widget_args = array(
	'title' => __('Social Media (cjfm)', 'cjfm'),
	'description' => __('Display social media links with or without icons', 'cjfm'),
	'classname' => 'social-media',
	'width' => '250',
	'height' => '350',
	'form_options' => array(
		array(
			'type' => 'text',
			'id' => 'title',
			'label' => __('Title', 'cjfm'),
			'info' => __('Specify widget title here.', 'cjfm'),
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
			'type' => 'textgroup',
			'id' => 'facebook',
			'label' => __('facebook', 'cjfm'),
			'info' => __('Specify widget facebook here.', 'cjfm'),
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'options' => array(
				'link_text', 'link_url', 'link_image'
			), // array in case of dropdown, checkbox and radio buttons
		),
		array(
			'type' => 'text',
			'id' => 'sub_title',
			'label' => __('sub_title', 'cjfm'),
			'info' => __('Specify widget title here.', 'cjfm'),
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
	) // form array
);

class cjfm_sample_widget_widget extends WP_Widget {

    /** constructor */
    function cjfm_sample_widget_widget() {
    	global $cjfm_sample_widget_args;
		$widget_ops = array('classname' => $cjfm_sample_widget_args['classname'], 'description' => $cjfm_sample_widget_args['description']);
		$control_ops = array('width' => $cjfm_sample_widget_args['width'], 'height' => $cjfm_sample_widget_args['height']);
		$this->WP_Widget($cjfm_sample_widget_args['classname'], $cjfm_sample_widget_args['title'] , $widget_ops, $control_ops);
    }

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		global $cjfm_sample_widget_args;
		extract( $args );

		foreach ($cjfm_sample_widget_args['form_options'] as $key => $option) {
			$vars[$option['id']] = apply_filters('title', $instance[$option['id']]);
		}

		$display[] = $before_widget;
		$display[] = ($vars['title'] != '') ? $before_title.$vars['title'].$after_title : '';
		$display[] = cjfm_sample_widget_display($vars);
		$display[] = $after_widget;

		echo implode("\n", $display);
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		global $cjfm_sample_widget_args;
		$instance = $old_instance;
		foreach ($cjfm_sample_widget_args['form_options'] as $key => $option) {
			$id = $option['id'];
			if(is_array($new_instance[$id])){
				$instance[$id] = implode('~~~~~', $new_instance[$id]);
			}else{
				$instance[$id] = strip_tags($new_instance[$id]);	
			}
		}
	    return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {
		global $cjfm_sample_widget_args;
		foreach ($cjfm_sample_widget_args['form_options'] as $key => $value) {
			$id = $value['id'];
			$form_instance[$id]['id'] = $this->get_field_id($id);
			$form_instance[$id]['name'] = $this->get_field_name($id);
			$form_instance[$id]['value'] = @esc_attr( $instance[$id] );
		}

	    echo cjfm_widget_form($cjfm_sample_widget_args['form_options'], $form_instance);
	}

}
add_action('widgets_init', create_function('', 'return register_widget("cjfm_sample_widget_widget");'));


function cjfm_sample_widget_display($vars){
	if(is_array($vars)){
		foreach ($vars as $key => $var) {
			if(strpos($var, '~~~~~') > 0){
				$$key = explode('~~~~~', $var);
			}else{
				$$key = $var;
			}
		}
	}

	// Start display function here...
	$display[] = '';


	return  implode("\n", $display);
}
