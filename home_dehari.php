<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
       // header("Location: index.html");
       // exit();
    }
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];



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
                <a class="navbar-brand page-scroll main_header_button" href="#"><img src="images/logo_white.png" alt="Dehari" height="90"> </a>
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
                        <a href="#">notifications</a>
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

        $query_user = 'SELECT * FROM dehari_user WHERE user_id = ' . $user_id ;

        // Perform Query
        $result_user = mysqli_query($db_dehari, $query_user);
        $result_user_array = mysqli_fetch_assoc($result_user);


        // INCOMING
        $user_incoming = $result_user_array['user_incoming'];
        $user_incoming_rating = $result_user_array['user_incoming_rating'];

        $incoming_bids = trim($result_user_array['user_incoming_bids'], ",");
        $incoming_in_progress = trim($result_user_array['user_incoming_in_progress'], ",");
        $incoming_completed = trim($result_user_array['user_incoming_completed'], ",");

        $query_incoming_bids = 'SELECT * FROM dehari_list WHERE dehari_id IN (' . $incoming_bids . ');';
        $query_incoming_in_progress = 'SELECT * FROM dehari_list WHERE dehari_id IN (' . $incoming_in_progress . ');';
        $query_incoming_completed = 'SELECT * FROM dehari_list WHERE dehari_id IN (' . $incoming_completed . ');';

        $result_incoming_bids = mysqli_query($db_dehari, $query_incoming_bids);
        $result_incoming_bids_array = array();
        if ($result_incoming_bids) {
            while ($result_incoming_bids_row = mysqli_fetch_assoc($result_incoming_bids))
                $result_incoming_bids_array[] = $result_incoming_bids_row;
        }
        

        $result_incoming_in_progress = mysqli_query($db_dehari, $query_incoming_in_progress);
        $result_incoming_in_progress_array = array();
        if ($result_incoming_in_progress) {
            while ($result_incoming_in_progress_row = mysqli_fetch_assoc($result_incoming_in_progress))
                    $result_incoming_in_progress_array[] = $result_incoming_in_progress_row;
        }

        $result_incoming_completed = mysqli_query($db_dehari, $query_incoming_completed);
        $result_incoming_completed_array = array();
        if ($result_incoming_completed) {
            while ($result_incoming_completed_row = mysqli_fetch_assoc($result_incoming_completed))
                    $result_incoming_completed_array[] = $result_incoming_completed_row;
        }


        //echo 'REHAN';
        //print_r( $result_incoming_bids_array );
        //echo 'REHAN';
        //print_r( $result_incoming_in_progress_array );
        //echo 'REHAN';
        //print_r( $result_incoming_completed_array );


        // OUTGOING
        $user_outgoing = $result_user_array['user_outgoing'];
        $user_outgoing_rating = $result_user_array['user_outgoing_rating'];

        $outgoing_posted = trim($result_user_array['user_outgoing_posted'], ",");
        $outgoing_bids_received = trim($result_user_array['user_outgoing_bids_received'], ",");
        $outgoing_in_progress = trim($result_user_array['user_outgoing_in_progress'], ",");
        $outgoing_completed = trim($result_user_array['user_outgoing_completed'], ",");

        $query_outgoing_posted = 'SELECT * FROM dehari_list WHERE dehari_id IN (' . $outgoing_posted . ');';
        $query_outgoing_bids_received = 'SELECT * FROM dehari_list WHERE dehari_id IN (' . $outgoing_bids_received . ');';
        $query_outgoing_in_progress = 'SELECT * FROM dehari_list WHERE dehari_id IN (' . $outgoing_in_progress . ');';
        $query_outgoing_completed = 'SELECT * FROM dehari_list WHERE dehari_id IN (' . $outgoing_completed . ');';

        $result_outgoing_posted = mysqli_query($db_dehari, $query_outgoing_posted);
        $result_outgoing_posted_array = array();
        if ($result_outgoing_posted) {
            while ($result_outgoing_posted_row = mysqli_fetch_assoc($result_outgoing_posted))
                    $result_outgoing_posted_array[] = $result_outgoing_posted_row;
        }

        $result_outgoing_bids_received = mysqli_query($db_dehari, $query_outgoing_bids_received);
        $result_outgoing_bids_received_array = array();
        if ($result_outgoing_bids_received) {
            while ($result_outgoing_bids_received_row = mysqli_fetch_assoc($result_outgoing_bids_received))
                    $result_outgoing_bids_received_array[] = $result_outgoing_bids_received_row;
        }

        $result_outgoing_in_progress = mysqli_query($db_dehari, $query_outgoing_in_progress);
        $result_outgoing_in_progress_array = array();
        if ($result_outgoing_in_progress) {
            while ($result_outgoing_in_progress_row = mysqli_fetch_assoc($result_outgoing_in_progress))
                    $result_outgoing_in_progress_array[] = $result_outgoing_in_progress_row;
        }

        $result_outgoing_completed = mysqli_query($db_dehari, $query_outgoing_completed);
        $result_outgoing_completed_array = array();
        if ($result_outgoing_completed) {
            while ($result_outgoing_completed_row = mysqli_fetch_assoc($result_outgoing_completed))
                    $result_outgoing_completed_array[] = $result_outgoing_completed_row;
        }

        // close connection
        mysqli_close($db_dehari);

?>

    <!-- Main Profile Section -->
    <section id="home_profile">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <img src="images/profile_pic.png" alt="profile_pic" height="150">
                </div>

                <div class="col-md-10">
                    <h2>
                      <?=$user_name?>
                    </h2>

                    <h3>
                      <?=$user_name?>
                    </h3>

                    <h4>
                        Lorem ipsum dolor sit amet, quo choro eleifend in. Ad sit idque debet antiopam, no stet noluisse phaedrum eum. Per exerci facilisis ut. Sea cu mutat atomorum.
                    </h4>
                </div>
            

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
    </section>

    <!-- Ratings & Mode Toggle Section -->
    <section id="home_mode_toggle" class="gray_background">
        <!-- Page Content -->
        <div class="container">
            <div class="row" id="user_overview">

                <div class="col-md-3"> </div>
                <div class="col-md-3">
                    <input id="worker_mode_button" class="red_button" type="button" value="WORKER MODE">
                </div>

                <div class="col-md-3">
                    <input id="client_mode_button" class="green_button" type="button" value="CLIENT MODE">
                </div>
                <div class="col-md-3"> </div>

            </div>
        </div>
    </section>

    <!-- Worker Section -->
    <section id="worker_table_section" hidden>
            <div class="col-md-12" id="incoming_table_section">

                <center> <h2> WORKER MODE </h2> </center>

                <div class="row" id="incoming_dehari_filters">
                    <div class="col-md-4">
                        Title:<input id="incoming_dehari_title" type="text"/>
                    </div>
                    <div class="col-md-4">  
                        Status:
                        <select id="incoming_dehari_status">
                          <option selected value=""></option>
                          <option value="Bids Submitted">Bids Submitted</option>
                          <option value="Work In Progress">Work In Progress</option>
                          <option value="Work Completed">Work Completed</option>
                        </select>
                    </div>
                     <div class="col-md-4">  
                        <a href="#clear" id="clear_incoming_search_button" title="clear filter">[clear]</a>
                        
                        <input id="incoming_search_button" type="button" value="SEARCH">
                    </div>
                </div>



                <table class="table incoming_dehari_table display" >
                    <thead>
                        <tr>
                            <th data-sort-initial="true">
                                Title
                            </th>
                            <th data-sort="true">
                                Category
                            </th>
                            <th data-hide="phone,tablet">
                                City
                            </th>
                            <th data-hide="phone,tablet">
                                Date Posted
                            </th>
                            <th data-hide="phone">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
<? foreach ($result_incoming_bids_array as $dehari_row) { ?>
                        <tr>
                            <td><a href="#" class="incoming_bids_submitted_details" id="dehari_list_id_<?=$dehari_row['dehari_id']?>"><?=$dehari_row['dehari_title']?></a></td>
                            <td><?=$dehari_row['dehari_category']?></td>
                            <td><?=$dehari_row['dehari_city']?></td>
                            <td data-value="78025368997"><?=(new DateTime($dehari_row['dehari_date']))->format('m/d/Y')?></td>
                            <td data-value="1">Bids Submitted</td>
                        </tr>
<? } ?>

<? foreach ($result_incoming_in_progress_array as $dehari_row) { ?>
                        <tr>
                            <td><a href="#" class="incoming_in_progress_details" id="dehari_list_id_<?=$dehari_row['dehari_id']?>"><?=$dehari_row['dehari_title']?></a></td>
                            <td><?=$dehari_row['dehari_category']?></td>
                            <td><?=$dehari_row['dehari_city']?></td>
                            <td data-value="78025368997"><?=(new DateTime($dehari_row['dehari_date']))->format('m/d/Y')?></td>
                            <td data-value="1">Work In Progress</td>
                        </tr>
<? } ?>

<? foreach ($result_incoming_completed_array as $dehari_row) { ?>
                        <tr>
                            <td><a href="#" class="incoming_completed_details" id="dehari_list_id_<?=$dehari_row['dehari_id']?>"><?=$dehari_row['dehari_title']?></a></td>
                            <td><?=$dehari_row['dehari_category']?></td>
                            <td><?=$dehari_row['dehari_city']?></td>
                            <td data-value="78025368997"><?=(new DateTime($dehari_row['dehari_date']))->format('m/d/Y')?></td>
                            <td data-value="1">Work Completed</td>
                        </tr>
<? } ?>
                    </tbody>
                </table>

            </div>
    </section>

    <!-- Client Section -->
    <section id="client_table_section" hidden>    
            <div class="col-md-12" id="outgoing_table_section">
                
                <center> <h2> CLIENT MODE </h2> </center>
                <div class="row" id="outgoing_dehari_filters">
                    <div class="col-md-4">
                        Title:<input id="outgoing_dehari_title" type="text"/>
                    </div>
                    <div class="col-md-4">  
                        Status:
                        <select id="outgoing_dehari_status">
                          <option selected value=""></option>
                          <option value="Work Posted">Work Posted</option>
                          <option value="Bids Received">Bids Received</option>
                          <option value="Work In Progress">Work In Progress</option>
                          <option value="Work Completed">Work Completed</option>
                        </select>
                    </div>
                     <div class="col-md-4">  
                        <a href="#clear" id="clear_outgoing_search_button" title="clear filter">[clear]</a>
                        
                        <input id="outgoing_search_button" type="button" value="SEARCH">
                    </div>
                </div>



                <table class="table outgoing_dehari_table display" >
                    <thead>
                        <tr>
                            <th data-sort-initial="true">
                                Title
                            </th>
                            <th data-sort="true">
                                Category
                            </th>
                            <th data-hide="phone,tablet">
                                City
                            </th>
                            <th data-hide="phone,tablet">
                                Date Posted
                            </th>
                            <th data-hide="phone">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>

<? foreach ($result_outgoing_posted_array as $dehari_row) { ?>
                        <tr>
                            <td><a href="#" class="outgoing_posted_details" id="dehari_list_id_<?=$dehari_row['dehari_id']?>"><?=$dehari_row['dehari_title']?></a></td>
                            <td><?=$dehari_row['dehari_category']?></td>
                            <td><?=$dehari_row['dehari_city']?></td>
                            <td data-value="78025368997"><?=(new DateTime($dehari_row['dehari_date']))->format('m/d/Y')?></td>
                            <td data-value="1">Work Posted</td>
                        </tr>
<? } ?>

<? foreach ($result_outgoing_bids_received_array as $dehari_row) { ?>
                        <tr>
                            <td><a href="#" class="outgoing_bids_received_details" id="dehari_list_id_<?=$dehari_row['dehari_id']?>"><?=$dehari_row['dehari_title']?></a></td>
                            <td><?=$dehari_row['dehari_category']?></td>
                            <td><?=$dehari_row['dehari_city']?></td>
                            <td data-value="78025368997"><?=(new DateTime($dehari_row['dehari_date']))->format('m/d/Y')?></td>
                            <td data-value="1">Bids Received</td>
                        </tr>
<? } ?>

<? foreach ($result_outgoing_in_progress_array as $dehari_row) { ?>
                        <tr>
                            <td><a href="#" class="outgoing_in_progress_details" id="dehari_list_id_<?=$dehari_row['dehari_id']?>"><?=$dehari_row['dehari_title']?></a></td>
                            <td><?=$dehari_row['dehari_category']?></td>
                            <td><?=$dehari_row['dehari_city']?></td>
                            <td data-value="78025368997"><?=(new DateTime($dehari_row['dehari_date']))->format('m/d/Y')?></td>
                            <td data-value="1">Work In Progress</td>
                        </tr>
<? } ?>

<? foreach ($result_outgoing_completed_array as $dehari_row) { ?>
                        <tr>
                            <td><a href="#" class="outgoing_completed_details" id="dehari_list_id_<?=$dehari_row['dehari_id']?>"><?=$dehari_row['dehari_title']?></a></td>
                            <td><?=$dehari_row['dehari_category']?></td>
                            <td><?=$dehari_row['dehari_city']?></td>
                            <td data-value="78025368997"><?=(new DateTime($dehari_row['dehari_date']))->format('m/d/Y')?></td>
                            <td data-value="1">Work Completed</td>
                        </tr>
<? } ?>
                    </tbody>
                </table>
                    

            </div>
            
    </section>

    <section class="white_background"> 
        &nbsp;
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

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>