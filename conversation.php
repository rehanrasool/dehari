<?php
    include 'server/find_dehari.php';
    session_start();
    if (!isset($_SESSION['user_id'])) {
       header("Location: index.php");
    }
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];

    $convo_id = $_GET['conversation_id'];

    // get notifications
    $db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
        mysql_select_db('bluecu6_dehari', $db_dehari);

    $query = 'SELECT COUNT(*) as notifications_count FROM dehari_notifications WHERE notification_read = 0 AND notification_user_id = ' . $user_id;

    // Perform Query
    $result = mysql_query($query, $db_dehari);
    $result_array = mysql_fetch_assoc($result);

    $notifications_count = $result_array['notifications_count'];


    // mark messages read
    $query_update = 'UPDATE dehari_messages SET message_read = 1 WHERE to_id = ' . $user_id . ' AND conversation_id = ' . $convo_id;

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

    <title>Dehari - Conversation</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:600,400' rel='stylesheet' type='text/css'>
    <link href="css/home_dehari.css" rel="stylesheet">
    <link href="css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
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


    <!-- Ratings & Mode Toggle Section -->
    <section id="find_dehari_section">
        <div class="container">
            
    <?
        $db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
            mysql_select_db('bluecu6_dehari', $db_dehari);

            $query = 'SELECT * FROM dehari_messages WHERE conversation_id = ' .  $convo_id . ' ORDER BY time_stamp DESC';

            // Perform Query
            $result = mysql_query($query, $db_dehari);

            $message_list = array();
            while ($message_list_row = mysql_fetch_assoc($result))
                    $message_list[] = $message_list_row;

            if ($message_list[0]['from_id'] == $user_id) {
                $conversation_with_id = $message_list[0]['to_id'];
            } else {
                $conversation_with_id = $message_list[0]['from_id'];
            }


            $query_convo = 'SELECT * FROM dehari_conversation WHERE conversation_id = ' .  $convo_id . ';';

            // Perform Query
            $result_convo = mysql_query($query_convo, $db_dehari);
            $convo_array = mysql_fetch_assoc($result_convo);

    ?>
            <div class = "col-md-12">
                <div class = "col-md-4"></div> 
                <div class = "col-md-4"><h2><?=$convo_array['title']?></h2></div> 
                <div class = "col-md-4"></div> 

            </div> 

            <div class = "col-md-12">
                <form class="form-inline" method="post" action="server/messages.php">
                    <div class="col-md-10">
                        <input type="textarea" name="message_content" value="" placeholder="Enter Message" required>
                    </div>
                    <div>
                        <input name="conversation_id" type="hidden" value="<?=$convo_id?>">
                        <input name="message_from" type="hidden" value="<?=$user_id?>">
                        <input name="message_to" type="hidden" value="<?=$conversation_with_id?>">
                        <input name="send_message" type="hidden" value="1">
                    </div>
                    <div class="col-md-2">
                         <input type="submit" class="red_button" value="SEND">
                    </div>
                </form>
            </div>

    <? foreach ($message_list as $message_row) { 
        $message_user_name = get_username_from_userid($message_row['from_id']);
        ?>
        <div class = "col-md-12"> &nbsp;</div>
        <?if ($message_row['from_id'] == $user_id) {?>
            <div class="col-md-12 gray_background rounded_corners">
                <div class="col-md-1">
                <?if (file_exists ("images/profile/" . $user_id) !== false) {?>
                    <img class="circular_image" src=<?="images/profile/" . $user_id?> alt="profile_pic" height="150" width="150">
                <?} else {?>
                    <img class="circular_image" src="images/profile/0.png" alt="profile_pic" height="150" width="150">
                <?}?>
                </div>
                <div class = "col-md-11">
                    <div class = "col-md-12"><h3><?=$message_user_name?></h3></div> 
                    <div class = "col-md-9"><h4><?=$message_row['message']?><h4></div> 
                    <div class = "col-md-3 text-right"><h6><?=time_elapsed_string($message_row['time_stamp'])?></h6></div> 
                </div>
            </div>
        <? } else {?>
<!--             <div class = "col-md-12 text-right dark_gray_background rounded_corners">
                <div class = "col-md-12"><h3><?=$message_user_name?></h3></div> 
                <div class = "col-md-3 text-left"><h6><?=$message_row['time_stamp']?></h6></div> 
                <div class = "col-md-9"><h4><?=$message_row['message']?><h4></div> 
            </div> -->
            <div class="col-md-12 light_green_background rounded_corners">
                <div class="col-md-1">
                <?if (file_exists ("images/profile/" . $conversation_with_id) !== false) {?>
                    <img class="circular_image" src=<?="images/profile/" . $conversation_with_id?> alt="profile_pic" height="150" width="150">
                <?} else {?>
                    <img class="circular_image" src="images/profile/0.png" alt="profile_pic" height="150" width="150">
                <?}?>
                </div>
                <div class = "col-md-11">
                    <div class = "col-md-12"><h3><?=$message_user_name?></h3></div> 
                    <div class = "col-md-9"><h4><?=$message_row['message']?><h4></div> 
                    <div class = "col-md-3 text-right"><h6><?=time_elapsed_string($message_row['time_stamp'])?></h6></div> 
                </div>
            </div>
        <?}?>
        
            
    <?}?>

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
    

    <script src="js/messages.js" type="text/javascript"></script>
    <script src="js/jquery.dataTables.min.js" type="text/javascript"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>