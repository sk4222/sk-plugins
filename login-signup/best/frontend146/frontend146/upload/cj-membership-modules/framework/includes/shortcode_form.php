<?php 

$uid = cjfm_unique_string().'-';

$display = '';

foreach ($options as $key => $value) {
	if($value[1] == 'heading'){
		$display[] = '<div class="shortcode-option-field clearfix">';
		$display[] = '<h3 class="heading">'.$value[0].'</h3>';
		$display[] = '</div>';
	}
	if($value[1] == 'paragraph'){
		$display[] = '<div class="shortcode-option-field paragraph clearfix">';
		$display[] = $value[0];
		$display[] = '</div>';
	}
	
	if($value[1] == 'text'){
		$display[] = '<div class="shortcode-option-field paragraph clearfix">';
		$display[] = '<label for="'.$uid.$key.'">'.$value[0].'</label>';
		$display[] = '<input name="'.$key.'" id="'.$uid.$key.'" type="text" value="'.$value[2].'" />';
		$display[] = (isset($value[3])) ? '<div class="form-desc">'.$value[3].'</div>' : '';
		$display[] = '</div>';
	}
	if($value[1] == 'color'){
		$display[] = '<div class="shortcode-option-field paragraph clearfix">';
		$display[] = '<label for="'.$uid.$key.'">'.$value[0].'</label>';
		$display[] = '<input name="'.$key.'" id="'.$uid.$key.'" type="color" value="'.$value[2].'" />';
		$display[] = (isset($value[3])) ? '<div class="form-desc">'.$value[3].'</div>' : '';
		$display[] = '</div>';
	}
	if($value[1] == 'number'){
		$display[] = '<div class="shortcode-option-field paragraph clearfix">';
		$display[] = '<label for="'.$uid.$key.'">'.$value[0].'</label>';
		$display[] = '<input name="'.$key.'" id="'.$uid.$key.'" type="number" value="'.$value[2].'" />';
		$display[] = (isset($value[3])) ? '<div class="form-desc">'.$value[3].'</div>' : '';
		$display[] = '</div>';
	}
	if($value[1] == 'textarea'){
		$display[] = '<div class="shortcode-option-field paragraph clearfix">';
		$display[] = '<label for="'.$uid.$key.'">'.$value[0].'</label>';
		$display[] = '<textarea name="'.$key.'" id="'.$uid.$key.'" rows="5" cols="45">'.$value[2].'</textarea>';
		$display[] = (isset($value[3])) ? '<div class="form-desc">'.$value[3].'</div>' : '';
		$display[] = '</div>';
	}
	if($value[1] == 'dropdown' || $value[1] == 'select'){
		$options = '<option value="">'.__('Please select', 'cjfm').'</option>';
		foreach ($value[2] as $okey => $ovalue) {
			$options .= '<option value="'.$okey.'">'.$ovalue.'</option>';			
		}
		$display[] = '<div class="shortcode-option-field paragraph clearfix">';
		$display[] = '<label for="'.$uid.$key.'">'.$value[0].'</label>';
		$display[] = '<select name="'.$key.'" id="'.$uid.$key.'">'.$options.'</select>';
		$display[] = (isset($value[3])) ? '<div class="form-desc">'.$value[3].'</div>' : '';
		$display[] = '</div>';
	}

}