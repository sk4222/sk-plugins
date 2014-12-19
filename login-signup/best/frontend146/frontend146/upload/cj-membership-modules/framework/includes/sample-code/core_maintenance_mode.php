<?php
global $cjfm_item_options;
$cjfm_item_options['core_maintenance_mode'] = array(
	array(
		'type' => 'heading',
		'id' => 'maintenance_mode_heading',
		'label' => '',
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Maintenance Mode', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'radio-inline',
		'id' => 'maintenance_mode_status',
		'label' => __('Maintenance Mode', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => 'enable',
		'options' => array('enable' => __('Enable', 'cjfm'), 'disable' => __('Disable', 'cjfm')), // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'wysiwyg',
		'id' => 'maintenance_mode_content',
		'label' => __('Page Content', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('<h2>We will be right back</h2> <p>We are making some changes to our website.</p>', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'sub-heading',
		'id' => 'page_styles',
		'label' => __('Page Styles', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => __('Page Styles', 'cjfm'),
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'body_bg_color',
		'label' => __('Document Background Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#f7f7f7',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'body_text_color',
		'label' => __('Document Text Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#222222',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'body_link_color',
		'label' => __('Document Link Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#FFFFFF',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'panel_bg_color',
		'label' => __('Panel Background Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#ffffff',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'panel_text_color',
		'label' => __('Panel Text Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#222222',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'panel_link_color',
		'label' => __('Panel Link Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#000000',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'color',
		'id' => 'panel_border_color',
		'label' => __('Panel Border Color', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '#dddddd',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
	array(
		'type' => 'submit',
		'id' => 'core_maintenance_mode',
		'label' => __('Save Settings', 'cjfm'),
		'info' => '',
		'suffix' => '',
		'prefix' => '',
		'default' => '',
		'options' => '', // array in case of dropdown, checkbox and radio buttons
	),
);


function cjfm_maintenance_mode_page(){
	if(cjfm_get_option('maintenance_mode_status') == 'enable'){
		if(!current_user_can('manage_options' )){
			require_once(sprintf('%s/maintenance/index.php', cjfm_item_path('framework_dir')));
			die();
		}
	}
}
add_action('get_header', 'cjfm_maintenance_mode_page');

function test(){
	if(cjfm_get_option('maintenance_mode_status') == 'enable'){
		echo '<div class="error">
       			<p>
       				'.sprintf(__('Maintenance mode is active. <a href="%s">Manage Settings</a>', 'cjfm'), cjfm_callback_url('core_maintenance_mode')).'
       			</p>		
    		  </div>';
	}
}

add_action('admin_notices', 'test');


