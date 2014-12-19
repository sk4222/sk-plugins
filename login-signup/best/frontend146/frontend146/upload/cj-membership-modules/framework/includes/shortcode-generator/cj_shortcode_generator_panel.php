<?php
	global $shortcode_tags;
	$select_shortcodes = null;
	$cj_products_text_domains = @array_keys(cjfm_list_submenu_admin_pages('cj-products'));
	if(!empty($shortcode_tags)){
		foreach ($shortcode_tags as $key => $value) {
			$tag = explode('_', $key);
			if(@in_array($tag[0], $cj_products_text_domains)){
				$select_shortcodes[$tag[0]][] = $value;
				$shortcode_options[$value] = @unserialize(cjfm_do_shortcode( '['.$value.' return="defaults"]' ));
			}
		}
	}
?>
<div class="cj-shortcode-generator-lightbox">

	<div class="cj-shortcode-generator">
		<h3 class="heading">
			<img src="<?php echo cjfm_item_path('includes_url').'/shortcode-generator/cj-icon.png'; ?>" />
			<?php _e('CSSJockey Shortcode Generator', 'cjfm') ?>
			<a href="#close" class="cj-shortcode-generator-close">&times;</a>
		</h3>
		<?php if(!is_null($select_shortcodes)): ?>
		<div class="cj-shortcode-footer clearfix">
			<div class="cj-shortcode-display-container">
				<div style="padding:10px;">
					<select id="cj-select-product">
						<option value="0"><?php _e('Select Product', 'cjfm') ?></option>
						<?php
							$cj_products = cjfm_list_submenu_admin_pages('cj-products');
							if(!empty($cj_products)){
								foreach ($cj_products as $key => $value) {
									if(in_array($key, array_keys($select_shortcodes))){
										echo '<option value="'.$key.'">'.$value.'</option>';
									}
								}	
							}
						?>
					</select>
					<?php
						if(!empty($select_shortcodes)){
							foreach ($select_shortcodes as $skey => $svalue) {
								echo '<select class="cj-shortcodes-dropdown '.$skey.'-shortcodes-dropdown">';
								echo '<option value="0">'.__('Select Shortcode', 'cjfm').'</option>';
								foreach ($svalue as $key => $value) {
									$display_value = str_replace($skey.'_', '', $value);
									$display_value = str_replace('_', ' ', $display_value);
									echo '<option value="'.$value.'">'.ucwords($display_value).'</option>';
								}
								echo '</select>';
							}
						}
						
					?>

					<span id="cj-shortcode-display"></span>
					<textarea id="cj-shortcode" rows="7" cols="50"></textarea>
				</div>
			</div>
			<div class="cj-shortcode-insert-container">
				<div style="padding:10px;">
					<input id="cj-insert-shortcode" type="button" value="Insert Shortcode" class="button-primary" />	
				</div>
			</div>
			
		</div>
		

		<div id="cj-shortcode-panel" class="clearfix">
			<div id="cj-shortcode-options"><div class="shortcode-option-field"><?php _e('Select product and shortcode.', 'cjfm') ?></div></div>
			<?php
				if (!empty($shortcode_options)) {
					
					foreach ($shortcode_options as $key => $shortcode_options_value) {

						$textdomain = explode('_', $key);

						$shortcode_title = ucwords(str_replace($textdomain[0].'_', '', $key));
						$shortcode_title = ucwords(str_replace("_", ' ', $shortcode_title));

						$shortcode_name = $key;

						$shortcode_description = (isset($shortcode_options_value['description'])) ? '<p class="cj-shortcode-description">'.$shortcode_options_value['description'].'</p>' : '';

						$shortcode_content = '<div class="shortcode-container-code"><code>';
						$shortcode_content .= '['.$shortcode_name;

						$default_content = (isset($shortcode_options_value['default_content'])) ? $shortcode_options_value['default_content'] : __('Content goes here..', 'cjfm');

						$sval = $shortcode_options_value;
						unset($sval['stype']);
						unset($sval['description']);
						unset($sval['heading']);
						unset($sval['paragraph']);
						unset($sval['default_content']);

						foreach ($sval as $skey => $svalue) {
							$default_val = (!is_array($svalue[2])) ? $svalue[2] : '';
							$shortcode_content .= ' <span class="p-key">'.$skey.'</span>="<span class="'.$skey.' p-val">'.$default_val.'</span>"';
						}
						$shortcode_content .= ($shortcode_options_value['stype'] == 'single') ? ']' : ']'.$default_content.'[/'.$shortcode_name.']';
						$shortcode_content .= '</code></div>';



						echo '<div id="cj-shortcode-'.$key.'" class="cj-shortcode-form">';
						echo '<h3 class="heading">'.$shortcode_title.'</h3>';
						echo $shortcode_description;
						echo '<form class="shortcode-options-form" action="" method="post">';
						echo $shortcode_content;
						echo cjfm_shortcode_form($shortcode_options_value);
						echo '</form>';
						echo '</div>';
					}
				}
			?>
			<br><br><br><br><br><br><br>
		</div><!-- cj-shortcodes-panel -->

		<?php else: ?>
			<p style="margin:10px;"><?php _e('No shortcodes available.', 'cjfm') ?></p>
		<?php endif; ?>

	</div><!-- cj-shortcode-generator -->

</div>