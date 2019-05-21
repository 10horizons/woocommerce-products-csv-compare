<?php
/*
Plugin Name: Missing Woocommerce Products Checker
Plugin URI: https://wproot.dev
Description: Compare products in CSV file with Woocommerce products in your WordPress site and see if there are missing products. 
Version: 0.1.0
Author: 10Horizons Plugins
Author URI: https://10horizonsplugins.com
*/

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


function thp_wcpc_admin_menu() {
	$wcpc_page = add_submenu_page( 'edit.php?post_type=product', 'Missing Products Checker', 'Missing Products Checker', 'manage_woocommerce', 'thp-missing-products-checker', 'thp_wcpc_missing_products_checker' );
	
	//Load the JS conditionally
	add_action( 'load-' . $wcpc_page, 'thp_wcpc_load_admin_js' );
}
add_action( 'admin_menu', 'thp_wcpc_admin_menu' );


function thp_wcpc_load_admin_js(){
	add_action( 'admin_enqueue_scripts', 'thp_wcpc_enqueue_admin_js' );
}


function thp_wcpc_enqueue_admin_js(){
	wp_enqueue_script( 'thp-wcpc-admin-js', plugin_dir_url( __FILE__ ).'thp-wcpc-js.js', array(), false, true );
	/*wp_localize_script('thp-wcpc-admin-js', 'thp_wcpc_vars', array(
			'wcresults' => __('Testings', 'thp-wcpc')
		)
	);*/
}


function thp_wcpc_missing_products_checker() { ?>
	
	<h2>Missing Woocommerce Products Checker</h2>
	
	<div class="wrap">
	
		<br />
		<p>Compare all Woocommerce products that you have on your WordPress site with the products found in a CSV file, and see if there are any missing from your Woocommerce shop. Specify the path of the CSV file below.</p>
		
		<form id="thp-check-file" action="" method="post">
			<p>Full path of the CSV file (must include http) :
			<input id="thp-csv-path" type="text" name="thp_csv_path" size="75" value="http://" />
			
			<input type="submit" id="thp-checkfile-button" class="button-primary" name="thp-file-checker" value="Check file!" />
			</p>
			<br />
		</form>
		
		<form id="thp-check-form" action="" method="post">
		
			<p style="font-weight:bold">Choose which fields to compare:</p>
			
			<input type="hidden" id="thp-csvpath-hidden" name="thp_csvpath_hidden" value="">
			
			<div style="float:left; margin-right:50px;">
			<span style="font-style:italic">Woocommerce field</span> <br /><br />
				<select id="thp-wc-columns">
					<option value="thp-wc-id">Product ID</option>
					<option value="thp-wc-sku">Product SKU</option>
					<option value="thp-wc-name">Product Name</option>
				</select>
			</div>
			
			<div>
			<span style="font-style:italic">CSV field</span> <br /><br />
				<select id="thp-csv-columns">
				</select>
			</div>
			
			<br />
			<div style="clear:both"></div>
			<br />
			<input type="submit" id="thp-wcpc-button" class="button-primary" name="thp-checker" value="Compare Now!" />
			
			<p><span style="font-weight:bold">Note:</span> Your result will appear below. Please allow some time for the result to appear. Longer if your site and file are big.</p>
		</form>
	
	</div>
	
	<div id="thp-results" class="wrap">
	</div>
<?php
}


require_once('thp-checkit.php');