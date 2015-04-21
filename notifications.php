<?php
    include 'server/find_dehari.php';
    session_start();
    if (!isset($_SESSION['user_id'])) {
       header("Location: index.php");
    }
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];



    // get notifications
    $db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
        mysql_select_db('bluecu6_dehari', $db_dehari);

    $query = 'SELECT * FROM dehari_notifications WHERE notification_read = 0 AND notification_user_id = ' . $user_id . ' ORDER BY notification_timestamp DESC;';

    // Perform Query
    $result = mysql_query($query, $db_dehari);

    $notification_list = array();
    while ($notification_list_row = mysql_fetch_assoc($result))
            $notification_list[] = $notification_list_row;

    // get old 10
    $query_old = 'SELECT * FROM dehari_notifications WHERE notification_read = 1 AND notification_user_id = ' . $user_id . ' ORDER BY notification_timestamp DESC LIMIT 10;';
    // Perform Query
    $result_old = mysql_query($query_old, $db_dehari);

    $notification_list_old = array();
    while ($notification_list_old_row = mysql_fetch_assoc($result_old))
            $notification_list_old[] = $notification_list_old_row;


    // mark notifications read
    $query_update = 'UPDATE dehari_notifications SET notification_read = 1 WHERE notification_user_id = ' . $user_id;

    // Perform Query
    $result_update = mysql_query($query_update, $db_dehari);

    if (!$result_update) {
        $message  = 'Invalid query: ' . mysql_error() . "\n";
        $message .= 'Whole query: ' . $query_update_outgoing;
        
    } 

    // get new messages count
    $query_message_count = 'SELECT COUNT(DISTINCT conversation_id) as new_messages_count FROM dehari_messages WHERE message_read = 0 AND to_id = ' . $user_id;

    // Perform Query
    $result_message_count = mysql_query($query_message_count, $db_dehari);
    $result_array_message_count = mysql_fetch_assoc($result_message_count);

    $new_messages_count = $result_array_message_count['new_messages_count'];
    
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dehari - Notifications</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:600,400' rel='stylesheet' type='text/css'>
    <link href="css/home_dehari.css" rel="stylesheet">
    <link rel='shortcut icon' href='favicon.ico' type='image/x-icon'/ >
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll main_header_button" href="home_dehari.php"><img src="images/logo_white.png" alt="Dehari" height="90"> </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="home_dehari.php">home</a>
                    </li>
                    <li>
                        <a href="find_dehari.php">find dehari</a>
                    </li>
                    <li>
                        <a href="post_dehari.php">post dehari</a>
                    </li>
                    <li>
                        <a href="messages.php">messages<?=($new_messages_count > 0)? '(' . $new_messages_count . ')': '' ?></a>
                    </li>
                    <li>
                        <a href="notifications.php">notifications</a>
                    </li>
                    <li>
                        <a data-toggle="dropdown" href="#" role="button" aria-expanded="false"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
                        <ul class="dropdown-menu" role="menu">
                          <li>
                            <a href="settings.php">Settings</a>
                          </li>
                          <li>
                            <a href="server/logout.php">Logout</a>
                          </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>


    <!-- Ratings & Mode Toggle Section -->
    <section id="notifications_section" class="light_green_background margined_section">
        <div class="container">
            <div class="row">
                <h3> new </h3>
    <? if (empty($notification_list)) {?>
        <p>There are no new notifications</p>
    <?}
    foreach ($notification_list as $notification_row) { 

                            $notification_message_string = explode(",", $notification_row['notification_message']);
                            $notification_message = $notification_message_string[0];
                            $notification_dehari_id = $notification_message_string[1];
        ?>
                <div class="col-md-10">
                            <p>
                                <? echo $notification_message . ' <strong><a href="dehari_details.php?dehari_id=' . $notification_dehari_id . '">' . get_title_from_dehari_id($notification_dehari_id) . '</a></strong>'?>
                            </p>
                </div>
                <div class="col-md-2 align-right">
                    <?=time_elapsed_string($notification_row['notification_timestamp'])?>
                </div>
    <? } ?>
            </div>
        </div> 
    </section>

    <section id="notifications_old_section" class="gray_background margined_section">
        <div class="container">
            <div class="row">
                <h3> old </h3>
    <? foreach ($notification_list_old as $notification_row) { 

                            $notification_message_string = explode(",", $notification_row['notification_message']);
                            $notification_message = $notification_message_string[0];
                            $notification_dehari_id = $notification_message_string[1];
        ?>
                <div class="col-md-10">
                            <p>
                                <? echo $notification_message . ' <strong><a href="dehari_details.php?dehari_id=' . $notification_dehari_id . '">' . get_title_from_dehari_id($notification_dehari_id) . '</a></strong>'?>
                            </p>
                </div>
                <div class="col-md-2 align-right">
                    <?=time_elapsed_string($notification_row['notification_timestamp'])?>
                </div>
    <? } ?>
                </div>
            </div>
        </div> 
    </section>

    <footer>
        <div class="container">
            <div class="row">

                <div class="col-md-12">
                  <center>
                    <ul class="list-inline social-buttons">
                        <li><a href="https://github.com/rehanrasool" target="_blank">Home</a>
                        </li>
                        <li><a href="http://www.linkedin.com/pub/rehan-rasool/85/676/102" target="_blank">About</a>
                        </li>
                        <li><a href="https://www.facebook.com/rehan.rasool" target="_blank">Help</a>
                        </li>
                        <li><a href="https://github.com/rehanrasool" target="_blank">Careers</a>
                        </li>
                        <li><a href="http://www.linkedin.com/pub/rehan-rasool/85/676/102" target="_blank">Terms</a>
                        </li>
                        <li><a href="https://www.facebook.com/rehan.rasool" target="_blank">Privacy</a>
                        </li>
                        <li><a href="https://www.facebook.com/rehan.rasool" target="_blank">Cookies</a>
                        </li>
                        <li><a href="https://www.facebook.com/rehan.rasool" target="_blank">Advertise</a>
                        </li>
                        <li><a href="https://www.facebook.com/rehan.rasool" target="_blank">Contact</a>
                        </li>
                      </ul>
                    </center>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>