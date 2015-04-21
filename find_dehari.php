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

    $query = 'SELECT COUNT(*) as notifications_count FROM dehari_notifications WHERE notification_read = 0 AND notification_user_id = ' . $user_id;
    // Perform Query
    $result = mysql_query($query, $db_dehari);
    $result_array = mysql_fetch_assoc($result);

    $notifications_count = $result_array['notifications_count'];

    // get cities list
    $query_configuration_cities = 'SELECT * FROM dehari_configure WHERE configure_property = "cities";';

    // Perform Query
    $result_configuration_cities = mysql_query($query_configuration_cities, $db_dehari);
    $result_array_configuration_cities = mysql_fetch_assoc($result_configuration_cities);

    $cities_list = explode(",", $result_array_configuration_cities['configure_value']);

    // get categories list
    $query_configuration_categories = 'SELECT * FROM dehari_configure WHERE configure_property = "categories";';

    // Perform Query
    $result_configuration_categories = mysql_query($query_configuration_categories, $db_dehari);
    $result_array_configuration_categories = mysql_fetch_assoc($result_configuration_categories);

    $categories_list = explode(",", $result_array_configuration_categories['configure_value']);

    // get budget list
    $query_configuration_budget = 'SELECT * FROM dehari_configure WHERE configure_property = "budget";';

    // Perform Query
    $result_configuration_budget = mysql_query($query_configuration_budget, $db_dehari);
    $result_array_configuration_budget = mysql_fetch_assoc($result_configuration_budget);

    $budget_list = explode(",", $result_array_configuration_budget['configure_value']);

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

    <title>Dehari - Find</title>

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
            <div class="row" id="find_dehari_form">
                <div class="col-md-12">
                    
                    <div class="row" id="find_dehari_filters">
                        <div class="col-md-3">  
                            Title: <input id="find_dehari_title" type="text"/>
                        </div>
                        <div class="col-md-2">  
                            Category: <select id="find_dehari_category">
                              <option selected value=""></option>
                              <? foreach ($categories_list as $category_key => $category_name){?>
                              <option value="<?=$category_name?>"><?=$category_name?></option>
                              <?}?>
                            </select>
                        </div>
                        <div class="col-md-2">  
                            City: <select id="find_dehari_city">
                              <option selected value=""></option>
                              <? foreach ($cities_list as $city_key => $city_name){?>
                              <option value="<?=$city_name?>"><?=$city_name?></option>
                              <?}?>
                            </select>
                        </div>
                        <div class="col-md-2">  
                            Budget (PKR): <select id="find_dehari_budget">
                              <option selected value=""></option>
                              <option selected value=""></option>
                              <? foreach ($budget_list as $budget_key => $budget_range){?>
                              <option value="<?=$budget_range?>"><?=$budget_range?></option>
                              <?}?>
                            </select>
                        </div> 
                         <div class="col-md-3">  
                            <a href="" id="clear_search_button" title="clear filter">[clear]</a>
                            </br>
                            <input id="search_button" type="button" class="green_button" value="SEARCH">
                        </div>
                    </div>

                </br>


    <?
        $db_dehari = mysql_connect('localhost','bluecu6_rehan','.dehari.');
            mysql_select_db('bluecu6_dehari', $db_dehari);

            $query = 'SELECT * FROM dehari_list WHERE dehari_status = 0 AND dehari_user_id <> ' . $user_id;

            // Perform Query
            $result = mysql_query($query, $db_dehari);

            $dehari_list = array();
            while ($dehari_list_row = mysql_fetch_assoc($result))
                    $dehari_list[] = $dehari_list_row;

    ?>

                    <table class="table dehari_table display" >
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
                                    Posted
                                </th>
                                <th data-hide="phone">
                                    Budget
                                </th>
                                <th data-hide="phone,tablet">
                                    Date Posted
                                </th>
                            </tr>
                        </thead>
                        <tbody>
    <? foreach ($dehari_list as $dehari_row) { ?>
                            <tr>

                                <td><a href="dehari_details.php?dehari_id=<?=$dehari_row['dehari_id']?>" id="dehari_list_id_<?=$dehari_row['dehari_id']?>"><?=$dehari_row['dehari_title']?></a></td>
                                <td><?=$dehari_row['dehari_category']?></td>
                                <td><?=$dehari_row['dehari_city']?></td>
                                <td data-value="78025368997"><?=time_elapsed_string($dehari_row['dehari_date'])?></td>
                                <td data-value="1"><?=$dehari_row['dehari_budget']?></td>
                                <td data-value="78025368997"><?=(new DateTime($dehari_row['dehari_date']))->format('m/d/Y H:i:s')?></td>
                            </tr>
    <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>   
        </div> 
    </section>

    <div id="find_dehari_popup" title="Dehari" style="display:none">
        <div class="row" id="post_dehari_form">
            <div class="col-md-2">
                <div id="dehari_title_popup">
                Title
                </br>
                </div>
            </div>

            <div class="col-md-2">
                <div id="dehari_address_popup">
                Address
                </br>
                </div>
            </div>


            <div class="col-md-2">
                <div id="dehari_city_popup">
                City
                </br>
                </div>
            </div>


            <div class="col-md-2">
                <div id="dehari_category_popup">
                Category
                </br>
                </div>
            </div>

            <div class="col-md-2">
                <div id="dehari_phone_popup">
                Phone
                </br>
                </div>
            </div>

            <div class="col-md-2">
                <div id="dehari_budget_popup">
                Budget
                </br>
                </div>
            </div>


        </br>
            <div class="col-md-12">
                <div id="dehari_description_popup">
                Description
                </br>
                </div>
            </div>

        </br>
            <div class="col-md-2">
                <input type="number" min="1" id="dehari_bid_popup" placeholder="Enter Bid">
            </div>
            <div class="col-md-6">
                <input type="textarea" id="dehari_message_popup" value="" placeholder="Enter Message for Client">
            </div>
            <div class="col-md-2">
                 <input id="select_dehari_button" type="button" value="SEND">
            </div>
            <div class="col-md-2">
                 <input id="cancel_dehari_button" type="button" value="CANCEL">
            </div>

        </div>
    </div>


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
    

    <script src="js/find_dehari.js" type="text/javascript"></script>
    <script src="js/jquery.dataTables.min.js" type="text/javascript"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>