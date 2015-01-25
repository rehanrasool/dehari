$( document ).ready( function() {


  // Closes the Responsive Menu on Menu Item Click
  $('.navbar-collapse ul li a').click(function() {
      $('.navbar-toggle:visible').click();
  });


  $(".login_form_button").click(function() {
        $("header").hide("slow");
        $("#signup").hide("slow");
        $("#login").show("slow");
    });

  $(".signup_form_button").click(function() {
        $("header").hide("slow");
        $("#login").hide("slow");
        $("#signup").show("slow");
    });

  $(".main_header_button").click(function() {
        $("#signup").hide("slow");
        $("#login").hide("slow");
        $("header").show("slow");
    });

  $("#signup_button").click(function () {
  var user_name = $('#dehari_sign_user_name').val();
  var pass_word = $('#dehari_sign_pass_word').val();

  $('#signup_error').html('');
  $('#signup_error').hide('fast');

  $.ajax
      ({
          type: "POST",
          //the url where you want to sent the userName and password to
          url: "server/authenticate.php",
          //json object to sent to the authentication url
          data : {
          signup: 1,
          username : user_name,
          password: pass_word
        } }).done(function(raw_data) {
          var data = JSON.parse(raw_data);
          
          if (data === "Username created successfully!") {
              window.location.href = "home_dehari.php";
          } else {
              $("#signup_error").html( "<p> " + data + "</p>" );
              $('#signup_error').show('fast');
          }

      });

  });

  $("#login_button").click(function () {
    var user_name = $('#dehari_user_name').val();
    var pass_word = $('#dehari_pass_word').val();

    $('#login_error').html('');
    $('#login_error').hide('fast');

    $.ajax
        ({
            type: "POST",
            //the url where you want to sent the userName and password to
            url: "server/authenticate.php",
            //json object to sent to the authentication url
            data : {
            login: 1,
            username : user_name,
            password: pass_word
          } }).done(function(raw_data) {
            if (raw_data === "-1") {
                $('#login_error').append("<p>Username or password is incorrect.</p>");
                $('#login_error').show('fast');
            } else {
                window.location.href = "home_dehari.php";
            }
        });

    });

    $("#dehari_index_popup").on('click', '#dehari_enter_info_button', function() {
      var userEmail = $('#dehari_enter_info_email').val();

      $.ajax
          ({
              type: "POST",
              //the url where you want to sent the userName and password to
              url: "server/authenticate.php",
              //json object to sent to the authentication url
              data : {
              enterinfo: 1,
              user_email : userEmail
            } }).done(function(raw_data) {
              
              if ($.trim(raw_data) === "success") {

                window.location.href = "home_dehari.php";
              } else {
                  $("#dehari_index_popup").append( "<p> Failed! </p>" );
              }

          });

      });






});
