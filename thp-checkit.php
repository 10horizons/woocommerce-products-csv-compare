<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


function thp_check_file() {
	
	$thp_csvpath = $_POST['thp_path'];
	
	$file = fopen($thp_csvpath,"r") or exit("ERROR: File does not exist!");
	
	$csv_arr = array();

	if ($file !== FALSE) {
		while(! feof($file)) {
			array_push($csv_arr, fgetcsv($file));
		}
	}

	fclose($file);
	
	echo json_encode($csv_arr[0]);
	
	die();
}
add_action('wp_ajax_thp_check_file', 'thp_check_file'); 
add_action('wp_ajax_nopriv_thp_check_file', 'thp_check_file');


function thp_wcpc_run_check() {

$thp_wc_field = $_POST['thp_wcfield'];
$thp_csv_field = $_POST['thp_csvfield'];
$thp_csv_path_h = $_POST['thp_csvpath_hidden'];

$args = array(
    'posts_per_page'   => -1, //show all posts
    'post_type'        => 'product', //woo products
    'post_status'      => 'publish', //published posts only
);
$wooproducts = get_posts( $args );

$wc_fields = array(); //woocommerce field we're going to compare

if (count($wooproducts)) {
    foreach ($wooproducts as $wooproduct) {
		if ( $thp_wc_field == 'thp-wc-id' ) {
			$product_field = $wooproduct->ID;
			array_push($wc_fields, $product_field);
		}
		elseif ( $thp_wc_field == 'thp-wc-sku' ) {
			$product_field = get_post_meta($wooproduct->ID, '_sku', true);
			array_push($wc_fields, $product_field);
		}
		elseif ( $thp_wc_field == 'thp-wc-name' ) {
			$product_field = get_the_title($wooproduct->ID);
			array_push($wc_fields, $product_field);
		}
		else {
			break;
		}
	}
}

$file = fopen($thp_csv_path_h,"r") or exit("ERROR: File does not exist!");

$csvproducts = array();

if ($file !== FALSE) {
    while(! feof($file)) {
	    array_push($csvproducts, fgetcsv($file));
    }
}

fclose($file);

$csv_fields = array(); //csv field we're going to compare

$n = 1; //starts with 1, ignoring 0 which contains csv header
while ($n < sizeof($csvproducts)) {
	array_push($csv_fields, $csvproducts[$n][$thp_csv_field]);
	$n++;
}

$results = array_diff($csv_fields, $wc_fields);

echo '<h3 style="font-weight:bold; margin-top: 50px;">The following products are missing from your Woocommerce shop:</h3>';
echo '<table class="wp-list-table widefat fixed striped">';

if ($results) {
	echo '<thead>';
	echo '<tr>';
	echo '<th>';
	echo $csvproducts[0][$thp_csv_field]; //assuming the csv does have a header
	echo '</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	
    foreach ($results as $result) {		
		echo '<tr>';
		echo '<td>';
		echo $result;
		echo '</td>';
		echo '</tr>';
	}
	
	echo '</tbody>';
}
else {
	echo '<tbody>';
	echo '<tr>';
	echo '<td>';
	echo 'None';
	echo '</td>';
	echo '</tr>';
	echo '</tbody>';
}

echo '</table>';

die();
}
add_action('wp_ajax_thp_wcpc_run_check', 'thp_wcpc_run_check'); 
add_action('wp_ajax_nopriv_thp_wcpc_run_check', 'thp_wcpc_run_check');


/*
function thp_wcpc_check_it() {
	if (( isset( $_POST['thp-checker'] ) ) && ( current_user_can('manage_options') ))  {
		$thp_path = $_POST['thp_csv_path'];		
		thp_wcpc_run_check($thp_path);
	}
}
add_action( 'init', 'thp_wcpc_check_it' );
*/