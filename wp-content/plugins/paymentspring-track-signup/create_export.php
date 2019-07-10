<?php
//if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once('../../../wp-config.php');
global $wpdb;

	// Build your query						
	$dbQuery = $wpdb->get_results("SELECT id, time, name, email FROM " . $wpdb->prefix . "track_signup_form Where time > '" . $_GET['fromDate'] . "' And time < '" . $_GET['toDate'] . "';");
							

	// Process report request
	if (! $dbQuery) {
	$Error = $wpdb->print_error();
	die("The following error was found: $Error");
	} else {
	// Prepare our csv download

		// Set header row values
		$csv_fields=array();
		$csv_fields[] = 'ID';
		$csv_fields[] = 'Time';
		$csv_fields[] = 'Name';
		$csv_fields[] = 'Email';
		$output_filename = 'PaymentSpring_Signup_Export.csv';
		$output_handle = @fopen( 'php://output', 'w' );
		
		//header_remove();
		//header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		//header( 'Content-Description: File Transfer' );
		header('Content-Disposition:attachment'); 
    	header('Content-type: application/csv');
    	header('Content-Disposition: attachment; filename="'.$output_filename.'"' );
		//header( 'Expires: 0' );
		//header( 'Pragma: public' );	

		// Insert header row
		fputcsv( $output_handle, $csv_fields );

		// Parse results to csv format
		foreach ($dbQuery as $Result) {
			$leadArray = (array) $Result; // Cast the Object to an array
			// Add row to file
			fputcsv( $output_handle, $leadArray );
			}
		 
		// Close output file stream
		fclose( $output_handle ); 

		die();
	}