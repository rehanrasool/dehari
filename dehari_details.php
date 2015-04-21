<?php

    include 'server/find_dehari.php';

    session_start();
    if (!isset($_SESSION['user_id'])) {
       header("Location: index.php");
    }
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    $dehari_id = $_GET['dehari_id'];

    // get notifications
    $db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
        mysql_select_db('bluecu6_dehari', $db_dehari);

    $query = 'SELECT COUNT(*) as notifications_count FROM dehari_notifications WHERE notification_read = 0 AND notification_user_id = ' . $user_id;

    // Perform Query
    $result = mysql_query($query, $db_dehari);
    $result_array = mysql_fetch_assoc($result);

    $notifications_count = $result_array['notifications_count'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dehari - Home</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:600,400' rel='stylesheet' type='text/css'>
    <link href="css/home_dehari.css" rel="stylesheet">
    <link href="css/jquery.dataTables.css" rel="stylesheet" type="text/css" />

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
                        <a href="#">messages</a>
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



        $result_dehari = find_dehari_item($dehari_id);
        $result_dehari_array = json_decode($result_dehari, true);
        if (isset($result_dehari_array['dehari_bids_array'])){
            $dehari_bids =  $result_dehari_array['dehari_bids_array'];
        } else {
            $dehari_bids =  array();
        }

        // Perform Query

        $dehari_user_id = $result_dehari_array['dehari_user_id'];
        $dehari_title = $result_dehari_array['dehari_title'];
        $dehari_address = $result_dehari_array['dehari_address'];
        $dehari_phone = $result_dehari_array['dehari_phone'];
        $dehari_city = $result_dehari_array['dehari_city'];
        $dehari_budget = $result_dehari_array['dehari_budget'];
        $dehari_description = $result_dehari_array['dehari_description'];
        $dehari_category = $result_dehari_array['dehari_category'];
        $dehari_date = $result_dehari_array['dehari_date'];
        //$dehari_bids = $result_dehari_array['dehari_bids'];
        $dehari_status = $result_dehari_array['dehari_status'];
        $dehari_selected_bid = $result_dehari_array['dehari_selected_bid'];
        $dehari_client_rating = $result_dehari_array['dehari_client_rating'];
        $dehari_worker_rating = $result_dehari_array['dehari_worker_rating'];

        $db_dehari = mysqli_connect("localhost", "bluecu6_rehan", ".dehari.", "bluecu6_dehari");

        /* check connection */
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        foreach ($dehari_bids as $bid_user_id => $bid_value) {

            $bids_array[$bid_user_id] = array();
            $bids_array[$bid_user_id]['dehari_id'] = $dehari_id;
            $bids_array[$bid_user_id]['bid_user_id'] = $bid_user_id;
            $bids_array[$bid_user_id]['bid_value'] = $dehari_bids[$bid_user_id]['bid'];
            $bids_array[$bid_user_id]['bid_message'] = $dehari_bids[$bid_user_id]['message'];

            $query = 'SELECT * FROM dehari_user WHERE user_id = ' . $bid_user_id;

            $result = mysqli_query($db_dehari, $query);
            $result_array = mysqli_fetch_assoc($result);

            if (!$result) {
                $message  = 'Invalid query: ' . mysql_error() . "\n";
                $message .= 'Whole query: ' . $query;
                return $message;
            } else {
                $bids_array[$bid_user_id]['bid_user_name'] = $result_array['user_name'];
                $bids_array[$bid_user_id]['bid_user_rating'] = $result_array['user_incoming_rating'];
            }
        }

        // close connection
        mysqli_close($db_dehari);

?>

    <!-- Dehari Details Section -->
    <section id="dehari_details">
        <div class="container">
            <div class="row">

                <div class="col-md-12">
                    <h2>
                      <?=$dehari_title?>
                    </h2>
                    <h4>
                      Description:
                    </h4><?=$dehari_description?>
                </div>

                <div class="col-md-6">
                    <h4>
                      Address:
                    </h4><?=$dehari_address?>
                    <h4>
                      City:
                    </h4><?=$dehari_city?>
                    <h4>
                      Phone:
                    </h4><?=$dehari_phone?>
                </div>
                <div class="col-md-6">
                    <h4>
                      Category:
                    </h4><?=$dehari_category?>
                    <h4>
                      Budget:
                    </h4><?=$dehari_budget?>
                    <h4>
                      Posted By:
                    </h4><a href="profile.php?user=<?=$dehari_user_id?>"><?=$dehari_user_id?></a>
                </div>

            

            </div>

        </div>
    </section>

    <!-- Dehari Bids Section -->
    <section id="dehari_bids" class="gray_background">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3>
                      Bids
                    </h3>

                    <? if ($user_id != $dehari_user_id) {?>
                    <form class="form-inline" method="post" action="server/find_dehari.php">
                        <div class="col-md-2">
                            <input type="number" min="1" name="dehari_bid" placeholder="Enter Bid" required>
                        </div>
                        <div class="col-md-8">
                            <input type="textarea" name="dehari_bid_message" value="" placeholder="Enter Message for Client" required>
                        </div>
                        <div>
                            <input name="dehari_id" type="hidden" value="<?=$dehari_id?>">
                            <input name="select_dehari" type="hidden" value="1">
                        </div>
                        <div class="col-md-2">
                             <input type="submit" id="select_dehari_button" class="green_button" value="SEND">
                        </div>
                    </form>

                    
                    <?}?>
                </div>

                

                <div class="col-md-2">
                    <h4>
                      Value
                    </h4>
                </div>
                <div class="col-md-2">
                    <h4>
                      User
                    </h4>
                </div>
                <div class="col-md-2">
                    <h4>
                      Rating
                    </h4>
                </div>
                <div class="col-md-4">
                    <h4>
                      Message
                    </h4>
                </div>
                <div class="col-md-2">
                    <h4>
                    <? if ($user_id == $dehari_user_id) { ?>
                      Action
                    <?} else {?>
                        &nbsp;
                    <?}?>
                    </h4>
                </div>
                <? if (!empty($bids_array)) {
                    foreach($bids_array as $bid_user_id => $bid_value) {
                            //$bids_array[$bid_user_id]['dehari_id']
                            //$bids_array[$bid_user_id]['bid_user_id']
                            //$bids_array[$bid_user_id]['bid_value']
                            //$bids_array[$bid_user_id]['bid_message']
                            //$bids_array[$bid_user_id]['bid_user_name']
                            //$bids_array[$bid_user_id]['bid_user_rating']
                ?>
                <div class="col-md-2">
                    <?=$bids_array[$bid_user_id]['bid_value']?>
                </div>
                <div class="col-md-2">
                    <a href="profile.php?user=<?=$bid_user_id?>"><?=$bids_array[$bid_user_id]['bid_user_name']?></a>
                </div>
                <div class="col-md-2">
                    <?=$bids_array[$bid_user_id]['bid_user_rating']?>
                </div>
                <div class="col-md-4">
                    <?=$bids_array[$bid_user_id]['bid_message']?>
                </div>
                <div class="col-md-2">
                    <form class="form-inline" method="post" action="server/find_dehari.php">
                        <div>
                            <input name="dehari_id" type="hidden" value="<?=$dehari_id?>">
                            <input name="select_bid" type="hidden" value="1">
                            <input name="bid_user" type="hidden" value="<?=$bid_user_id?>">
                            <input name="bid_value" type="hidden" value="<?=$bids_array[$bid_user_id]['bid_value']?>">
                        </div>
                        <div class="col-md-12">
                            <? if ($user_id == $dehari_user_id) { ?>
                                <? if (empty($dehari_selected_bid)) { ?>
                                    <input type="submit" class="red_button" value="ACCEPT">
                                <?} else {?>
                                    &nbsp;
                                <?}?>
                            <?} else {?>
                                &nbsp;
                            <?}?>
                        </div>
                    </form>
                </div>

                    <?}
                }?>
            </div>

        </div>
    </section>

<? if (!empty($dehari_selected_bid)) {?>
    <!-- Dehari In Progress Section -->
    <section id="dehari_in_progress">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3>
                      Bid Selected
                    </h3>
                </div>

                <div class="col-md-3">
                    <h4>
                      Value
                    </h4>
                </div>
                <div class="col-md-3">
                    <h4>
                      User
                    </h4>
                </div>
                <div class="col-md-3">
                    <h4>
                      Rating
                    </h4>
                </div>
                <div class="col-md-3">
                    <h4>
                      Action
                    </h4>
                </div>
                <? if (!empty($dehari_selected_bid)) {
                    $bid_selected_split = explode(":", $dehari_selected_bid);
                    $dehari_selected_user_id = $bid_selected_split[0];
                    $dehari_selected_bid_value = $bid_selected_split[1];
                ?>
                <div class="col-md-3">
                    <?=$dehari_selected_bid_value?>
                </div>
                <div class="col-md-3">
                    <a href="profile.php?user=<?=$dehari_selected_user_id?>"><?=$bids_array[$dehari_selected_user_id]['bid_user_name']?></a>
                </div>
                <div class="col-md-3">
                    <?=$bids_array[$dehari_selected_user_id]['bid_user_rating']?>
                </div>
                <div class="col-md-3">
                    MESSAGE
                </div>

                <?}?>
            </div>

        </div>
    </section>
<?}?>


<? if ((!empty($dehari_selected_bid)) && ($user_id == $dehari_user_id || $user_id == $dehari_selected_user_id)) {?>
    <!-- Dehari Completed Section -->
    <section id="dehari_completed" class="gray_background">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3>
                      Summary
                    </h3>
                </div>

                <div class="col-md-3">
                    <h4>
                        Rating Received
                    </h4> 
                        <?  
                        if ($user_id == $dehari_user_id) {
                            $user_is_client = true;
                        } else { // $user_id == $dehari_selected_user_id
                            $user_is_client = false;
                        }
                        
                        if ($user_is_client && $dehari_client_rating != -1.0) {
                            echo $dehari_client_rating;
                        } else if (!$user_is_client && $dehari_worker_rating != -1.0) {
                            echo $dehari_worker_rating;
                        } else {
                            echo "NOT RECEIVED YET";
                        }?>
                </div>
                <div class="col-md-3">
                    <h4>
                        Feedback Received
                    </h4>
                        FEEDBACK GOES HERE
                </div>

                <div class="col-md-3">
                    <h4>
                        Rating Given
                    </h4> 
                        <? if ($user_is_client) {
                            if ($dehari_worker_rating != -1.0) {
                                echo $dehari_worker_rating;
                            } else {?>

                            <form class="form-inline" method="post" action="server/find_dehari.php">
                                <div>
                                    <input name="dehari_id" type="hidden" value="<?=$dehari_id?>">
                                    <input name="outgoing_dehari_completed" type="hidden" value="1">
                                </div>
                                <div class="col-md-6">
                                    <input type="number" min="0" max="5" name="worker_rating" placeholder="Rate">
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" class="red_button" value="RATE">
                                </div>
                            </form>

                            <?}
                            
                        } else {
                            if ($dehari_client_rating != -1.0) {
                                echo $dehari_client_rating;
                            } else {?>

                                <form class="form-inline" method="post" action="server/find_dehari.php">
                                    <div>
                                        <input name="dehari_id" type="hidden" value="<?=$dehari_id?>">
                                        <input name="incoming_dehari_completed" type="hidden" value="1">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" min="0" max="5" name="client_rating" placeholder="Rate">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="submit" class="red_button" value="RATE">
                                    </div>
                                </form>

                            <?}
                            
                        }?>   

                </div>
                <div class="col-md-3">
                    <h4>
                        Feedback Given
                    </h4>
                        FEEDBACK GOES HERE
                </div>
            </div>

        </div>
    </section>
<?}?>

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
    

    <script src="js/dehari_details.js" type="text/javascript"></script>
    <script src="js/jquery.dataTables.min.js" type="text/javascript"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>