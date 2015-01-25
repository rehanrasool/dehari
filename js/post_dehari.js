
$('#post_button').click(function() {

	var dehariTitle = $('#dehari_title').val();
	var dehariAddress = $('#dehari_address').val();
	var dehariCity = $('#dehari_city').val();
	var dehariCategory = $('#dehari_category').val();
	var dehariPhone = $('#dehari_phone').val();
	var dehariBudget = $('#dehari_budget').val();
	var dehariDescription = $('#dehari_description').val();

	var error_found = false;

	$('#post_dehari_error').html("");

	if (dehariTitle == "" || dehariPhone == "" || dehariAddress == "" || !dehariCity || dehariCategory == "" || !dehariBudget || dehariDescription == "") {
		$('#post_dehari_error').append("The following fields are missing: ");
		error_found = true;	
	}
	if (dehariTitle == "") {
		$('#post_dehari_error').append("<b>Title </b>");	
	}
	if (dehariAddress == "") {
		$('#post_dehari_error').append("<b>Address </b>");	
	}
	if (!dehariCity) {
		$('#post_dehari_error').append("<b>City </b>");	
	}
	if (dehariCategory == " ") {
		$('#post_dehari_error').append("<b>Category </b>");	
	}
	if (dehariPhone == "") {
		$('#post_dehari_error').append("<b>Phone </b>");	
	}
	if (!dehariBudget) {
		$('#post_dehari_error').append("<b>Budget </b>");	
	}
	if (dehariDescription == "") {
		$('#post_dehari_error').append("<b>Description </b>");	
	}

	if (error_found) {
		return;
	}


	$.ajax
      ({
          type: "POST",
          //the url where you want to sent the userName and password to
          url: "server/post_dehari.php",
          //json object to sent to the authentication url
          data : {
          postdata: 1,
          dehari_title : dehariTitle,
          dehari_address: dehariAddress,
          dehari_city : dehariCity,
          dehari_category: dehariCategory,
          dehari_phone : dehariPhone,
          dehari_budget: dehariBudget,
          dehari_description: dehariDescription
        } }).done(function(raw_data) {
          console.log(raw_data);

          if ($.trim(raw_data) === "success") {
          	  $("#post_dehari_popup").html( "<p> Your dehari has been posted!</p>" );
              $("#post_dehari_popup").dialog({
                  dialogClass: 'popup_style',
                  title: 'Congratulations!',
                  modal:true
                });

              $('#post_button').prop("disabled",true);

          } else {
          	  $("#post_dehari_popup").html( "<p> Your dehari did not get posted. <br>Please try again later!</p>" );
              $("#post_dehari_popup").dialog({
                  dialogClass: 'popup_style',
                  title: 'Post Failed',
                  modal:true
                });
          }
      });

});