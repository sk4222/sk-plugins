<?php 
function cjfm_add_dashboard_widgets() {
	wp_add_dashboard_widget(
                 'cjfm_dashboard_widget',         // Widget slug.
                 'cjfm',         // Title.
                 'cjfm_dashboard_widget_content' // Display function.
        );	
}

//add_action( 'wp_dashboard_setup', 'cjfm_add_dashboard_widgets' );


function cjfm_dashboard_widget_content() {

	$display[] = '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget.</p>';
	$display[] = '<h4>Comments</h4>';
	$display[] = '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget.</p>';

	echo implode('', $display);
	
}
