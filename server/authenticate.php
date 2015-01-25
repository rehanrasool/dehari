<?php

session_start();

	if (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) {
    	die(check_login($_POST['username'], $_POST['password']));
    }

    if (isset($_POST['signup']) && isset($_POST['username']) && isset($_POST['password'])) {
    	die(create_login($_POST['username'], $_POST['password']));
    }

    if (isset($_POST['enterinfo']) && isset($_POST['user_email'])) {
    	die(enter_info($_POST['user_email']));
    }



	// BELOW HERE
	/**
	*  function that checks if the login credentials are correct
	*
	*  @param string $username The user name $password The user password
	*
	*  @return int The user id, -1 if login is not successful
	*/
    function check_login($username, $password) {
        $db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

		 $return_value = json_encode(-1);

        if (!empty($username) && !empty($password)) {
        	$query = "SELECT user_id FROM dehari_user WHERE user_name = '" . $username . "' AND
                user_password = '" . $password . "';";


            $result = mysql_query($query, $db_dehari);
           
			if (!$result) {
			    $return_value  = 'Invalid query: ' . mysql_error() . "\n";
			    $return_value .= 'Whole query: ' . $query;
			    
			} else {
				$user_id = -1;
	            while ($row = mysql_fetch_assoc($result)) {
		            $user_id = $row['user_id'];
		            $user_name = $row['user_name'];
		        }

		        $_SESSION['user_id'] = $user_id;
		        $_SESSION['user_name'] = $username;

		        $return_value = json_encode($user_id);
			}

    	}



    	return $return_value;
	}

    /**
	*  create a new account in the database, returns error message if failed
	*
	*  @param string $username The user name $password The user password
	*
	*  @return string The error message
	*/
    function create_login($username, $password) {
        $db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

        if (!empty($username) && !empty($password)) {
            $query = "SELECT * FROM dehari_user WHERE user_name = '" . $username . "';";

            $result = mysql_query($query, $db_dehari);
            if (mysql_num_rows($result) == 0) {
                $query2 = "INSERT INTO dehari_user (user_name, user_password) VALUES ('" . $username . "', '" . $password . "');";
                mysql_query($query2, $db_dehari);

                $query3 = "SELECT user_id FROM dehari_user WHERE user_name = '" . $username . "';";

            	$result_user_data = mysql_query($query3, $db_dehari);
            	$result_user_data_array = mysql_fetch_assoc($result_user_data);

		        $_SESSION['user_id'] = $result_user_data_array['user_id'];
		        $_SESSION['user_name'] = $username;

                $message = "Username created successfully!";
                return json_encode($message);
            }
            else {
                $error_msg = 'There is an existing user with the same username.';
                return json_encode($error_msg);
            }
        }
        else {
            $error_msg = 'All fields cannot be empty';
            return json_encode($error_msg);
        }

    }

    function enter_info($user_email) {
    	$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
		mysql_select_db('bluecu6_dehari', $db_dehari);

		//session_start();
		$dehari_user_id = $_SESSION['user_id'];
		$query = 'UPDATE dehari_user SET user_email = "' . $user_email . '" WHERE user_id = ' . $dehari_user_id . ';';

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


?>
