<?php
	$url = 'http://cssjockey.com/api/products.php';
	$response = wp_remote_post( $url, array(
		'method' => 'POST',
		'timeout' => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array(),
		'body' => array(),
		'cookies' => array()
	    )
	);
	if ( is_wp_error( $response ) ) {
	   	$error_message = $response->get_error_message();
	   	$errors = $error_message;
	} else {
		$errors = null;
	   	$products = json_decode($response['body']);
	}
?><div class="wrap">
	<div class="postbox" style="margin-top:20px;">
		<h3 style="padding:10px;"><span><?php _e('Our Free & Premium Products', 'cjfm') ?></span></h3>
		<div class="inside">
			<?php
			if(!is_null($errors)){
				echo $errors;
			}else{
				echo '<div class="clearfix" style="padding:0 15px 0 15px;">';
				$count = 0;
				foreach ($products as $key => $value) {
					$count++;
					if($count % 4){
						$last_class = '';
					}else{
						$last_class = ' last';
					}
					echo '<div class="one_fourth'.$last_class.'" style="margin-bottom:25px;">';
					echo '<div style="padding:10px; background: #f7f7f7; border:1px solid #ddd;">';
					echo '<div style="padding:0 0 5px 0;"><a href="'.$value->url.'" target="_blank"><img src="'.$value->image.'" width="100%"></a></div>';
					echo '<div style="padding:0 0 5px 0;"><b style="font-size:12pt;">'.$value->title.' </b><br><b> '.$value->category.'</b></div>';
					echo '<div style="padding:0 0 10px 0;">'.$value->description.'</div>';
					echo '<div><a href="'.$value->url.'" target="_blank" class="button-primary">Read more</a></div>';
					echo '</div>';
					echo '</div>';
				}
				echo '</div>';
			}
			?>
		</div><!-- inside -->
	</div><!-- postbox -->	
</div>