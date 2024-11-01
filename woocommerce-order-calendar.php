<?php 
/**
* Plugin Name: Woocommerce Orders Calendar
* Plugin URI: http://dswebsolutions.in/
* Description:  Allow the customers orders show in Calendar Woocommerce.
* Version: 1.0
* Author: Deepak Shrama
* Author URI: http://dswebsolutions.in
*/

add_action( 'admin_head', 'order_calendar_enqueue_script' );
function order_calendar_enqueue_script() {
	wp_enqueue_style( 'fullcalendar_style', plugin_dir_url( __FILE__ ).'css/fullcalendar.css' );
	wp_enqueue_script( 'fullcalendar-min', plugin_dir_url( __FILE__ ).'js/fullcalendar.min.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'fullcalendar-script', plugin_dir_url( __FILE__ ).'js/fullscript.js', array( 'jquery' ), '', true );
}

add_action('admin_menu', 'register_order_calendar_page');
function register_order_calendar_page() {
	add_submenu_page('woocommerce', 'Woocommerce Orders Celandar Settings', 'Orders Celandar', 'manage_options', 'woocommerce-orders-calendar', 'order_calendar_page_callback' );
}

function order_calendar_page_callback() {
echo '<h1>Woocommerce Orders Celandar </h1><br/><div id="woo-order-calendar" class="fc"></div>';
}

add_action( 'wp_ajax_nopriv_get_calendar_details_ajax', 'get_calendar_details_ajax' );
add_action( 'wp_ajax_get_calendar_details_ajax', 'get_calendar_details_ajax' );
function get_calendar_details_ajax() {
	$orders = array(
				'post_type'   => 'shop_order',
				'post_status' => 'publish',
				'tax_query'   => array( 
									array(
										'taxonomy' => 'shop_order_status',
										'field'           => 'slug',
										'terms'         => array( 'processing', 'completed' )
									)
								)
				);
			$ordersnew =  new WP_Query( $orders );
			$arrayfinal = array();
			if($ordersnew->have_posts() ) : 
				while($ordersnew->have_posts() ) : $ordersnew->the_post();
					$ordertime = get_the_date('g:i A');
						if(get_post_status( get_the_ID() )=='wc-processing'){
							$status = 'Processing';
						} else {
							$status = 'Completed';
						}	
					$neorderdate = get_the_date('Y-m-d H:i:s');
					$aaray['id'] = get_the_ID();
					$aaray['title'] = $ordertime.' Order No - #'.get_the_ID().' ('.$status.')';
					$aaray['start'] = $neorderdate;
					$aaray['url'] = admin_url().'post.php?post='.get_the_ID().'&action=edit';
					$arrayfinal = $aaray;
				endwhile; 
			endif; 
			wp_reset_query();
		echo json_encode($arrayfinal);
	die();
}