<?php 
	global $wp_registered_sidebars;


	$saved_sidebar_data = cjfm_get_option('dynamic_sidebars');
	// Add new sidebar
	if(isset($_POST['add_new_sidebar'])){
		if($_POST['name'] != ''){
			$sidebar_id = sanitize_title( $_POST['name'], 'sidebar-'.cjfm_unique_string() );
			$sidebar_name = $_POST['name'];
			$sidebar_class = $_POST['class'];
			$sidebar_description = $_POST['description'];

			$new_sidebar_data[$sidebar_id] = array(
				'id' => $sidebar_id,
				'name' => $sidebar_name,
				'description' => $sidebar_description,
				'class' => sanitize_title( $sidebar_class ),
			);

			if(!empty($saved_sidebar_data) && is_array($saved_sidebar_data)){
				$saved_sidebars = $saved_sidebar_data;
				$new_value = array_merge($saved_sidebars, $new_sidebar_data);
			}else{
				$new_value = $new_sidebar_data;
			}

			cjfm_update_option('dynamic_sidebars', $new_value);
			$location = cjfm_callback_url('theme_dynamic_sidebars');
			wp_redirect( $location, $status = 302 );
			exit;
		}else{
			echo cjfm_message('error', __('Sidebar Name is required.', 'cjfm'));
		}

	}

	// Edit Sidebar
	if(isset($_POST['edit_sidebar'])){
		if($_POST['name'] != ''){

			$id = $_GET['id'];
			unset($saved_sidebar_data[$id]);

			$sidebar_id = sanitize_title( $_POST['name'], 'sidebar-'.cjfm_unique_string() );
			$sidebar_name = $_POST['name'];
			$sidebar_class = $_POST['class'];
			$sidebar_description = $_POST['description'];

			$new_sidebar_data[$sidebar_id] = array(
				'id' => $sidebar_id,
				'name' => $sidebar_name,
				'description' => $sidebar_description,
				'class' => sanitize_title( $sidebar_class ),
			);

			if(!empty($saved_sidebar_data) && is_array($saved_sidebar_data)){
				$saved_sidebars = $saved_sidebar_data;
				$new_value = array_merge($saved_sidebars, $new_sidebar_data);
			}else{
				$new_value = $new_sidebar_data;
			}

			cjfm_update_option('dynamic_sidebars', $new_value);
			$location = cjfm_callback_url('theme_dynamic_sidebars');
			wp_redirect( $location, $status = 302 );
			exit;
		}else{
			echo cjfm_message('error', __('Sidebar Name is required.', 'cjfm'));
		}

	}

	// Remove Sidebar
	if(isset($_GET['cj_action']) && $_GET['cj_action'] == 'delete_sidebar' && $_GET['id'] != ''){
		$id = $_GET['id'];
		unset($saved_sidebar_data[$id]);
		cjfm_update_option('dynamic_sidebars', $saved_sidebar_data);
		$location = cjfm_callback_url('theme_dynamic_sidebars');
		wp_redirect( $location, $status = 302 );
		exit;
		
	}


?>
<div class="dynamic_sidebars">


	<?php if(isset($_GET['cj_action']) && $_GET['cj_action'] == 'add_sidebar'): ?>
	<form action="" method="post">
		<table class="enable-search margin-30-bottom" cellspacing="0" cellpadding="0" width="100%">
				<tbody>
					<tr>
						<th colspan="5"><h2 class="main-heading"><?php _e('Register New Sidebar', 'cjfm') ?></h2></th>
					</tr>
					<tr>
						<th width="10%"><?php _e('Sidebar ID', 'cjfm') ?></th>
						<th width="20%"><?php _e('Sidebar Name', 'cjfm') ?></th>
						<th width="40%"><?php _e('Description', 'cjfm') ?></th>
						<th width="15%"><?php _e('Class', 'cjfm') ?></th>
						<th width="15%"><?php _e('Actions', 'cjfm') ?></th>
					</tr>
					<tr>
						<td class="red"><?php _e('Generated Automatically', 'cjfm') ?></td>
						<td><input type="text" name="name" value="" /></td>
						<td><input type="text" name="description" value="" /></td>
						<td><input type="text" name="class" value="" /></td>
						<td><button type="submit" name="add_new_sidebar" class="button-primary"><?php _e('Add New Sidebar', 'cjfm') ?></button></td>
					</tr>
			</tbody>
		</table>
	</form>
	<?php endif; ?>


	<?php if(isset($_GET['cj_action']) && $_GET['cj_action'] == 'edit_sidebar' && $_GET['id'] != ''): ?>

	<?php
		$id = $_GET['id'];
		$sidebar_info = $saved_sidebar_data[$id];
	?>

	<form action="" method="post">
		<table class="enable-search margin-30-bottom" cellspacing="0" cellpadding="0" width="100%">
				<tbody>
					<tr>
						<th colspan="5"><h2 class="main-heading"><?php _e('Register New Sidebar', 'cjfm') ?></h2></th>
					</tr>
					<tr>
						<th width="10%"><?php _e('Sidebar ID', 'cjfm') ?></th>
						<th width="20%"><?php _e('Sidebar Name', 'cjfm') ?></th>
						<th width="40%"><?php _e('Description', 'cjfm') ?></th>
						<th width="15%"><?php _e('Class', 'cjfm') ?></th>
						<th width="15%"><?php _e('Actions', 'cjfm') ?></th>
					</tr>
					<tr>
						<td><?php echo $_GET['id']; ?></td>
						<td><input type="text" name="name" value="<?php echo $sidebar_info['name']; ?>" /></td>
						<td><input type="text" name="description" value="<?php echo $sidebar_info['description']; ?>" /></td>
						<td><input type="text" name="class" value="<?php echo $sidebar_info['class']; ?>" /></td>
						<td><button type="submit" name="edit_sidebar" class="button-primary"><?php _e('Edit Sidebar', 'cjfm') ?></button></td>
					</tr>
			</tbody>
		</table>
	</form>
	<?php endif; ?>


	<table class="enable-search" cellspacing="0" cellpadding="0" width="100%">
			<tbody>
				<tr>
					<th colspan="5">
						<a href="<?php echo cjfm_string(cjfm_callback_url('theme_dynamic_sidebars')).'cj_action=add_sidebar'; ?>" class="button-primary margin-3-top alignright" title="">Add new sidebar</a>
						<h2 class="main-heading"><?php _e('Registered Sidebars', 'cjfm') ?></h2>
					</th>
				</tr>
				<tr>
					<th width="10%"><?php _e('Sidebar ID', 'cjfm') ?></th>
					<th width="20%"><?php _e('Sidebar Name', 'cjfm') ?></th>
					<th width="40%"><?php _e('Description', 'cjfm') ?></th>
					<th width="15%"><?php _e('Class', 'cjfm') ?></th>
					<th width="15%"><?php _e('Actions', 'cjfm') ?></th>
				</tr>
	<?php 
	if(!empty($wp_registered_sidebars)):

		foreach ($wp_registered_sidebars as $key => $sidebar):?>

		<?php
			if($sidebar['class'] == 'required'){
				$action_links = '--';
			}else{
				$action_links = '<a href="'.cjfm_string(cjfm_callback_url('theme_dynamic_sidebars')).'cj_action=edit_sidebar&id='.$key.'" title="">'.__('Edit', 'cjfm').'</a> | ';	
				$action_links .= '<a href="'.cjfm_string(cjfm_callback_url('theme_dynamic_sidebars')).'cj_action=delete_sidebar&id='.$key.'" class="cj-confirm red" data-confirm="'.__("Are you sure?\nThis cannot be undone.", 'cjfm').'" title="">'.__('Delete', 'cjfm').'</a>';	
			}
			

		?>

		<tr>
			<td><?php echo $sidebar['id']; ?></td>
			<td><?php echo $sidebar['name']; ?></td>
			<td><?php echo $sidebar['description']; ?></td>
			<td><?php echo ($sidebar['class'] == 'required') ? '<span class="red">'.$sidebar['class'].'</span>' : $sidebar['class']; ?></td>
			<td>
				<?php echo $action_links; ?>
			</td>
		</tr>
			

	<?php 
		endforeach;
	endif;
	?>
		</tbody>
	</table>


</div>