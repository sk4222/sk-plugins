<?php  
#########################################################################################
# Push Notifications
#########################################################################################
add_action( 'wp_ajax_cjfm_get_notifications', 'cjfm_get_notifications' );

function cjfm_get_notifications() {

	$check_notification = get_option('cjfm_notification_timestamp');
	$now = time();

	if(isset($_GET['push-notification']) && $_GET['push-notification'] == 'test'){
		$url = sprintf('%s', 'http://cssjockey.com/api/notifications-test.php');
	}else{
		$url = sprintf('%s', 'http://cssjockey.com/api/notifications.php');	
	}

	echo $check_notification;

	if(!$check_notification || $now > $check_notification){
		$args = array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array('product_cat' => 'cjfm'),
			'cookies' => array()
		);
		$response = wp_remote_post( $url, $args );

		if(!empty($response['body'])){
			$data = json_decode($response['body']);	
			$notification = get_option('cjfm_notification');
			$product_textdomain = $data->product;
			if(!is_null($product_textdomain)){
				$product_exists = (string)$product_textdomain.'_item_info';	
			}else{
				$product_exists = 'is_admin';
			}
			if(function_exists($product_exists)){
				if(!$notification){
					$notification_data = array(
						'ID' => $data->ID,
						'title' => $data->title,
						'content' => $data->content,
						'product' => $data->product,
						'status' => 'unread',
					);
					update_option('cjfm_notification', $notification_data);
				}		

				if($notification['ID'] < $data->ID){
					$notification_data = array(
						'ID' => $data->ID,
						'title' => $data->title,
						'content' => $data->content,
						'product' => $data->product,
						'status' => 'unread',
					);
					update_option('cjfm_notification', $notification_data);
				}
				update_option('cjfm_notification_timestamp', strtotime('+1 day'));	
			}
		}
	}
	die();
}

add_action( 'wp_ajax_cjfm_close_notification', 'cjfm_close_notification' );
function cjfm_close_notification() {
	$notification = get_option('cjfm_notification');
	unset($notification['status']);
	$notification['status'] = 'read';
	update_option('cjfm_notification', $notification);
	die();	
}


function cjfm_show_notification(){
	$notification = get_option('cjfm_notification');
	if($notification && $notification['status'] == 'unread'){
		$display[] = '<div id="notification-'.$notification['ID'].'" class="updated push-notification-message">';
		$display[] = '<div class="notification-icon">';
		$display[] = '<img src="http://cssjockey.com/files/leaf-64.png" />';
		$display[] = '</div>';
		$display[] = '<div class="notification-content">';
		$display[] = '<p class="notification-title">'.$notification['title'].'</p>';
		$display[] = $notification['content'];
		$display[] = '</div>';
		$display[] = '<a href="#notification-'.$notification['ID'].'" data-id="'.$notification['ID'].'" class="notification-close">x</a>';
		$display[] = '</div>';
		echo implode('', $display);
	}
}
add_action('admin_notices' , 'cjfm_show_notification');




function cjfm_notification_scripts(){

	if(isset($_GET['push-notification']) && $_GET['push-notification'] == 'test'){
		delete_option('cjfm_notification_timestamp');
		delete_option('cjfm_notification');	
	}

	$script = <<<EOD
	<script>
	jQuery(document).ready(function($) {
		$.post(
		    ajaxurl, {
		        'action': 'cjfm_get_notifications',
		        //'data': 'foobarid'
		    },
		    function(response) {
		        // console.log(response);
		        // alert(response);
		    }
		);

		$('.notification-close').on('click', function(){
		    var el = $(this).attr('href');
		    $.post(
		        ajaxurl, {
		            'action': 'cjfm_close_notification',
		            //'data': 'foobarid'
		        },
		        function(response) {
		            // console.log(response);
		            $(el).fadeOut(250);
		        }
		    );
		    return false;
		});
	});
	</script>
EOD;

echo $script;
}

add_action('admin_footer', 'cjfm_notification_scripts');