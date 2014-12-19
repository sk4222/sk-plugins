<?php 
global $wpdb;
$options_table = cjfm_item_info('options_table');

$cj_message = null;

$unique_key = sha1(serialize($options));

$form_submit = 'form_submit_'.$unique_key;

if ( empty($_POST) || !wp_verify_nonce('form_submit') ){

	if(isset($_POST[$form_submit])){

		foreach ($_POST as $key => $value) {
			if($key != '_wpnonce' && $key != '_wp_http_referer'){
				if (is_array($value)) {
					$update_value = serialize($value);
				} else {
					$update_value = $value;
				}
				//$wpdb->query("UPDATE {$options_table} SET option_value = '{$update_value}' WHERE option_name = '{$key}'");
				$wpdb->update( $options_table, array("option_value" => $update_value), array("option_name" => $key), array("%s"), array("%s") );
			}
		}
		$location = cjfm_string(cjfm_current_url()).'cjfm_options=saved';
		wp_redirect($location);
	}

}else{
	print __('Sorry, your nonce did not verify.', 'cjfm');
   	exit;
}


if(isset($_GET['cjfm_options']) && $_GET['cjfm_options'] == 'saved'){
	$cj_message = cjfm_message('success', __('Settings saved successfully.', 'cjfm'));
	$display[] = $cj_message;
}


$display[] = wp_nonce_field('form_submit');

$display[] = '<table class="enable-search" cellspacing="0" cellpadding="0" width="100%">';	
foreach ($options as $key => $option) {
	
	if($option['type'] == 'heading'){

		$settings_search_box = '
			<span class="settings-search-box">
				<input id="settings-search-box" name="settings-search-box" type="text" placeholder="search" />
				<i class="cj-icon icon-search"></i>
			</span>
		';

		$display[] = '<tr>';
		$display[] = '<th colspan="2">';
		$display[] = '<h2 class="main-heading">'.$option['default'].$settings_search_box.'</h2>';
		$display[] = '</th>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'sub-heading'){
		$display[] = '<tr class="searchable">';
		$display[] = '<th colspan="2">';
		$display[] = '<h2 id="'.$option['id'].'" class="sub-heading">'.$option['default'].'</h2>';
		$display[] = '</th>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'section-heading'){
		$display[] = '<tr class="searchable">';
		$display[] = '<th colspan="2">';
		$display[] = '<h2 class="section-heading">'.$option['default'].'</h2>';
		$display[] = '</th>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'hidden'){
		$display[] = '<tr class="cj-hidden hidden"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<input type="hidden" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'" value="'.$option['default'].'" />';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}	

	if($option['type'] == 'info-full'){
		$display[] = '<tr class="searchable info-full">';
		$display[] = '<td colspan="2" class="cj-panel">';
		$display[] = $option['default'];
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'info-highlight'){
		$display[] = '<tr class="searchable info-highlight">';
		$display[] = '<td colspan="2" class="cj-panel">';
		$display[] = $option['default'];
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'info'){
		$display[] = '<tr class="searchable info"><td class="cj-label">'.$option['label'].'</td>';
		$display[] = '<td class="cj-panel">';
		$display[] = $option['default'];
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'text' || $option['type'] == 'textbox'){
		if($option['suffix'] != ''){
			$suffix = $option['suffix'];
		}else{
			$suffix = '';
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<input type="text" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'" value="'.cjfm_get_option($option['id']).'" /> <span class="cj-suffix">'.$suffix.'</span>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'pass' || $option['type'] == 'password'){
		if($option['suffix'] != ''){
			$suffix = $option['suffix'];
		}else{
			$suffix = '';
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<input type="password" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'" value="'.cjfm_get_option($option['id']).'" /> <span class="cj-suffix">'.$suffix.'</span>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'number'){
		if($option['suffix'] != ''){
			$suffix = $option['suffix'];
		}else{
			$suffix = '';
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<input type="number" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'" value="'.cjfm_get_option($option['id']).'" /> <span class="cj-suffix">'.$suffix.'</span>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'textarea'){
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<textarea rows="5" cols="40" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'">'.cjfm_get_option($option['id']).'</textarea>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'code-css'){
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<textarea class="cj-code-css" rows="5" cols="40" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'">'.cjfm_get_option($option['id']).'</textarea>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'code-js'){
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<textarea class="cj-code-js" rows="5" cols="40" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'">'.cjfm_get_option($option['id']).'</textarea>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'dropdown' || $option['type'] == 'select'){
		$opts = null;
		foreach ($option['options'] as $okey => $ovalue) {
			if(cjfm_get_option($option['id']) == $okey){
				$opts[] = '<option selected value="'.$okey.'">'.$ovalue.'</option>';
			}else{
				$opts[] = '<option value="'.$okey.'">'.$ovalue.'</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'multidropdown' || $option['type'] == 'multiselect'){
		$opts = null;
		foreach ($option['options'] as $okey => $ovalue) {
			if(@in_array($okey, cjfm_get_option($option['id']))){
				$opts[] = '<option selected value="'.$okey.'">'.$ovalue.'</option>';
			}else{
				$opts[] = '<option value="'.$okey.'">'.$ovalue.'</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" multiple name="'.$option['id'].'[]" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'radio-inline'){
		$opts = null;
		foreach ($option['options'] as $okey => $ovalue) {
			if(cjfm_get_option($option['id']) == $okey){
				$opts[] = '<label for="'.$option['id'].'_'.$okey.'"><input checked type="radio" name="'.$option['id'].'" id="'.$option['id'].'_'.$okey.'" value="'.$okey.'" /> <span class="checkbox-span-fix">'.$ovalue.'</span> </label>';
			}else{
				$opts[] = '<label for="'.$option['id'].'_'.$okey.'"><input type="radio" name="'.$option['id'].'" id="'.$option['id'].'_'.$okey.'" value="'.$okey.'" /> <span class="checkbox-span-fix">'.$ovalue.'</span> </label>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel cj-checkbox">';
		$display[] = implode('', $opts);
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'radio'){
		$opts = null;
		foreach ($option['options'] as $okey => $ovalue) {
			if(cjfm_get_option($option['id']) == $okey){
				$opts[] = '<label class="cj-block" for="'.$option['id'].'_'.$okey.'"><input checked type="radio" name="'.$option['id'].'" id="'.$option['id'].'_'.$okey.'" value="'.$okey.'" /> <span class="checkbox-span-fix">'.$ovalue.'</span> </label>';
			}else{
				$opts[] = '<label class="cj-block" for="'.$option['id'].'_'.$okey.'"><input type="radio" name="'.$option['id'].'" id="'.$option['id'].'_'.$okey.'" value="'.$okey.'" /> <span class="checkbox-span-fix">'.$ovalue.'</span> </label>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel cj-checkbox">';
		$display[] = implode('', $opts);
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'checkbox-inline'){
		$opts = null;
		foreach ($option['options'] as $okey => $ovalue) {
			if(@in_array($okey, cjfm_get_option($option['id']))){
				$opts[] = '<label for="'.$option['id'].'_'.$okey.'"><input checked type="checkbox" name="'.$option['id'].'[]" id="'.$option['id'].'_'.$okey.'" value="'.$okey.'" /> <span class="checkbox-span-fix">'.$ovalue.'</span> </label>';
			}else{
				$opts[] = '<label for="'.$option['id'].'_'.$okey.'"><input type="checkbox" name="'.$option['id'].'[]" id="'.$option['id'].'_'.$okey.'" value="'.$okey.'" /> <span class="checkbox-span-fix">'.$ovalue.'</span> </label>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel cj-checkbox">';
		$display[] = implode('', $opts);
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'checkbox'){
		$opts = null;
		foreach ($option['options'] as $okey => $ovalue) {
			if(@in_array($okey, cjfm_get_option($option['id']))){
				$opts[] = '<label class="cj-block" for="'.$option['id'].'_'.$okey.'"><input checked type="checkbox" name="'.$option['id'].'[]" id="'.$option['id'].'_'.$okey.'" value="'.$okey.'" /> <span class="checkbox-span-fix">'.$ovalue.'</span> </label>';
			}else{
				$opts[] = '<label class="cj-block" for="'.$option['id'].'_'.$okey.'"><input type="checkbox" name="'.$option['id'].'[]" id="'.$option['id'].'_'.$okey.'" value="'.$okey.'" /> <span class="checkbox-span-fix">'.$ovalue.'</span> </label>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel cj-checkbox">';
		$display[] = implode('', $opts);
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'page'){
		$opts = null;
		$paggs = get_pages(array('sort_column' => 'post_title', 'sort_order' => 'ASC','post_type' => 'page','post_status' => 'publish', 'posts_per_page' => '10000'));
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(cjfm_get_option($option['id']) == $ovalue->ID){
				$opts[] = '<option selected value="'.$ovalue->ID.'">'.$ovalue->post_title.'</option>';
			}else{
				$opts[] = '<option value="'.$ovalue->ID.'">'.$ovalue->post_title.'</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'pages'){
		$opts = null;
		$paggs = get_pages(array('sort_column' => 'post_title', 'sort_order' => 'ASC','post_type' => 'page','post_status' => 'publish', 'posts_per_page' => '10000'));
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(@in_array($ovalue->ID, cjfm_get_option($option['id']))){
				$opts[] = '<option selected value="'.$ovalue->ID.'">'.$ovalue->post_title.'</option>';
			}else{
				$opts[] = '<option value="'.$ovalue->ID.'">'.$ovalue->post_title.'</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" multiple name="'.$option['id'].'[]" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'post'){
		$opts = null;
		$paggs = get_posts(array('sort_column' => 'post_title', 'sort_order' => 'ASC', 'post_type' => 'post','post_status' => 'publish', 'posts_per_page' => '10000'));
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(cjfm_get_option($option['id']) == $ovalue->ID){
				$opts[] = '<option selected value="'.$ovalue->ID.'">'.$ovalue->post_title.'</option>';
			}else{
				$opts[] = '<option value="'.$ovalue->ID.'">'.$ovalue->post_title.'</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'post_type'){
		$opts = null;
		$paggs = get_post_types();
		unset($paggs['revision']);
		unset($paggs['nav_menu_item']);
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if($okey == cjfm_get_option($option['id'])){
				$opts[] = '<option selected value="'.$okey.'">'.$ovalue.'</option>';
			}else{
				$opts[] = '<option value="'.$okey.'">'.$ovalue.'</option>';
			}
		}
		$display[] = '<tr id="'.$option['id'].'" class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'post_types'){
		$opts = null;
		$paggs = get_post_types();
		unset($paggs['revision']);
		unset($paggs['nav_menu_item']);
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		if(!empty($paggs)){
			foreach ($paggs as $okey => $ovalue) {
				if(@in_array($okey, cjfm_get_option($option['id']))){
					$opts[] = '<option selected value="'.$okey.'">'.$ovalue.'</option>';
				}else{
					$opts[] = '<option value="'.$okey.'">'.$ovalue.'</option>';
				}
			}
		}else{
			$opts[] = '<option value="">'.__('No custom post types found', 'cjfm').'</option>';
		}
		$display[] = '<tr id="'.$option['id'].'" class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" multiple name="'.$option['id'].'[]" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'posts'){
		$opts = null;
		$paggs = get_posts(array('sort_column' => 'post_title', 'sort_order' => 'ASC','post_type' => 'post','post_status' => 'publish', 'posts_per_page' => '10000'));
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(@in_array($ovalue->ID, cjfm_get_option($option['id']))){
				$opts[] = '<option selected value="'.$ovalue->ID.'">'.$ovalue->post_title.'</option>';
			}else{
				$opts[] = '<option value="'.$ovalue->ID.'">'.$ovalue->post_title.'</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" multiple name="'.$option['id'].'[]" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'category'){
		$opts = null;
		$paggs = get_categories( array('type' => 'post', 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => 0, 'taxonomy' => 'category', 'number' => 10000) );
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(cjfm_get_option($option['id']) == $ovalue->term_id){
				$opts[] = '<option selected value="'.$ovalue->term_id.'">'.$ovalue->cat_name.'</option>';
			}else{
				$opts[] = '<option value="'.$ovalue->term_id.'">'.$ovalue->cat_name.'</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'categories'){
		$opts = null;
		$paggs = get_categories( array('type' => 'post', 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => 0, 'taxonomy' => 'category', 'number' => 10000) );
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(@in_array($ovalue->term_id, cjfm_get_option($option['id']))){
				$opts[] = '<option selected value="'.$ovalue->term_id.'">'.$ovalue->cat_name.'</option>';
			}else{
				$opts[] = '<option value="'.$ovalue->term_id.'">'.$ovalue->cat_name.'</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" multiple name="'.$option['id'].'[]" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}


	if($option['type'] == 'taxonomy'){
		$opts = null;
		$taxonomies = get_taxonomies();
		$exclude_taxonomies = array('category', 'post_tag', 'nav_menu', 'link_category', 'post_format');
		$terms = '';
		foreach ($taxonomies as $key => $taxonomy) {
			if(!in_array($key, $exclude_taxonomies)){
				$taxonomy_array[] = $key;
			}
		}
		$paggs = get_terms($taxonomy_array, array('orderby' => 'name'));
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(cjfm_get_option($option['id']) == $ovalue->slug.'~~~~'.$ovalue->taxonomy){
				$opts[] = '<option selected value="'.$ovalue->slug.'~~~~'.$ovalue->taxonomy.'">'.$ovalue->name.' ('.$ovalue->taxonomy.')</option>';
			}else{
				$opts[] = '<option value="'.$ovalue->slug.'~~~~'.$ovalue->taxonomy.'">'.$ovalue->name.' ('.$ovalue->taxonomy.')</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}



	if($option['type'] == 'taxonomies'){
		$opts = null;
		$taxonomies = get_taxonomies();
		$exclude_taxonomies = array('category', 'post_tag', 'nav_menu', 'link_category', 'post_format');
		$terms = '';
		$taxonomy_array = null;
		foreach ($taxonomies as $key => $taxonomy) {
			if(!in_array($key, $exclude_taxonomies)){
				if(!isset($option['specific'])){
					$taxonomy_array[] = $key;
				}else{
					if($key == $option['specific']){
						$taxonomy_array[] = $key;	
					}
				}
			}
		}
		$paggs = get_terms($taxonomy_array, array('orderby' => 'name'));
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(is_array(cjfm_get_option($option['id']))){
				if(@in_array($ovalue->slug.'~~~~'.$ovalue->taxonomy, cjfm_get_option($option['id']))){
					$opts[] = '<option selected value="'.$ovalue->slug.'~~~~'.$ovalue->taxonomy.'">'.$ovalue->name.' ('.$ovalue->taxonomy.')</option>';
				}else{
					$opts[] = '<option value="'.$ovalue->slug.'~~~~'.$ovalue->taxonomy.'">'.$ovalue->name.' ('.$ovalue->taxonomy.')</option>';
				}
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" multiple name="'.$option['id'].'[]" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}
	

	if($option['type'] == 'tag'){
		$opts = null;
		$paggs = get_tags();
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(cjfm_get_option($option['id']) == $ovalue->slug){
				$opts[] = '<option selected value="'.$ovalue->slug.'">'.$ovalue->name.'</option>';
			}else{
				$opts[] = '<option value="'.$ovalue->slug.'">'.$ovalue->name.'</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'tags'){
		$opts = null;
		$paggs = get_tags();
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(@in_array($ovalue->slug, cjfm_get_option($option['id']))){
				$opts[] = '<option selected value="'.$ovalue->slug.'">'.$ovalue->name.'</option>';
			}else{
				$opts[] = '<option value="'.$ovalue->slug.'">'.$ovalue->name.'</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" multiple name="'.$option['id'].'[]" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'role'){
		$opts = null;
		global $wp_roles;
		$paggs = $wp_roles->role_names;
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(cjfm_get_option($option['id']) == $okey){
				$opts[] = '<option selected value="'.$okey.'">'.$ovalue.'</option>';
			}else{
				$opts[] = '<option value="'.$okey.'">'.$ovalue.'</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'roles'){
		$opts = null;
		global $wp_roles;
		$paggs = $wp_roles->role_names;
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(@in_array($okey, cjfm_get_option($option['id'])) || $okey == 'administrator'){
				$opts[] = '<option selected value="'.$okey.'">'.$ovalue.'</option>';
			}else{
				$opts[] = '<option value="'.$okey.'">'.$ovalue.'</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" multiple name="'.$option['id'].'[]" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'user'){
		$opts = null;
		global $wp_roles;
		$paggs = get_users(array('orderby' => 'display_name', 'blog_id' => $GLOBALS['blog_id']));
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(cjfm_get_option($option['id']) == $okey){
				$opts[] = '<option selected value="'.$ovalue->ID.'">'.$ovalue->display_name.' ('.$ovalue->user_email.')</option>';
			}else{
				$opts[] = '<option value="'.$ovalue->ID.'">'.$ovalue->display_name.' ('.$ovalue->user_email.')</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'users'){
		$opts = null;
		$paggs = get_users(array('orderby' => 'display_name', 'blog_id' => $GLOBALS['blog_id']));
		$opts[] = '<option value="0">'.__('None', 'cjfm').'</option>';
		foreach ($paggs as $okey => $ovalue) {
			if(@in_array($ovalue->ID, cjfm_get_option($option['id']))){
				$opts[] = '<option selected value="'.$ovalue->ID.'">'.$ovalue->display_name.' ('.$ovalue->user_email.')</option>';
			}else{
				$opts[] = '<option value="'.$ovalue->ID.'">'.$ovalue->display_name.' ('.$ovalue->user_email.')</option>';
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<select class="chzn-select-no-results" multiple name="'.$option['id'].'[]" id="'.sanitize_title( $option['id'] ).'">';
		$display[] = implode('', $opts);
		$display[] = '</select>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'date'){
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<input type="text" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'" value="'.cjfm_get_option($option['id']).'" class="date" />';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'color'){
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<input type="text" name="'.$option['id'].'" id="'.sanitize_title( $option['id'] ).'" value="'.cjfm_get_option($option['id']).'" data-color="'.cjfm_get_option($option['id']).'" class="color-picker" /> <span class="color-hex"><code>'.cjfm_get_option($option['id']).'</code></span>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'slider'){
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '
						<input name="'.$option['id'].'" id="cj-slider-'.$option['id'].'" class="cj-slider-input" type="text" value="'.cjfm_get_option( $option['id'] ).'" />
						<div class="cj-slider" id="'.sanitize_title( $option['id'] ).'"></div>

						<script>
						$(function() {
						  $( "#'.$option['id'].'" ).slider({
						    range: "max",
						    min: '.$option['min'].',
						    max: '.$option['max'].',
						    value: '.cjfm_get_option( $option['id'] ).',
						    slide: function( event, ui ) {
						      $( "#cj-slider-'.$option['id'].'" ).val( ui.value );
						    }
						  });
						  //$( "#cj-slider-'.$option['id'].'" ).val( $( "#cj-slider" ).slider( "value" ) );
						});
						</script>
					 ';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'file'){
		$opts = '';
		$saved_files = cjfm_get_option($option['id']);
		if(is_array($saved_files)){
			foreach ($saved_files as $key => $saved_file) {
				if($saved_file != ''){
					$opts .= '<div class="file-list">';
					$opts .= '<a href="'.$saved_file.'" target="_blank">'.basename($saved_file).'</a>';
					$opts .= '<input type="hidden" name="'.$option['id'].'[]" value="'.$saved_file.'" />';
					$opts .= '<a href="#" class="cj-remove-file"><i class="icon-remove"></i></a>';
					$opts .= '</div>';	
				}
			}
		}

		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<div class="cj-file-upload">';
		$display[] = '<a data-form-id="'.$form_submit.'" href="#" title="" class="cj-upload-file button-secondary">'.__('Upload File', 'cjfm').'</a>';
		$display[] = '<input type="hidden" name="'.$option['id'].'" value="" />';
		$display[] = '<div data-id="'.$option['id'].'" class="uploaded-file sortables clearfix">'.$opts.'</div>';
		$display[] = '</div>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'files'){

		$opts = '';
		$saved_files = cjfm_get_option($option['id']);
		if(is_array($saved_files)){
			foreach ($saved_files as $key => $saved_file) {
				if($saved_file != ''){
					$opts .= '<div class="file-list clearfix">';
					$opts .= '<a href="'.$saved_file.'" target="_blank">'.basename($saved_file).'</a>';
					$opts .= '<input type="hidden" name="'.$option['id'].'[]" value="'.$saved_file.'" />';
					$opts .= '<a href="#" class="cj-remove-file"><i class="icon-remove"></i></a>';
					$opts .= '</div>';	
				}
			}
		}

		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<div class="cj-file-upload">';
		$display[] = '<a data-form-id="'.$form_submit.'" href="#" title="" class="cj-upload-files button-secondary">'.__('Upload Files', 'cjfm').'</a>';
		$display[] = '<input type="hidden" name="'.$option['id'].'" value="" />';
		$display[] = '<div data-id="'.$option['id'].'" class="uploaded-files sortables clearfix">'.$opts.'</div>';
		$display[] = '</div>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'wysiwyg'){

		$editor_id = str_replace('-', '', str_replace('_', '', strtolower($option['id'])));
		$editor_settings = array(
			'wpautop' => false,
			'media_buttons' => true,
			'textarea_name' => $option['id'],
			'textarea_rows' => 12,
			'teeny' => false,
		);
		ob_start();
		wp_editor(cjfm_get_option( $option['id'] ), $editor_id, $editor_settings);
		$editor_panel = ob_get_clean();

		$display[] = '<tr class="searchable" class="cj-wysiwyg"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<div class="cj-panel-editor">'.$editor_panel.'</div>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'screenshots'){
		$opts = '';
		foreach ($option['options'] as $key => $value) {
			if($key == cjfm_get_option($option['id'])){
				$opts .= '<div class="cj-screenshot checked"><label><input checked type="radio" name="'.$option['id'].'" class="cj-hidden" value="'.$key.'" /> <img src="'.$value.'" alt="" /></label><span class="checked-img"></span><br /><p class="screenshot-name">'.str_replace('_', ' ', $key).'</p><span class="check-icon"><i class="icon-ok white"></i></span></div>';
			}else{
				$opts .= '<div class="cj-screenshot"><label><input type="radio" name="'.$option['id'].'" class="cj-hidden" value="'.$key.'" /> <img src="'.$value.'" alt="" /></label><span class="checked-img"></span><br /><p class="screenshot-name">'.str_replace('_', ' ', $key).'</p><span class="check-icon"><i class="icon-white cj-icon-checkmark-5"></i></span></div>';
			}	
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<div class="cj-screenshots">';
		$display[] = $opts;
		$display[] = '</div>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'textures'){
		$opts = '';
		foreach ($option['options'] as $key => $value) {
			if($value == cjfm_get_option( $option['id'] )){
				$opts .= '<label class="texture checked" for="label-'.$key.'" class="texture" style="background:#ccc url('.$value.') repeat;"><input class="hidden"  checked id="label-'.$key.'" name="'.$option['id'].'" value="'.$value.'" type="radio" /></label>';				
			}else{
				$opts .= '<label class="texture" for="label-'.$key.'" class="texture" style="background:#ccc url('.$value.') repeat;"><input class="hidden" id="label-'.$key.'" name="'.$option['id'].'" value="'.$value.'" type="radio" /></label>';				
			}
		}
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<div class="cj-textures">';
		$display[] = $opts;
		$display[] = '</div>';
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}


	if($option['type'] == 'font'){

		$google_fonts_array = cjfm_get_option( 'google_fonts' );

		$google_fonts['inherit'] = 'Inherit';
		$google_fonts['Arial'] = 'Arial';
		$google_fonts['Arial Black'] = 'Arial Black';
		$google_fonts['Helvetica'] = 'Helvetica';
		$google_fonts['Georgia'] = 'Georgia';
		$google_fonts['Trebuchet MS'] = 'Trebuchet MS';
		$google_fonts['Verdana'] = 'Verdana';
		$google_fonts['Palatino Linotype'] = 'Palatino Linotype';
		$google_fonts['Book Antiqua'] = 'Book Antiqua';
		$google_fonts['Palatino'] = 'Palatino';
		$google_fonts['Times New Roman'] = 'Times New Roman';
		$google_fonts['Times'] = 'Times';
		$google_fonts['Gadget'] = 'Gadget';
		$google_fonts['Comic Sans MS'] = 'Comic Sans MS';
		$google_fonts['Impact'] = 'Impact';
		$google_fonts['Charcoal'] = 'Charcoal';
		$google_fonts['Lucida Sans Unicode'] = 'Lucida Sans Unicode';
		$google_fonts['Lucida Grande'] = 'Lucida Grande';
		$google_fonts['Geneva'] = 'Geneva';
		$google_fonts['Courier New'] = 'Courier New';
		$google_fonts['Courier'] = 'Courier';
		$google_fonts['Lucida Console'] = 'Lucida Console';
		$google_fonts['Monaco'] = 'Monaco';
		$google_fonts['monospace'] = 'monospace';

		foreach ($google_fonts_array as $key => $value) {
			$google_fonts[$key] = $key;	
		}

		
		$saved_font = cjfm_get_option($option['id']);
		
		$font_weight_array = array(
			'inherit' => __('Inherit', 'cjfm'),
			'bold' => __('Bold', 'cjfm'),
			'bolder' => __('Bolder', 'cjfm'),
			'normal' => __('Normal', 'cjfm'),
			'lighter' => __('Lighter', 'cjfm'),
			'100' => __('100', 'cjfm'),
			'200' => __('200', 'cjfm'),
			'300' => __('300', 'cjfm'),
			'400' => __('400', 'cjfm'),
			'500' => __('500', 'cjfm'),
			'600' => __('600', 'cjfm'),
			'700' => __('700', 'cjfm'),
			'800' => __('800', 'cjfm'),
			'900' => __('900', 'cjfm'),
		);

		$font_style_array = array(
			'inherit' => __('Inherit', 'cjfm'),
			'italic' => __('Italic', 'cjfm'),
			'oblique' => __('Oblique', 'cjfm'),
			'normal' => __('Normal', 'cjfm'),
			
		);

		$font_family = '';
		foreach ($google_fonts as $key => $value) {
			if($saved_font['family'] == $key){
				$font_family .= '<option selected value="'.$key.'">'.$key.'</option>';	
			}else{
				$font_family .= '<option value="'.$key.'">'.$key.'</option>';
			}
		}

		$font_weight = '';
		foreach ($font_weight_array as $key => $value) {
			if(isset($saved_font['weight']) && $saved_font['weight'] == $key){
				$font_weight .= '<option selected value="'.$key.'">'.$key.'</option>';	
			}else{
				$font_weight .= '<option value="'.$key.'">'.$key.'</option>';
			}
		}

		$font_size = '';
		$font_size .= '<option selected value="inherit">'.__('Inherit', 'cjfm').'</option>';
		for ($i=8; $i < 37; $i++) { 
			if(!($i % 2)){
				if(isset($saved_font['size']) && $saved_font['size'] == $i.'pt'){
					$font_size .= '<option selected value="'.$i.'pt">'.$i.'pt</option>';
				}else{
					$font_size .= '<option value="'.$i.'pt">'.$i.'pt</option>';
				}
			}
		}

		$font_style = '';
		foreach ($font_style_array as $key => $value) {
			if(isset($saved_font['style']) && $saved_font['style'] == $key){
				$font_style .= '<option selected value="'.$key.'">'.$key.'</option>';	
			}else{
				$font_style .= '<option value="'.$key.'">'.$value.'</option>';
			}
		}
		
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		
		$display[] = '<p><label style="display:inline-block; width:50px;" for="'.$option['id'].'-family">'.__('Family:', 'cjfm').'</label>';
		$display[] = '<select name="'.$option['id'].'[family]" id="'.$option['id'].'-family" class="chzn-select-no-results" style="margin-right:10px;">';
		$display[] = $font_family;
		$display[] = '</select></p>';

		$display[] = '<p><label style="display:inline-block; width:50px;" for="'.$option['id'].'-weight">'.__('Weight:', 'cjfm').'</label>';
		$display[] = '<select name="'.$option['id'].'[weight]" id="'.$option['id'].'-weight" class="chzn-select-no-results" style="margin-right:10px;">';
		$display[] = $font_weight;
		$display[] = '</select></p>';

		$display[] = '<p><label style="display:inline-block; width:50px;" for="'.$option['id'].'-size">'.__('Size:', 'cjfm').'</label>';
		$display[] = '<select name="'.$option['id'].'[size]" id="'.$option['id'].'-size" class="chzn-select-no-results" style="margin-right:10px;">';
		$display[] = $font_size;
		$display[] = '</select></p>';
		
		$display[] = '<p><label style="display:inline-block; width:50px;" for="'.$option['id'].'-style">'.__('Style:', 'cjfm').'</label>';
		$display[] = '<select name="'.$option['id'].'[style]" id="'.$option['id'].'-style" class="chzn-select-no-results" style="margin-right:10px;">';
		$display[] = $font_style;
		$display[] = '</select></p>';
		
		$display[] = '<div class="cj-info">'.$option['info'].'</div>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	
	if($option['type'] == 'sortable'){
		$display[] = '<tr class="searchable"><td class="cj-label"><label for="'.sanitize_title( $option['id'] ).'">'.$option['label'].'</label></td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<ul id="'.$option['id'].'">';
		$opts = null;
		if(cjfm_get_option($option['id']) != ''){
			$opts = cjfm_sortArrayByArray($option['options'], cjfm_get_option($option['id']));
		}else{
			$opts = $option['options'];
		}
		foreach ($opts as $key => $value) {
			$display[] = '<li class="ui-state-default padding-10" style="background:#f7f7f7;">'.$value.' <input type="hidden" name="'.$option['id'].'[]" value="'.$key.'" /> </li>';
		}	
		$display[] = '</ul>
		<script>
		  jQuery(function() {
		    jQuery( "#'.$option['id'].'" ).sortable();
		    jQuery( "#'.$option['id'].'" ).disableSelection();
		  });
		  </script>';
		$display[] = '</td>';
		$display[] = '</tr>';
	}	


	if($option['type'] == 'submit'){
		$display[] = '<tr class="cj-submit"><td class="cj-label">&nbsp;</td>';
		$display[] = '<td class="cj-panel">';
		$display[] = '<input type="hidden" name="form_message" value="'.$option['default'].'" />';
		$display[] = '<input type="submit" id="'.$form_submit.'" name="'.$form_submit.'" value="'.$option['label'].'" class="button-primary" />'.$option['suffix'];
		$display[] = '</td>';
		$display[] = '</tr>';
	}

	if($option['type'] == 'submit-full'){
		$display[] = '<tr class="cj-submit">';
		$display[] = '<td colspan="2" class="cj-panel">';
		$display[] = '<input type="hidden" name="form_message" value="'.$option['default'].'" />';
		$display[] = '<input type="submit" id="'.$form_submit.'" name="'.$form_submit.'" value="'.$option['label'].'" class="button-primary" />'.$option['suffix'];
		$display[] = '</td>';
		$display[] = '</tr>';
	}

}


$display[] = '</table>';


echo '<form action="'.admin_url('admin.php?page='.cjfm_item_info('page_slug').'&callback='.@$_GET['callback'].'').'" method="post" enctype="multipart/form-data">';
echo implode('', $display);
echo '</form>';


function cjfm_sortArrayByArray(Array $array, Array $orderArray) {
    $ordered = array();
    foreach($orderArray as $key) {
        if(array_key_exists($key,$array)) {
            $ordered[$key] = $array[$key];
            unset($array[$key]);
        }
    }
    return $ordered + $array;
}