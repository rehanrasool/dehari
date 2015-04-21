<?php
    include 'find_dehari.php';
	session_start();

	if (isset($_POST['new_message']) && isset($_POST['message_title']) && isset($_POST['message_from']) && isset($_POST['message_to']) && isset($_POST['message_content']) ) {
    	die(new_message($_POST['message_title'], $_POST['message_from'], $_POST['message_to'], $_POST['message_content']));
    }

	if (isset($_POST['send_message']) && isset($_POST['conversation_id']) && isset($_POST['message_from']) && isset($_POST['message_to']) && isset($_POST['message_content']) ) {
    	die(send_message($_POST['conversation_id'], $_POST['message_from'], $_POST['message_to'], $_POST['message_content']));
    }

    // from's id given, to's username given; find to's id from username
    function new_message($title, $from, $to, $content) {
    	$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

		$to_id = get_userid_from_username($to);
		$from_id = $from;
		$query_insert_conversation_table = 'INSERT INTO dehari_conversation (title,user1,user2,time_stamp) VALUES ("'
													. $title . '",'
													. $from_id . ','
													. $to_id . ', NOW());' ;


		// Perform Query
		$result_insert_conversation_table = mysql_query($query_insert_conversation_table, $db_dehari);
		$convo_id = mysql_insert_id();

		// Check result
		// This shows the actual query sent to MySQL, and the error. Useful for debugging.

		if (!$result_insert_conversation_table) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query_insert_conversation_table;
		    
		} else {
			$query_insert_messages_table = 'INSERT INTO dehari_messages (conversation_id,message,from_id,to_id,time_stamp) VALUES ('
													. $convo_id . ',"'
													. $content . '",'
													. $from_id .','
													. $to_id . ', NOW());' ;


			// Perform Query
			$result_insert_message_table = mysql_query($query_insert_messages_table, $db_dehari);

			if (!$result_insert_message_table) {
					    $message  = 'Invalid query: ' . mysql_error() . "\n";
					    $message .= 'Whole query: ' . $query_insert_messages_table;
					    
			}else{
				$message = 'success';
			}

		}

		header( 'Location: ../messages.php' );
		return $message;

    }

    function send_message($convo_id, $from, $to, $content) {
    	$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

		$to_id = $to;
		$from_id = $from;

		$query_insert_messages_table = 'INSERT INTO dehari_messages (conversation_id,message,from_id,to_id,time_stamp) VALUES ('
															. $convo_id . ',"'
															. $content . '",'
															. $from_id .','
															. $to_id . ', NOW());' ;


		// Perform Query
		$result_insert_message_table = mysql_query($query_insert_messages_table, $db_dehari);

		if (!$result_insert_message_table) {
				    $message  = 'Invalid query: ' . mysql_error() . "\n";
				    $message .= 'Whole query: ' . $query_insert_messages_table;
				    
		}else{

			$query_update_conversation_timestamp = 'UPDATE dehari_conversation SET time_stamp = NOW() WHERE conversation_id = ' . $convo_id . ';';

			$result_update_conversation_table = mysql_query($query_update_conversation_timestamp, $db_dehari);
		// Check result
		// This shows the actual query sent to MySQL, and the error. Useful for debugging.

			if (!$result_update_conversation_table) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query_update_conversation_timestamp;
			    
			} else {
				$message = 'success';
			}	
		}

		header( 'Location: ../conversation.php?conversation_id=' . $convo_id . '&success=1' );
		return $message;

    }


?>
