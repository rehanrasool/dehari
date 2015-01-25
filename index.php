<? 

  $db_dehari = mysqli_connect("localhost", "bluecu6_rehan", ".dehari.", "bluecu6_dehari");

        /* check connection */
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $query_user = 'SELECT Count(*) FROM dehari_user';

        // Perform Query
        $result_user = mysqli_query($db_dehari, $query_user);
        $result_user_array = mysqli_fetch_assoc($result_user);
        $users_count = $result_user_array['Count(*)'];

        $query_deharis = 'SELECT Count(*) FROM dehari_list';

        // Perform Query
        $result_deharis = mysqli_query($db_dehari, $query_deharis);
        $result_deharis_array = mysqli_fetch_assoc($result_deharis);
        $deharis_count = $result_deharis_array['Count(*)'];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dehari</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:600,400' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
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
                <a class="navbar-brand page-scroll main_header_button" href="#"><img src="images/logo.png" alt="Dehari" height="90"> </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll login_form_button" href="#">LOGIN</a>
                    </li>
                    <li>
                        <a class="page-scroll signup_form_button" href="#">SIGNUP</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>


    <header>
        <div class="container">
            <div class="intro-text">
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <div class="intro-heading">Dehari</div>
                <br/>
                <div class="intro-lead-in">welcome to dehari</div>
                <br/>
                <br/>
                <!--<a href="#services" class="page-scroll btn btn-xl">Tell Me More</a>-->
            </div>
        </div>
    </header>

    <!-- Login Section -->
    <section id="login" class="gray_background" hidden>
        <div class="container">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                  <center>
                    <h2>
                      Login To Dehari.com
                    </h2>

                      <div id="login_error" class="error_area"></div>
                      <p><input type="text" id="dehari_user_name" value="" placeholder="Username"></p>
                      <p><input type="password" id="dehari_pass_word" value="" placeholder="Password"></p>
                      <input id="login_button" class="red_button" type="button" value="LOGIN">

                  
                      Don't have an account? <a class="signup_form_button" href="#">Sign up now!</a>
                  </center>
                </div>
                <div class="col-md-4"></div>
                
            </div>

        </div>
    </section>

    <!-- Signup Section -->
    <section id="signup" class="gray_background" hidden>
        <div class="container">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                  <center>
                    <h2>
                      Sign Up To Dehari.com
                    </h2>

                      <div id="signup_error" class="error_area"></div>
                      <p><input type="text" id="dehari_sign_user_email" value="" placeholder="Email"></p>
                      <p><input type="text" id="dehari_sign_user_name" value="" placeholder="Username"></p>
                      <p><input type="password" id="dehari_sign_pass_word" value="" placeholder="Password"></p>
                      <input id="signup_button" class="green_button" type="button" value="SIGNUP">

                      Already have an account? <a class="login_form_button" href="#">Sign in now!</a>
                  </center>
                </div>
                <div class="col-md-4"></div>
                
            </div>

        </div>
    </section>

    <!-- How Section -->
    <section id="how">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>
                      How Dehari.com Works
                    </h2>

                    <h4>
                      Post any service you need done and receive bids from talented workers within minutes. Compare their proposals and price, then select the best worker to complete your job. You can also be a worker at Dehari.com, free of charge!
                    </h4>
                </div>

                <div class="col-md-6">
                    <h3>
                      What are your benefits as a client?
                    </h3>

                    <h4>
                      <span class="glyphicon glyphicon-check dehari_green" aria-hidden="true"></span> Receive hundreds of proposals to compare within days <br />
                      <span class="glyphicon glyphicon-thumbs-up dehari_green" aria-hidden="true"></span> Easy communication with worker and tracking of tasks <br />
                      <span class="glyphicon glyphicon-credit-card dehari_green" aria-hidden="true"></span> Pay for work safely and securely!
                    </h4>
                </div>
                <div class="col-md-6">

                    <h3>
                      What are your benefits as a worker?
                    </h3>

                    <h4>
                      <span class="glyphicon glyphicon-plus dehari_red" aria-hidden="true"></span> Earn money by providing services to other people <br />
                      <span class="glyphicon glyphicon-star dehari_red" aria-hidden="true"></span> Improve your skillset and get better at doing things you love
                       <br />
                      <span class="glyphicon glyphicon-pencil dehari_red" aria-hidden="true"></span> Track your progress and earnings easily
                    </h4>
                    
                </div>
            </div>

        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="green_background">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                  <center>
                  <h2> Users: <?=$users_count?> </h2>
                  </center>
                </div>
                <div class="col-md-6">
                  <center>
                  <h2> Deharis Posted: <?=$deharis_count?> </h2>
                  </center>
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
<!--
        <div class="row">

            <div class="col-md-12 index_main_area">
              <div class="col-md-12">
                <center> 
                <h3>At dehari, you can:</h3>
                </center> 

                <div class="col-md-6">
                  
                </div>

                <div class="col-md-6">

                </div>

              </div>

              <div class="col-md-12">

                <div class="col-md-6">
                  <h3> Sign In </h3>
                      <p><input type="text" id="dehari_user_name" value="" placeholder="Username"></p>
                      <p><input type="password" id="dehari_pass_word" value="" placeholder="Password"></p>
                      <input id="login_button" type="button" value="LOGIN">
                </div>

                <div class="col-md-6">
                  <h3> Sign Up </h3>
                      <p><input type="text" id="dehari_sign_user_name" value="" placeholder="Username"></p>
                      <p><input type="password" id="dehari_sign_pass_word" value="" placeholder="Password"></p>
                      <input id="signup_button" type="button" value="SIGNUP">
                </div>
              </div>
            </div>


        </div>
    </div>

    <div id="dehari_index_popup" title="" style="display:none">
    </div>
-->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script src="js/index.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>