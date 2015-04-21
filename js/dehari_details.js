
$(document).ready(function() {

  $("#send_message_bid_button").click(function() {
      var profile_user_id = $('#send_message_bid_user_id').val();
      
      var url = "new_message.php?recipient_id=" + profile_user_id;
      $(location).attr('href',url);
    });


});

