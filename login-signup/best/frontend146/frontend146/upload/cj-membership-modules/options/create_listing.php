<?php 

$location = admin_url('post-new.php?post_type=listings');
wp_redirect($location);
die();