<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
       header("Location: index.php");
    }
    $user_id = $_SESSION['user_id'];
    $profile_user_id = $_GET['user'];


    // get notifications
    $db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
        mysql_select_db('bluecu6_dehari', $db_dehari);

    $query = 'SELECT COUNT(*) as notifications_count FROM dehari_notifications WHERE notification_read = 0 AND notification_user_id = ' . $user_id;

    // Perform Query
    $result = mysql_query($query, $db_dehari);
    $result_array = mysql_fetch_assoc($result);

    $notifications_count = $result_array['notifications_count'];

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

    <title>Dehari - Profile</title>

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
                        <a href="notifications.php">notifications<?=($notifications_count > 0)? '(' . $notifications_count . ')': '' ?></a>
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

<?
    //$db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
        //mysql_select_db('bluecu6_dehari', $db_dehari);

        $db_dehari = mysqli_connect("localhost", "bluecu6_rehan", ".dehari.", "bluecu6_dehari");

        /* check connection */
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $query_user = 'SELECT * FROM dehari_user WHERE user_id = ' . $profile_user_id ;

        // Perform Query
        $result_user = mysqli_query($db_dehari, $query_user);
        $result_user_array = mysqli_fetch_assoc($result_user);

        $user_name = $result_user_array['user_name'];

        if ($result_user_array['user_full_name'] == '') {
            $user_full_name = '';
        } else {
            $user_full_name = $result_user_array['user_full_name'];
        }

        if ($result_user_array['user_description'] == '') {
            $user_description = '';
        } else {
            $user_description = $result_user_array['user_description'];
        }

        $user_incoming = $result_user_array['user_incoming'];
        $user_incoming_rating = $result_user_array['user_incoming_rating'];
        $user_outgoing = $result_user_array['user_outgoing'];
        $user_outgoing_rating = $result_user_array['user_outgoing_rating'];


        // close connection
        mysqli_close($db_dehari);

?>

    <!-- Main Profile Section -->
    <section id="profile_overview">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-2">
                        <?if (file_exists ("images/profile/" . $profile_user_id) !== false) {?>
                            <img src=<?="images/profile/" . $profile_user_id?> alt="profile_pic" height="150" width="150">
                        <?} else {?>
                            <img src="images/profile/0.png" alt="profile_pic" height="150" width="150">
                        <?}?>
                        
                    </div>

                    <div class="col-md-10">
                        <h2>
                          <?=$user_name?>
                        </h2>

                        <h3>
                          <?=$user_full_name?>
                        </h3>

                        <h4>
                            <?=$user_description?>
                        </h4>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-3">
                        <h3>
                            worker rating:
                            <br/>
                            earnings:
                        </h3> 
                    </div>
                    <div class="col-md-3">
                        <h3>
                            <? for ($i = 1; $i <= $user_incoming_rating; $i++) { ?>
                                    <span class="glyphicon glyphicon-star dehari_red" aria-hidden="true"></span>
                            <? } ?>
                            
                            <br/>
                            PKR <?=$user_incoming?>
                        </h3> 
                    </div>
                    <div class="col-md-3">
                        <h3>
                            client rating:
                            <br/>
                            expenditures:
                        </h3>
                    </div>
                    <div class="col-md-3">
                        <h3>
                            <? for ($i = 1; $i <= $user_outgoing_rating; $i++) { ?>
                                    <span class="glyphicon glyphicon-star dehari_brown" aria-hidden="true"></span>
                            <? } ?>
                            
                            <br/>
                            PKR <?=$user_outgoing?>
                        </h3>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <section class="gray_background"> 
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4 col-md-offset-4">
                        <input id="profile_send_message_user_id" type="hidden" value="<?=$profile_user_id?>">
                        <input id="profile_send_message_button" class="red_button" type="button" value="SEND MESSAGE">
                    </div>
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

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    

    <script src="js/home_dehari.js" type="text/javascript"></script>
    <script src="js/jquery.dataTables.min.js" type="text/javascript"></script>

<?
    if ($_SESSION['user_mode'] == 'worker') {
        echo "<script>$(document).ready(function(){";
        echo "$(\"#worker_mode_button\").click();";
        echo "});</script>";
    } else if ($_SESSION['user_mode'] == 'client') {
        echo "<script>$(document).ready(function(){";
        echo "$(\"#client_mode_button\").click();";
        echo "});</script>";
    }
    
?>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/profile.js"></script>

</body>

</html>