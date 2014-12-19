<?php 
$display[] = '';
foreach ($options as $key => $option) {

	$params = '';
	if(isset($option['params'])){
		foreach ($option['params'] as $key => $value) {
			$params .= ' '.$key.'="'.$value.'" ';
		}
	}

	if($option['type'] == 'heading'){
		$display[] = '<h2 id="'.$option['id'].'" class="cjfm-heading">'.$option['default'].'</h2>';
	}

	if($option['type'] == 'paragraph'){
		$display[] = '<p id="'.$option['id'].'" class="cjfm-paragraph">'.$option['default'].'</p>';
	}

	if($option['type'] == 'custom_html'){
		$display[] = '<div id="'.$option['id'].'" class="cjfm-custom-html">'.$option['default'].'</div>';
	}

	if($option['type'] == 'html_tag'){
		$display[] = $option['default'];
	}

	if($option['type'] == 'sub-heading'){
		$display[] = '<h2 id="'.$option['id'].'" class="sub-heading">'.$option['default'].'</h2>';
	}

	if($option['type'] == 'info'){ 
		$display[] = '<p id="'.$option['id'].'" class="info">'.$option['default'].'</p>';
	}

	if($option['type'] == 'hidden'){ 
		$display[] = '<input type="hidden" name="'.$option['id'].'" id="'.$option['id'].'" value="'.$option['default'].'" '.$params.'>';
	}


	if($option['type'] == 'text'){ 
		$display[] = '<div class="control-group '.@$option['class'].' textbox">';
		$display[] = '<label class="control-label" for="'.$option['id'].'">'.$option['label'].'</label>';
		$display[] = '<div class="controls">';
		$display[] = $option['prefix'].'<input type="text" name="'.$option['id'].'" id="'.$option['id'].'" value="'.$option['default'].'" '.$params.'>'.$option['suffix'];
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';
	}

	if($option['type'] == 'email'){ 
		$display[] = '<div class="control-group '.@$option['class'].' email">';
		$display[] = '<label class="control-label" for="'.$option['id'].'">'.$option['label'].'</label>';
		$display[] = '<div class="controls">';
		$display[] = $option['prefix'].'<input type="email" name="'.$option['id'].'" id="'.$option['id'].'" value="'.$option['default'].'" '.$params.'>'.$option['suffix'];
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';
	}

	if($option['type'] == 'text-readonly'){ 
		$display[] = '<div class="control-group '.@$option['class'].' textbox">';
		$display[] = '<label class="control-label" for="'.$option['id'].'">'.$option['label'].'</label>';
		$display[] = '<div class="controls">';
		$display[] = $option['prefix'].'<input type="text" readonly name="'.$option['id'].'" id="'.$option['id'].'" value="'.$option['default'].'" '.$params.'>'.$option['suffix'];
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';
	}

	if($option['type'] == 'upload' || $option['type'] == 'file'){ 
		$display[] = '<div class="control-group '.@$option['class'].' textbox">';
		$display[] = '<label class="control-label" for="'.$option['id'].'">'.$option['label'].'</label>';
		$display[] = '<div class="controls">';
		$display[] = '<input type="file" name="'.$option['id'].'" id="'.$option['id'].'" value="" '.$params.'>';
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';
	}

	if($option['type'] == 'uploads' || $option['type'] == 'files'){ 
		$display[] = '<div class="control-group '.@$option['class'].' textbox">';
		$display[] = '<label class="control-label" for="'.$option['id'].'">'.$option['label'].'</label>';
		$display[] = '<div class="controls">';
		$display[] = '<input type="file" name="'.$option['id'].'[]" id="'.$option['id'].'" value="" '.$params.' multiple>';
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';
	}

	if($option['type'] == 'password'){ 
		$display[] = '<div class="control-group '.@$option['class'].'  password">';
		$display[] = '<label class="control-label" for="'.$option['id'].'">'.$option['label'].'</label>';
		$display[] = '<div class="controls">';
		$display[] = $option['prefix'].'<input type="password" name="'.$option['id'].'" id="'.$option['id'].'" value="'.$option['default'].'" '.$params.'>'.$option['suffix'];
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';
	}


	if($option['type'] == 'textarea'){
		$display[] = '<div class="control-group '.@$option['class'].'  textarea">';
		$display[] = '<label class="control-label" for="'.$option['id'].'">'.$option['label'].'</label>';
		$display[] = '<div class="controls">';
		$display[] = $option['prefix'].'<textarea name="'.$option['id'].'" id="'.$option['id'].'" rows="5" cols="40" '.$params.'>'.$option['default'].'</textarea>'.$option['suffix'];
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';
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
		wp_editor($option['default'], $editor_id, $editor_settings);
		$editor_panel = ob_get_clean();

		$display[] = '<div class="control-group '.@$option['class'].'  wysiwyg">';
		$display[] = '<label class="control-label" for="'.$option['id'].'">'.$option['label'].'</label>';
		$display[] = '<div class="controls">';
		$display[] = '<div class="cj-panel-editor">'.$editor_panel.'</div>';
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';

	}

	if($option['type'] == 'dropdown' || $option['type'] == 'select'){ 
		$opts = '';
		if(is_array($option['options'])){
			foreach ($option['options'] as $key => $opt) {
				if($option['default'] == strip_tags($key)){
					$opts .= '<option selected value="'.strip_tags($key).'">'.$opt.'</option>';
				}else{
					$opts .= '<option value="'.strip_tags($key).'">'.$opt.'</option>';
				}
			}
		}
		$display[] = '<div class="control-group '.@$option['class'].'  select">';
		$display[] = '<label class="control-label" for="'.$option['id'].'">'.$option['label'].'</label>';
		$display[] = '<div class="controls">';
		$display[] = $option['prefix'];
		$display[] = '<select name="'.$option['id'].'" id="'.$option['id'].'" '.$params.'>';
		$display[] = $opts;
		$display[] = '</select>';
		$display[] = $option['suffix'];
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';
	}

	if($option['type'] == 'multidropdown' || $option['type'] == 'multiselect'){ 
		$opts = '';
		if(is_array($option['options'])){
			foreach ($option['options'] as $key => $opt) {
				$format_option_defaults = (is_serialized($option['default'])) ? unserialize($option['default']) : $option['default'];
				if(@in_array(strip_tags($key), $format_option_defaults )){
					$opts .= '<option selected value="'.strip_tags($key).'">'.$opt.'</option>';
				}else{
					$opts .= '<option value="'.strip_tags($key).'">'.$opt.'</option>';
				}
			}
		}
		$display[] = '<div class="control-group '.@$option['class'].'  select">';
		$display[] = '<label class="control-label" for="'.$option['id'].'">'.$option['label'].'</label>';
		$display[] = '<div class="controls">';
		$display[] = $option['prefix'];
		$display[] = '<select multiple name="'.$option['id'].'[]" id="'.$option['id'].'" '.$params.'>';
		$display[] = $opts;
		$display[] = '</select>';
		$display[] = $option['suffix'];
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';
	}

	if($option['type'] == 'radio' || $option['type'] == 'radio-inline'){ 
		$opts = '';
		$inline = ($option['type'] == 'radio-inline') ? 'inline' : '';
		if(is_array($option['options'])){
			foreach ($option['options'] as $key => $opt) {
				if($option['default'] == strip_tags($key)){
					$opts .= '<label class="checkbox '.$inline.'"> <input checked type="radio" id="'.$option['id'].'" name="'.$option['id'].'" value="'.strip_tags($key).'"> '.stripcslashes($opt).'</label>';
				}else{
					$opts .= '<label class="checkbox '.$inline.'"> <input type="radio" id="'.$option['id'].'" name="'.$option['id'].'" value="'.strip_tags($key).'"> '.stripcslashes($opt).'</label>';
				}
			}
		}
		$display[] = '<div class="control-group '.@$option['class'].'  radio-buttons">';
		$display[] = '<label class="control-label" for="'.$option['id'].'">'.$option['label'].'</label>';
		$display[] = '<div class="controls">';
		$display[] = $opts;
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';
	}

	if($option['type'] == 'checkbox' || $option['type'] == 'checkbox-inline'){ 
		$opts = '';
		$inline = ($option['type'] == 'checkbox-inline') ? 'inline' : '';
		if(is_array($option['options'])){
			foreach ($option['options'] as $key => $opt) {
				$format_option_defaults = (is_serialized($option['default'])) ? unserialize($option['default']) : $option['default'];
				if(@in_array(strip_tags($key), $format_option_defaults )){
					$opts .= '<label class="checkbox '.$inline.'"><input type="checkbox" id="'.$option['id'].'" name="'.$option['id'].'[]" value="'.strip_tags($key).'" checked>'.stripcslashes($opt).'</label>';
				}else{
					$opts .= '<label class="checkbox '.$inline.'"><input type="checkbox" id="'.$option['id'].'" name="'.$option['id'].'[]" value="'.strip_tags($key).'">'.stripcslashes($opt).'</label>';
				}
			}
		}
		$display[] = '<div class="control-group '.@$option['class'].'  checkbox">';
		$display[] = '<label class="control-label" for="'.$option['id'].'">'.$option['label'].'</label>';
		$display[] = '<div class="controls">';
		$display[] = $opts;
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';
	}

	if($option['type'] == 'submit'){ 
		$display[] = '<div class="control-group submit-button">';
		$display[] = '<div class="controls">';
		$display[] = $option['prefix'].'<button type="submit" name="'.$option['id'].'" id="'.$option['id'].'" class="submit '.@$option['class'].'" '.$params.'>'.$option['label'].'</button>'.$option['suffix'];
		$display[] = $option['info'];
		$display[] = '</div>';
		$display[] = '<div class="info">'.$option['info'].'</div>';
		$display[] = '</div>';
	}

}