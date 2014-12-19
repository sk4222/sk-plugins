<?php 
function cjfm_widget_form($options, $form_instance){
	if(is_array($options)){

		foreach ($options as $key => $option) {

			$id = $option['id'];
			
			if($option['type'] == 'text'){
				$display[] = '<p>';
				$display[] = '<label for="'.$form_instance[$id]['id'].'">'.$option['label'].'</label><br />';
				$display[] = '<input type="text" name="'.$form_instance[$id]['name'].'" id="'.$form_instance[$id]['id'].'" value="'.$form_instance[$id]['value'].'" style="width:100%;" />';
				$display[] = '<span style="display:block; margin-top:2px; margin-bottom:10px;">'.$option['info'].'</span>';
				$display[] = '</p>';
			}

			if($option['type'] == 'info'){
				$display[] = '<span style="display:block; margin-top:2px; margin-bottom:10px;">'.$option['info'].'</span>';
			}

			if($option['type'] == 'heading'){
				$display[] = '<h3 style="display:block; margin-top:0px; margin-bottom:10px;">'.$option['info'].'</h3>';
			}

			if($option['type'] == 'textgroup'){
				$opts = '';
				if(is_array($option['options'])){
					$saved_opts = explode('~~~~~', $form_instance[$id]['value']);
					foreach ($option['options'] as $okey => $ovalue) {
						$opts .= '<label for="'.$form_instance[$id]['name'].'">'.ucwords(str_replace('_', ' ', $ovalue)).'</label><br />';
						$opts .= '<input type="text" name="'.$form_instance[$id]['name'].'['.$okey.']" id="'.$form_instance[$id]['id'].'['.$okey.']" value="'.@$saved_opts[$okey].'" style="width:100%;" />';
						
					}
				}
				$display[] = '<p>';
				$display[] = '<label for="'.$form_instance[$id]['id'].'"><b>'.$option['label'].'</b></label><br />';
				$display[] = $opts;
				$display[] = '</p>';
			}


			if($option['type'] == 'textarea'){
				$display[] = '<p>';
				$display[] = '<label for="'.$form_instance[$id]['id'].'">'.$option['label'].'</label><br />';
				$display[] = '<textarea rows="4" cols="40" name="'.$form_instance[$id]['name'].'" id="'.$form_instance[$id]['id'].'" style="width:100%;">'.$form_instance[$id]['value'].'</textarea>';
				$display[] = '<span style="display:block; margin-top:2px; margin-bottom:10px;">'.$option['info'].'</span>';
				$display[] = '</p>';
			}


			if($option['type'] == 'dropdown' || $option['type'] == 'select'){
				$opts = '<option value="">'.sprintf(__('Select %s', 'cjfm'), $option['label']).'</option>';
				if(is_array($option['options'])){
					foreach ($option['options'] as $okey => $ovalue) {
						if($form_instance[$id]['value'] == $okey){
							$opts .= '<option selected value="'.$okey.'">'.$ovalue.'</option>';	
						}else{
							$opts .= '<option value="'.$okey.'">'.$ovalue.'</option>';
						}
						
					}
				}
				$display[] = '<p>';
				$display[] = '<label for="'.$form_instance[$id]['id'].'">'.$option['label'].'</label><br />';
				$display[] = '<select name="'.$form_instance[$id]['name'].'" id="'.$form_instance[$id]['id'].'" style="width:100%;">'.$opts.'</select>';
				$display[] = '<span style="display:block; margin-top:2px; margin-bottom:10px;">'.$option['info'].'</span>';
				$display[] = '</p>';
			}

			if($option['type'] == 'multidropdown' || $option['type'] == 'multiselect'){
				$opts = '<option value="">'.sprintf(__('Select %s', 'cjfm'), $option['label']).'</option>';
				if(is_array($option['options'])){
					$saved_opts = explode('~~~~~', $form_instance[$id]['value']);
					foreach ($option['options'] as $okey => $ovalue) {
						if(in_array($okey, $saved_opts)){
							$opts .= '<option selected value="'.$okey.'">'.$ovalue.'</option>';	
						}else{
							$opts .= '<option value="'.$okey.'">'.$ovalue.'</option>';
						}
						
					}
				}
				$display[] = '<p>';
				$display[] = '<label for="'.$form_instance[$id]['id'].'">'.$option['label'].'</label><br />';
				$display[] = '<select multiple name="'.$form_instance[$id]['name'].'[]" id="'.$form_instance[$id]['id'].'" style="width:100%;">'.$opts.'</select>';
				$display[] = '<span style="display:block; margin-top:2px; margin-bottom:10px;">'.$option['info'].'</span>';
				$display[] = '</p>';
			}

		}

	}else{
		$display[] = __('No options available for this widget.', 'cjfm');
	}

	return implode("\n", $display);
}