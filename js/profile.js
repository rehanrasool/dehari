$( document ).ready( function() {

  $("#profile_send_message_button").click(function() {
      var profile_user_id = $('#profile_send_message_user_id').val();
      
      var url = "new_message.php?recipient_id=" + profile_user_id;
      $(location).attr('href',url);
    });

});
