<?php
/*
Plugin Name: PaymentSpring Track Signup
Plugin URI:  https://www.firespring.com/
Description: Track the entries in the Signup and Sandbox forms
Version:     1.0
Author:      Matia Ward
Author URI:  https://www.firespring.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

//ini_set('display_errors', 'On');
//error_reporting(E_ALL);


global $jal_db_version;
$jal_db_version = '1.0';

function jal_install() {
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'track_signup_form';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name tinytext NOT NULL,
		email tinytext NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}

/*function jal_install_data() {
	global $wpdb;
	
	$welcome_name = 'Mr. WordPress';
	$welcome_text = 'Congratulations, you just completed the installation!';
	
	$table_name = $wpdb->prefix . 'track_signup_form';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'name' => "test name", 
			'email' => "test@test.com", 
		) 
	);
}*/

register_activation_hook( __FILE__, 'jal_install' );
//register_activation_hook( __FILE__, 'jal_install_data' );


add_action('admin_menu', 'my_plugin_menu');
function my_plugin_menu() {
	add_menu_page('View Entries', 'View Signup Entries', 'administrator', 'track-signup', 'track_signup', 'dashicons-format-status');
}
function track_signup() {
	global $wpdb;

	$output = "<h1>Tracking Data for Signup and Sandbox forms</h1><br /><br />";

	if (isset($_POST['submitFilter'])) {
		$signups = $wpdb->get_results("Select * From " . $wpdb->prefix . "track_signup_form Where time > '" . $_POST['fromDate'] . "' And time < '" . $_POST['toDate'] . "' Order By time Desc;");
	} else {
		$signups = $wpdb->get_results("Select * From " . $wpdb->prefix . "track_signup_form Order By time Desc;");
	}

	$output .= "<h2>Filter</h2>";
	$output .= "<form action='' method='post' id='filterForm'>
					<table><tr><td style='vertical-align: top;'>From: <input type='text' name='fromDate' value='" . $_POST['fromDate'] . "' /><br /><span style='font-size: 11px; font-style: italic;'>Ex: 2017-03-22</span></td><td style='vertical-align: top;'>To: <input type='text' name='toDate' value='" . $_POST['fromDate'] . "' /><br /><span style='font-size: 11px; font-style: italic;'>Ex: 2017-04-01</span></td><td style='vertical-align: top;'><button type='submit' form='filterForm' name='submitFilter' value='Filter'>Filter</button></td></tr></table>
				</form>";
	$output .= "<br /><br />";
	$output .= "<table class='track_signup_table'>";
	$output .= "<tr><th>Date</th><th>Name</th><th>Email</th></tr>";
    foreach ($signups AS $signup) {
    	$date = strtotime($signup->time);
    	$output .= "<tr><td>" . date('M d, Y', $date) . "</td><td>" . $signup->name . "</td><td>" . $signup->email . "</td></tr>";
    }
    $output .= "</table>";

    $output .= "<div style='background-color: #4ec0af; padding: 20px; border-radius: 4px; display: inline-block; margin-top: 30px;'><a href='/wp-content/plugins/paymentspring-track-signup/create_export.php?fromDate=" . $_POST['fromDate'] . "&toDate=" . $_POST['toDate'] . "' style='color: #fff;'>Export Selected Entries</a></div>";

    echo $output;

}

