<?php

 	session_start();

    if (isset($_POST['postdata']) && isset($_SESSION['user_id']) && isset($_POST['dehari_title']) && isset($_POST['dehari_address']) && isset($_POST['dehari_city']) &&
    	isset($_POST['dehari_category']) && isset($_POST['dehari_phone']) && isset($_POST['dehari_budget']) && isset($_POST['dehari_description'])) {
    	die(post_dehari_item($_SESSION['user_id'], $_POST['dehari_title'], $_POST['dehari_address'],$_POST['dehari_city'],$_POST['dehari_category'],$_POST['dehari_phone'],$_POST['dehari_budget'], $_POST['dehari_description']));
    }





    function post_dehari_item ( $dehari_user_id, $dehari_title, $dehari_address, $dehari_city, $dehari_category, $dehari_phone, $dehari_budget, $dehari_description) {

    	$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

		$query = 'INSERT INTO dehari_list
				    (dehari_user_id, dehari_title, dehari_address, dehari_city, dehari_category, dehari_phone, dehari_budget, dehari_description, dehari_date )
				  VALUES
				  	(' . $dehari_user_id . ',"'
				  	   . $dehari_title . '","'
				  	   . $dehari_address . '","'
				  	   . $dehari_city . '","'
				  	   . $dehari_category . '",'
				  	   . $dehari_phone . ',"'
				  	   . $dehari_budget . '","'
				  	   . $dehari_description . '",NOW())';

		// Perform Query
		$result = mysql_query($query, $db_dehari);

		// Check result
		// This shows the actual query sent to MySQL, and the error. Useful for debugging.

		if (!$result) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query;
		    
		} else {
			
			$dehari_user_id = $_SESSION['user_id'];
			$dehari_id = mysql_insert_id();
			$query_update_outgoing = 'UPDATE dehari_user SET user_outgoing_posted = CONCAT(user_outgoing_posted,"' . $dehari_id . ',") WHERE user_id = ' . $dehari_user_id;
			// Perform Query
			$result_update_outgoing = mysql_query($query_update_outgoing, $db_dehari);


			if (!$result_update_outgoing) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query_update_outgoing;
			    
			} else {
				$message = 'success';
			}
		}
		return $message;
    }

?>