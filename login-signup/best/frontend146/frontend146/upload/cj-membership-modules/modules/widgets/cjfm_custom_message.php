<?php 
global $cjfm_login_info_widget_args;

$yes_now_array = array('yes' => __('Yes', 'cjfm'), 'no' => __('No', 'cjfm'));

$cjfm_login_info_widget_args = array(
	'title' => __('Custom Content (Membership Modules)', 'cjfm'),
	'description' => __('Custom content based on user login status.', 'cjfm'),
	'classname' => 'cjfm-login-widget',
	'width' => '350',
	'height' => '350',
	'form_options' => array(
		array(
			'type' => 'heading',
			'id' => 'visitor_info',
			'label' => '',
			'info' => __('<p><b>Visitors</b> (Users not logged in)</p>', 'cjfm'),
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
			'type' => 'text',
			'id' => 'visitor_title',
			'label' => __('Widget Title:', 'cjfm'),
			'info' => __('e.g. Welcome User!', 'cjfm'),
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
			'type' => 'textarea',
			'id' => 'visitor_msg',
			'label' => __('Message:', 'cjfm'),
			'info' => __('Specify message and shortcodes for users who are <b>not</b> logged in.', 'cjfm'),
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
			'type' => 'select',
			'id' => 'show_visitor',
			'label' => __('Show widget:', 'cjfm'),
			'info' => __('You can disable this widget for users who are <b>not</b> logged in.', 'cjfm'),
			'suffix' => '',
			'prefix' => '',
			'default' => 'yes',
			'options' => $yes_now_array, // array in case of dropdown, checkbox and radio buttons
		),
		array(
			'type' => 'heading',
			'id' => 'user_info',
			'label' => '',
			'info' => __('<p><b>Users</b> (Logged in users)</p>', 'cjfm'),
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
			'type' => 'text',
			'id' => 'user_title',
			'label' => __('Widget Title:', 'cjfm'),
			'info' => sprintf(__('Welcome %s', 'cjfm'), '%%display_name%%'),
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
			'type' => 'textarea',
			'id' => 'user_msg',
			'label' => __('User Message:', 'cjfm'),
			'info' => __('Specify message and shortcodes for users who are logged in.', 'cjfm'),
			'suffix' => '',
			'prefix' => '',
			'default' => '',
			'options' => '', // array in case of dropdown, checkbox and radio buttons
		),
		array(
			'type' => 'select',
			'id' => 'show_user',
			'label' => __('Show widget:', 'cjfm'),
			'info' => __('You can disable this widget for users who are logged in.', 'cjfm'),
			'suffix' => '',
			'prefix' => '',
			'default' => 'yes',
			'options' => $yes_now_array, // array in case of dropdown, checkbox and radio buttons
		),
	) // form array
);

class cjfm_login_info_widget_widget extends WP_Widget {

    /** constructor */
    function cjfm_login_info_widget_widget() {
    	global $cjfm_login_info_widget_args;
		$widget_ops = array('classname' => $cjfm_login_info_widget_args['classname'], 'description' => $cjfm_login_info_widget_args['description']);
		$control_ops = array('width' => $cjfm_login_info_widget_args['width'], 'height' => $cjfm_login_info_widget_args['height']);
		$this->WP_Widget($cjfm_login_info_widget_args['classname'], $cjfm_login_info_widget_args['title'] , $widget_ops, $control_ops);
    }

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		global $cjfm_login_info_widget_args;
		extract( $args );

		foreach ($cjfm_login_info_widget_args['form_options'] as $key => $option) {
			$vars[$option['id']] = @$instance[$option['id']];
		}

		$widget_params = array(
			'before_widget' => $before_widget,
			'after_widget' => $after_widget,
			'before_title' => $before_title,
			'after_title' => $after_title,
		);
		
		$display[] = cjfm_login_info_widget_display($vars, $widget_params);
		

		echo implode("\n", $display);
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		global $cjfm_login_info_widget_args;
		$instance = $old_instance;
		foreach ($cjfm_login_info_widget_args['form_options'] as $key => $option) {
			$id = $option['id'];
			if(is_array($new_instance[$id])){
				$instance[$id] = implode('~~~~~', $new_instance[$id]);
			}else{
				$instance[$id] = $new_instance[$id];
			}
		}
	    return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {
		global $cjfm_login_info_widget_args;
		foreach ($cjfm_login_info_widget_args['form_options'] as $key => $value) {
			$id = $value['id'];
			$form_instance[$id]['id'] = $this->get_field_id($id);
			$form_instance[$id]['name'] = $this->get_field_name($id);
			$form_instance[$id]['value'] = $instance[$id];
		}

	    echo cjfm_widget_form($cjfm_login_info_widget_args['form_options'], $form_instance);
	}

}
add_action('widgets_init', create_function('', 'return register_widget("cjfm_login_info_widget_widget");'));


function cjfm_login_info_widget_display($vars, $widget_params = null){
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

	$display_name = '';
	$display = '';
	$title = '';
	$message = '';
	$show = '';

	if(is_user_logged_in()){
		global $current_user;
		$display_name = cjfm_user_info($current_user->ID, 'display_name');
		$title = str_replace('%%display_name%%', $display_name, strip_tags($user_title));
		$message = do_shortcode( $user_msg );
		$show = (is_null($show_user)) ? 'yes' : $show_user;
	}else{
		$title = strip_tags($visitor_title);
		$message = do_shortcode( $visitor_msg );
		$show = (is_null($show_visitor)) ? 'yes' : $show_visitor;
	}

	
	$display[] = $widget_params['before_widget'];
	$display[] = $widget_params['before_title'];
	$display[] = $title;
	$display[] = $widget_params['after_title'];
	$display[] = $message;
	$display[] = $widget_params['after_widget'];

	if($show == 'yes'){
		return implode("\n", $display);
	}else{
		return;
	}
}
