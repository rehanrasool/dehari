<?php
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
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dehari - Post</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:600,400' rel='stylesheet' type='text/css'>
    <link href="css/home_dehari.css" rel="stylesheet">

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
                        <a href="messages.php">messages</a>
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


    <!-- Main Profile Section -->
    <section id="post_dehari_section">
        <div class="container">

            <div class="row" id="post_dehari_form">
                <? if ($_GET['success']) {?>
                    <? if ($_GET['success']==1) {?>
                        <div class="success_area">Post Successfull! </div>
                    <? } else {?>
                        <div class="error_area">Post unsuccessfull! </div>
                    <?}?>
                <?}?>
                <form action="server/post_dehari.php" method="post">
                    <div class="col-md-6">
                        Title
                        <p><input type="text" name="dehari_title" value="" required></p>

                        Address 
                        <p><input type="text" name="dehari_address" value="" required></p>

                        City 
                        <select name="dehari_city" required>
                          <option disabled selected value=""> Select your City </option>
                          <? foreach ($cities_list as $city_key => $city_name){?>
                          <option value="<?=$city_name?>"><?=$city_name?></option>
                          <?}?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        Category 
                        <select name="dehari_category" required>
                          <option disabled selected value=""> Select Category </option>
                          <? foreach ($categories_list as $category_key => $category_name){?>
                          <option value="<?=$category_name?>"><?=$category_name?></option>
                          <?}?>
                        </select>

                        Phone 
                        <p><input type="text" name="dehari_phone" value="" required></p>

                        Budget (PKR)
                        <select name="dehari_budget" required>
                          <option disabled selected value=""> Select your Budget </option>
                          <? foreach ($budget_list as $budget_key => $budget_range){?>
                          <option value="<?=$budget_range?>"><?=$budget_range?></option>
                          <?}?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        Description 
                        <p><textarea name="dehari_description" rows="5" required></textarea></p>
                        <input type="hidden" name="postdata" value="1">
                    </div>

                    <div class="col-md-12">
                         <input class="red_button" type="submit" value="POST">
                    </div>
                </form>

            </div>

        </div>
        <!-- /.container -->
    </section>

    <div id="post_dehari_popup" title="Login Failed" style="display:none">
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
    <script src="js/jquery.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
    
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script src="js/post_dehari.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>