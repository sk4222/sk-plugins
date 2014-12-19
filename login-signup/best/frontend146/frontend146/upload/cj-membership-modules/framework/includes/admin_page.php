<div class="wrap">

	<h2><?php echo sprintf(__('%s Settings', 'cjfm'), ucwords(cjfm_item_info('item_name'))) ?></h2>

	<nav class="cjfm-dropdown clearfix">
		<ul>
			<li class="home"><a href="<?php echo cjfm_callback_url('core_welcome'); ?>" title=""><i class="icon-home"></i></a>
				<ul>
					<!-- <li><a href="<?php echo cjfm_callback_url('core_maintenance_mode'); ?>"><?php _e('Maintenance Mode', 'cjfm') ?></a></li> -->
					<li><a href="<?php echo cjfm_callback_url('core_upgrades'); ?>"><?php _e('Check for Upgrades', 'cjfm') ?></a></li>
					<li><a href="<?php echo cjfm_callback_url('core_import_export'); ?>"><?php _e('Backup &amp; Restore', 'cjfm') ?></a></li>
					<li><a href="<?php echo cjfm_callback_url('core_uninstall'); ?>"><?php _e('Uninstall', 'cjfm') ?></a></li>
				</ul>
			</li>
			<?php 
				$dropdown = cjfm_item_vars('dropdown');

				foreach ($dropdown as $key => $menu) {
					$li_id = $key;
					if(is_array($menu)){
						echo '<li id="'.$li_id.'" class="has-sub-menu"><a href="#" onclick="return false;" title="">'.ucwords(str_replace('_', ' ', $key)).' <i class="cj-icon icon-white cj-icon-caret-down"></i></a>';
						echo '<ul>';
						foreach ($menu as $skey => $sub_menu) {
							echo '<li id="'.$li_id.'"><a href="'.cjfm_callback_url($skey).'" title="">'.$sub_menu.'</a></li>';	
						}
						echo '</ul>';
						echo '</li>';
					}else{
						echo '<li id="'.$li_id.'"><a href="'.cjfm_callback_url($key).'" title="">'.$menu.'</a></li>';
					}
				}
				do_action('cjfm_dropdown_hook');
			?>
		</ul>
	</nav>

	<noscript class="no-script">
		<?php _e('Javascript must be enabled.', 'cjfm') ?>
	</noscript>

	<?php do_action('cjfm_message_hook'); ?>

	<div id="cj-admin-content" class="clearfix">
		<?php
			if(isset($_GET['page']) && isset($_GET['callback']) && $_GET['callback'] != ''){

				$callback = $_GET['callback'];

				$check_item_options_folder = file_exists(sprintf('%s/'.$callback.'.php', cjfm_item_path('options_dir')));
				$check_core_options_folder = file_exists(sprintf('%s/includes/options/'.$callback.'.php', cjfm_item_path('framework_dir')));


				if(!$check_item_options_folder && !$check_core_options_folder){
					echo cjfm_message('error', sprintf(__('<b>%s.php</b> not found in options directory.', 'cjfm'), $callback));
				}else{
					if($check_item_options_folder){
						require_once(sprintf('%s/'.$callback.'.php', cjfm_item_path('options_dir')));
						global $cjfm_item_options;
						if(!empty($cjfm_item_options) && !empty($cjfm_item_options[$callback])){
							cjfm_admin_form($cjfm_item_options[$callback]);
						}
					}elseif($check_core_options_folder){
						require_once(sprintf('%s/includes/options/'.$callback.'.php', cjfm_item_path('framework_dir')));
						global $cjfm_item_options;
						if(!empty($cjfm_item_options) && !empty($cjfm_item_options[$callback])){
							cjfm_admin_form($cjfm_item_options[$callback]);
						}
					}
				}

			}else{
				$location = cjfm_callback_url('core_welcome');
				wp_redirect( $location, $status = 302 );
				exit;
			}
		?>
	</div>

</div><!-- wrap -->