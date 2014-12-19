<html>
<head>
	<title><?php bloginfo( 'name' ); ?></title>
	<style type="text/css">
		body{
			margin: 0px;
			padding: 0px;
			font-family: 'Arial', 'Verdana';
			font-size: 14px;
			background: <?php echo cjfm_get_option('body_bg_color'); ?>;
			color: <?php echo cjfm_get_option('body_text_color'); ?>;
		}
		h1,h2,h3,h4,h5,h6{
			margin: 0 0 20px 0;
		}
		p{
			margin: 0 0 10px 0;
		}
		a{
			color: <?php echo cjfm_get_option('body_link_color'); ?>;
			text-decoration: none;
		}
		#logo{
			display: block;
			text-align: center;
			margin-top: 100px;
		}
		#wrapper{
			width: 600px;
			margin: 0px auto 0 auto;
			text-align: center;
			background: <?php echo cjfm_get_option('panel_bg_color'); ?>;
			color: <?php echo cjfm_get_option('panel_text_color'); ?>;
			border: 1px solid <?php echo cjfm_get_option('panel_border_color'); ?>;
			padding: 30px 15px 30px 15px;
			border-radius: 5px;
		}
		#wrapper a,
		#wrapper a:visited,
		#wrapper a:active,
		#wrapper a:hover{
			color: <?php echo cjfm_get_option('panel_link_color'); ?> !important;
		}
		.copyright{
			text-align: center;
			margin-top: 20px;
			font-size: 12px;
			color: <?php echo cjfm_get_option('body_text_color'); ?>;
		}
		.copyright a{
			color: <?php echo cjfm_get_option('body_link_color'); ?>;
			text-decoration: none;
		}
		.margin-30-top{
			margin-top: 30px !important;
		}
		.margin-100-top{
			margin-top: 100px !important;
		}
	
		@media (max-width : 1023px) {
			#wrapper{
				width: 80% !important;
				margin: 0px auto 0 auto;
			}
		}

	</style>
	<?php do_action('cjfm_maintenance_mode_head'); ?>
</head>
<body>
	<?php
		$logo = cjfm_get_option('logo_image');
		if(!empty($logo)){
			echo '<a id="logo" href="'.site_url().'"><img src="'.$logo[0].'" alt="'.get_bloginfo('name').'" /></a>';
			$wrapper_top_margin = 'margin-30-top';
		}else{
			$wrapper_top_margin = 'margin-100-top';
		}
	?>
	
	<div id="wrapper" class="<?php echo $wrapper_top_margin; ?>">
		<?php echo do_shortcode(cjfm_get_option('maintenance_mode_content')); ?>
	</div>
	<div class="copyright">
		<?php echo sprintf(__('Copyright &copy; %d', 'cjfm'), date('Y')) ?> - <a href="<?php echo site_url(); ?>"><?php bloginfo( 'name' ) ?></a>
	</div>
</body>
</html>