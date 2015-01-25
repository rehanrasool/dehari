<?php

	session_start();

    if (isset($_POST['find_dehari']) && isset($_POST['dehari_id'])) {
    	die(find_dehari_item($_POST['dehari_id']));
    }

    if (isset($_POST['get_bids']) && isset($_POST['dehari_id'])) {
    	die(get_dehari_bids($_POST['dehari_id']));
    }

    if (isset($_POST['select_dehari']) && isset($_POST['dehari_id']) && isset($_POST['dehari_bid']) && isset($_POST['dehari_bid_message'])) {
    	die(select_dehari_item($_POST['dehari_id'], $_POST['dehari_bid'], $_POST['dehari_bid_message']));
    }

    if (isset($_POST['update_bid']) && isset($_POST['dehari_id']) && isset($_POST['dehari_bid']) && isset($_POST['dehari_bid_message'])) {
    	die(update_dehari_bid($_POST['dehari_id'], $_POST['dehari_bid'], $_POST['dehari_bid_message']));
    }

    if (isset($_POST['select_bid']) && isset($_POST['dehari_id']) && isset($_POST['bid_user']) && isset($_POST['bid_value'])) {
    	die(select_dehari_bid($_POST['dehari_id'], $_POST['bid_user'], $_POST['bid_value']));
    }

    if (isset($_POST['incoming_dehari_completed']) && isset($_POST['dehari_id']) && isset($_POST['client_rating'])) {
    	die(mark_incoming_dehari_completed($_POST['dehari_id'], $_POST['client_rating']));
    }


    if (isset($_POST['outgoing_dehari_completed']) && isset($_POST['dehari_id']) && isset($_POST['worker_rating'])) {
    	die(mark_outgoing_dehari_completed($_POST['dehari_id'], $_POST['worker_rating']));
    }



	function mark_outgoing_dehari_completed ( $dehari_id, $worker_rating ) {

    	$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

		//$dehari_user_id = $_SESSION['user_id'];


		$query = 'SELECT * FROM dehari_list WHERE dehari_id = ' . $dehari_id;

		// Perform Query
		$result = mysql_query($query, $db_dehari);
		$result_array = mysql_fetch_array($result);

		$bid_selected = $result_array['dehari_selected_bid'];
		$bid_selected_split = explode(":", $bid_selected);
		$dehari_worker_id = $bid_selected_split[0];
		$bid_value = $bid_selected_split[1];

		$dehari_client_id = $result_array['dehari_user_id'];

		if (!$result) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query;
		    
		} else {
			$query_update_worker_rating = 'UPDATE dehari_list SET dehari_worker_rating = ' . $worker_rating . ' WHERE dehari_id = ' . $dehari_id;

			// Perform Query
			$result_update_worker_rating = mysql_query($query_update_worker_rating, $db_dehari);

			if (!$result_update_worker_rating) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query;
			    
			} else {


				$query_get_users = 'SELECT * FROM dehari_user WHERE user_id IN (' . $dehari_client_id . ',' . $dehari_worker_id  . ');';
				// Perform Query
				$result_get_users = mysql_query($query_get_users, $db_dehari);


				if (!$result_get_users) {
				    $message  = 'Invalid query: ' . mysql_error() . "\n";
				    $message .= 'Whole query: ' . $query_get_users;
				    
				} else {
					$result_get_users_row1 = mysql_fetch_assoc($result_get_users);
					$result_get_users_row2 = mysql_fetch_assoc($result_get_users);
					
					if ($result_get_users_row1['user_id'] == $dehari_worker_id) {
						$user_incoming_data = $result_get_users_row1;
						$user_outgoing_data = $result_get_users_row2;
					} else {
						$user_incoming_data = $result_get_users_row2;
						$user_outgoing_data = $result_get_users_row1;
					}

					// remove bid from clients outgoing in progress
					$user_outgoing_in_progress = trim($user_outgoing_data['user_outgoing_in_progress'], ",");
					$user_outgoing_in_progress_array = explode(",", $user_outgoing_in_progress);
					if(($key = array_search($dehari_id, $user_outgoing_in_progress_array)) !== false) {
					    unset($user_outgoing_in_progress_array[$key]);
					}
					$user_outgoing_in_progress_updated = implode(",", $user_outgoing_in_progress_array);
					$user_outgoing_in_progress_updated = $user_outgoing_in_progress_updated . ',';

					// add bid to workers clients work in progress
					$user_outgoing_completed = trim($user_outgoing_data['user_outgoing_completed'], ",");
					$user_outgoing_completed_updated = $user_outgoing_completed . ',' . $dehari_id . ',';

					// add money to clients expenses
					$user_outgoing_new_expenses = $user_outgoing_data['user_outgoing'] + $bid_value;


					// update workers rating
					$worker_new_total_ratings = $user_incoming_data['user_incoming_total_ratings'] + $worker_rating;
					$worker_new_total_deharis = $user_incoming_data['user_incoming_total_deharis'] + 1;
					$worker_new_rating = $worker_new_total_ratings / $worker_new_total_deharis;
					
					// Queries
					$query_update_outgoing = 'UPDATE dehari_user SET user_outgoing_in_progress = "' . $user_outgoing_in_progress_updated . '", user_outgoing_completed = "' . $user_outgoing_completed_updated .  '", user_outgoing = ' . $user_outgoing_new_expenses . '  WHERE user_id = ' . $dehari_client_id;

					$query_update_incoming = 'UPDATE dehari_user SET user_incoming_total_ratings = ' . $worker_new_total_ratings . ', user_incoming_total_deharis = ' . $worker_new_total_deharis . ', user_incoming_rating = ' . $worker_new_rating . '  WHERE user_id = ' . $dehari_worker_id;
					
					// Perform Query
					$result_update_outgoing = mysql_query($query_update_outgoing, $db_dehari);

					if (!$result_update_outgoing) {
					    $message  = 'Invalid query: ' . mysql_error() . "\n";
					    $message .= 'Whole query: ' . $query_update_outgoing;
					    
					} else {

						// Perform Query
						$result_update_incoming = mysql_query($query_update_incoming, $db_dehari);

						if (!$result_update_incoming) {
					    $message  = 'Invalid query: ' . mysql_error() . "\n";
					    $message .= 'Whole query: ' . $query_update_incoming;
					    
						} else {
							$message = 'success';
						}
					}

				}
			}
		}

		return $message;
    }



	function mark_incoming_dehari_completed ( $dehari_id, $client_rating ) {

    	$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

		//$dehari_user_id = $_SESSION['user_id'];


		$query = 'SELECT * FROM dehari_list WHERE dehari_id = ' . $dehari_id;

		// Perform Query
		$result = mysql_query($query, $db_dehari);
		$result_array = mysql_fetch_array($result);

		$bid_selected = $result_array['dehari_selected_bid'];
		$bid_selected_split = explode(":", $bid_selected);
		$dehari_user_id = $bid_selected_split[0];
		$bid_value = $bid_selected_split[1];

		$dehari_client_id = $result_array['dehari_user_id'];

		if (!$result) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query;
		    
		} else {
			$query_update_client_rating = 'UPDATE dehari_list SET dehari_client_rating = ' . $client_rating . ' WHERE dehari_id = ' . $dehari_id;

			// Perform Query
			$result_update_client_rating = mysql_query($query_update_client_rating, $db_dehari);

			if (!$result_update_client_rating) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query;
			    
			} else {


				$query_get_users = 'SELECT * FROM dehari_user WHERE user_id IN (' . $dehari_client_id . ',' . $dehari_user_id  . ');';
				// Perform Query
				$result_get_users = mysql_query($query_get_users, $db_dehari);


				if (!$result_get_users) {
				    $message  = 'Invalid query: ' . mysql_error() . "\n";
				    $message .= 'Whole query: ' . $query_get_users;
				    
				} else {
					$result_get_users_row1 = mysql_fetch_assoc($result_get_users);
					$result_get_users_row2 = mysql_fetch_assoc($result_get_users);
					
					if ($result_get_users_row1['user_id'] == $dehari_user_id) {
						$user_incoming_data = $result_get_users_row1;
						$user_outgoing_data = $result_get_users_row2;
					} else {
						$user_incoming_data = $result_get_users_row2;
						$user_outgoing_data = $result_get_users_row1;
					}

					// remove bid from workers incoming in progress
					$user_incoming_in_progress = trim($user_incoming_data['user_incoming_in_progress'], ",");
					$user_incoming_in_progress_array = explode(",", $user_incoming_in_progress);
					if(($key = array_search($dehari_id, $user_incoming_in_progress_array)) !== false) {
					    unset($user_incoming_in_progress_array[$key]);
					}
					$user_incoming_in_progress_updated = implode(",", $user_incoming_in_progress_array);
					$user_incoming_in_progress_updated = $user_incoming_in_progress_updated . ',';

					// add bid to workers incoming work in progress
					$user_incoming_completed = trim($user_incoming_data['user_incoming_completed'], ",");
					$user_incoming_completed_updated = $user_incoming_completed . ',' . $dehari_id . ',';

					// add money to workers earnings
					$user_incoming_new_earnings = $user_incoming_data['user_incoming'] + $bid_value;

					// update clients rating
					//$client_old_rating = $user_outgoing_data['user_outgoing_rating'];
					$client_new_total_ratings = $user_outgoing_data['user_outgoing_total_ratings'] + $client_rating;
					$client_new_total_deharis = $user_outgoing_data['user_outgoing_total_deharis'] + 1;
					$client_new_rating = $client_new_total_ratings / $client_new_total_deharis;
					
					// Queries
					$query_update_incoming = 'UPDATE dehari_user SET user_incoming_in_progress = "' . $user_incoming_in_progress_updated . '", user_incoming_completed = "' . $user_incoming_completed_updated .  '", user_incoming = ' . $user_incoming_new_earnings . '  WHERE user_id = ' . $dehari_user_id;

					$query_update_outgoing = 'UPDATE dehari_user SET user_outgoing_total_ratings = ' . $client_new_total_ratings . ', user_outgoing_total_deharis = ' . $client_new_total_deharis . ', user_outgoing_rating = ' . $client_new_rating . '  WHERE user_id = ' . $dehari_client_id;
					
					// Perform Query
					$result_update_outgoing = mysql_query($query_update_outgoing, $db_dehari);

					if (!$result_update_outgoing) {
					    $message  = 'Invalid query: ' . mysql_error() . "\n";
					    $message .= 'Whole query: ' . $query_update_outgoing;
					    
					} else {

						// Perform Query
						$result_update_incoming = mysql_query($query_update_incoming, $db_dehari);

						if (!$result_update_incoming) {
					    $message  = 'Invalid query: ' . mysql_error() . "\n";
					    $message .= 'Whole query: ' . $query_update_incoming;
					    
						} else {
							$message = 'success';
						}
					}

				}
			}
		}

		return $message;
    }


	function select_dehari_bid ( $dehari_id, $dehari_bid_user_id, $dehari_bid_value ) {

    	$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

		$dehari_user_id = $_SESSION['user_id'];

		$query = 'UPDATE dehari_list SET dehari_selected_bid = "' . $dehari_bid_user_id . ':' . $dehari_bid_value . '", dehari_status = 1 WHERE dehari_id = ' . $dehari_id;

		// Perform Query
		$result = mysql_query($query, $db_dehari);

		// Check result
		// This shows the actual query sent to MySQL, and the error. Useful for debugging.

		if (!$result) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query;
		    
		} else {
			
			$query_get_users = 'SELECT * FROM dehari_user WHERE user_id IN (' . $dehari_bid_user_id . ',' . $dehari_user_id  . ');';
			// Perform Query
			$result_get_users = mysql_query($query_get_users, $db_dehari);


			if (!$result_get_users) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query_get_users;
			    
			} else {
				$result_get_users_row1 = mysql_fetch_assoc($result_get_users);
				$result_get_users_row2 = mysql_fetch_assoc($result_get_users);
				
				if ($result_get_users_row1['user_id'] == $dehari_bid_user_id) {
					$user_incoming_data = $result_get_users_row1;
					$user_outgoing_data = $result_get_users_row2;
				} else {
					$user_incoming_data = $result_get_users_row2;
					$user_outgoing_data = $result_get_users_row1;
				}

				// remove bid from workers incoming submitted bids
				$user_incoming_bids = trim($user_incoming_data['user_incoming_bids'], ",");
				$user_incoming_bids_array = explode(",", $user_incoming_bids);
				if(($key = array_search($dehari_id, $user_incoming_bids_array)) !== false) {
				    unset($user_incoming_bids_array[$key]);
				}
				$user_incoming_bids_updated = implode(",", $user_incoming_bids_array);
				$user_incoming_bids_updated = $user_incoming_bids_updated . ',';

				// add bid to workers incoming work in progress
				$user_incoming_in_progress = trim($user_incoming_data['user_incoming_in_progress'], ",");
				$user_incoming_in_progress_updated = $user_incoming_in_progress . ',' . $dehari_id . ',';
				

				// remove bid from clients outgoing posted
				$user_outgoing_posted = trim($user_outgoing_data['user_outgoing_posted'], ",");
				$user_outgoing_posted_array = explode(",", $user_outgoing_posted);
				if(($key = array_search($dehari_id, $user_outgoing_posted_array)) !== false) {
				    unset($user_outgoing_posted_array[$key]);
				}
				$user_outgoing_posted_updated = implode(",", $user_outgoing_posted_array);
				$user_outgoing_posted_updated = $user_outgoing_posted_updated . ',';

				// remove bid from clients outgoing bids received
				$user_outgoing_bids_received = trim($user_outgoing_data['user_outgoing_bids_received'], ",");
				$user_outgoing_bids_received_array = explode(",", $user_outgoing_bids_received);
				if(($key = array_search($dehari_id, $user_outgoing_bids_received_array)) !== false) {
				    unset($user_outgoing_bids_received_array[$key]);
				}
				$user_outgoing_bids_received_updated = implode(",", $user_outgoing_bids_received_array);
				$user_outgoing_bids_received_updated = $user_outgoing_bids_received_updated . ',';

				// add bid to clients outgoing work in progress
				$user_outgoing_in_progress = trim($user_incoming_data['user_outgoing_in_progress'], ",");
				$user_outgoing_in_progress_updated = $user_outgoing_in_progress . ',' . $dehari_id . ',';
				
				// Queries
				$query_update_incoming = 'UPDATE dehari_user SET user_incoming_bids = "' . $user_incoming_bids_updated . '", user_incoming_in_progress = "' . $user_incoming_in_progress_updated . '"  WHERE user_id = ' . $dehari_bid_user_id;

				$query_update_outgoing = 'UPDATE dehari_user SET user_outgoing_posted = "' . $user_outgoing_posted_updated . '", user_outgoing_bids_received = "' . $user_outgoing_bids_received_updated . '", user_outgoing_in_progress = "' . $user_outgoing_in_progress_updated . '"  WHERE user_id = ' . $dehari_user_id;
				
				// Perform Query
				$result_update_outgoing = mysql_query($query_update_outgoing, $db_dehari);

				if (!$result_update_outgoing) {
				    $message  = 'Invalid query: ' . mysql_error() . "\n";
				    $message .= 'Whole query: ' . $query_update_outgoing;
				    
				} else {

					// Perform Query
					$result_update_incoming = mysql_query($query_update_incoming, $db_dehari);

					if (!$result_update_incoming) {
				    $message  = 'Invalid query: ' . mysql_error() . "\n";
				    $message .= 'Whole query: ' . $query_update_incoming;
				    
					} else {
						$message = 'success';
					}
				}
			}
		}

		return $message;
    }


	function get_dehari_bids ( $dehari_id ) {

    	$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

		$dehari_item = find_dehari_item($dehari_id);
		$dehari_item_array = json_decode($dehari_item, true);
		if (isset($dehari_item_array['dehari_bids_array'])){
			$dehari_bids =  $dehari_item_array['dehari_bids_array'];
		} else {
			$dehari_bids =  array();
		}
		//print_r ($dehari_bids); 
		//return json_encode($dehari_bids);
		$bids_array = array();
		foreach ($dehari_bids as $bid_user_id => $bid_value) {

			$bids_array[$bid_user_id] = array();
			$bids_array[$bid_user_id]['dehari_id'] = $dehari_id;
			$bids_array[$bid_user_id]['bid_user_id'] = $bid_user_id;
			$bids_array[$bid_user_id]['bid_value'] = $bid_value;

			$query = 'SELECT * FROM dehari_user WHERE user_id = ' . $bid_user_id;

			$result = mysql_query($query, $db_dehari);
			$result_array = mysql_fetch_assoc($result);

			if (!$result) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query;
			    return $message;
			} else {
				$bids_array[$bid_user_id]['bid_user_name'] = $result_array['user_name'];
				$bids_array[$bid_user_id]['bid_user_rating'] = $result_array['user_incoming_rating'];
			}
		}

		return json_encode($bids_array);
    }

	function update_dehari_bid ( $dehari_id, $dehari_bid, $dehari_bid_message ) {

    	$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

		$dehari_user_id = $_SESSION['user_id'];
		$dehari_old_bids_array = $_SESSION['dehari_bids'];
		$dehari_old_bids_array[$dehari_user_id] = $dehari_bid; // update to new bid
		$dehari_bid_formatted = json_encode($dehari_old_bids_array);
		$dehari_bids_new = mysql_real_escape_string($dehari_bid_formatted);

		$query = 'UPDATE dehari_list SET dehari_bids = "' . $dehari_bids_new . '," WHERE dehari_id = ' . $dehari_id;
		// Perform Query
		$result = mysql_query($query, $db_dehari);

		// Check result
		// This shows the actual query sent to MySQL, and the error. Useful for debugging.

		if (!$result) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query;
		    
		} else {
			$message = 'success';
		}

		return $message;
    }


	function select_dehari_item ( $dehari_id, $dehari_bid, $dehari_bid_message ) {

    	$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

		$dehari_user_id = $_SESSION['user_id'];
		$dehari_bid_formatted = '{"' . $dehari_user_id . '":' . $dehari_bid . '},';
		$dehari_bid_formatted = mysql_real_escape_string($dehari_bid_formatted);

		$query = 'UPDATE dehari_list SET dehari_bids = CONCAT(dehari_bids,"' . $dehari_bid_formatted . '") WHERE dehari_id = ' . $dehari_id;

		// Perform Query
		$result = mysql_query($query, $db_dehari);

		// Check result
		// This shows the actual query sent to MySQL, and the error. Useful for debugging.

		if (!$result) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query;
		    
		} else {
			
			$query_update_incoming = 'UPDATE dehari_user SET user_incoming_bids = CONCAT(user_incoming_bids,"' . $dehari_id . ',") WHERE user_id = ' . $dehari_user_id;
			// Perform Query
			$result_update_incoming = mysql_query($query_update_incoming, $db_dehari);


			if (!$result_update_incoming) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query_update_incoming;
			    
			} else {
				$dehari_client_id = $_SESSION['dehari_client_id'];
				$query_update_outgoing = 'UPDATE dehari_user SET user_outgoing_bids_received = CONCAT(user_outgoing_bids_received,"' . $dehari_id . ',") WHERE user_id = ' . $dehari_client_id;
				// Perform Query
				$result_update_outgoing = mysql_query($query_update_outgoing, $db_dehari);


				if (!$result_update_outgoing) {
				    $message  = 'Invalid query: ' . mysql_error() . "\n";
				    $message .= 'Whole query: ' . $query_update_outgoing;
				    
				} else {
					$message = 'success';
				}
			}
		}

		return $message;
    }

    function find_dehari_item ( $dehari_id ) {

    	$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

		$query = 'SELECT * FROM dehari_list WHERE dehari_id = ' . $dehari_id;

		// Perform Query
		$result = mysql_query($query, $db_dehari);

		// Check result
		// This shows the actual query sent to MySQL, and the error. Useful for debugging.

		if (!$result) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query;
		    die($message);
		}

		$result_array = mysql_fetch_assoc($result);

		if (!empty($result_array['dehari_bids'])) {	
			$trimmed_dehari_bids = trim($result_array['dehari_bids'], ",");
			$trimmed_dehari_bids = '[' . $trimmed_dehari_bids . ']'; // convert to JSON format
			$result_array_objects  = json_decode($trimmed_dehari_bids,true);

			foreach ($result_array_objects as $key => $val) {
				foreach ($val as $key_id => $val_bid) {
				$result_array['dehari_bids_array'][$key_id] = $val_bid;
				}
			}

			$_SESSION['dehari_bids'] = $result_array['dehari_bids_array'];
		}

		$result_array['user_id'] = $_SESSION['user_id'];

		$_SESSION['dehari_client_id'] = $result_array['dehari_user_id'];

		return json_encode($result_array);
    }

?>