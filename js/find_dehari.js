
$(document).ready(function() {

	var dehariID;

    // DataTable
    var table = $('.dehari_table').DataTable({
        "lengthMenu": [[6, 25, 50, -1], [6, 25, 50, "All"]],
        "dom": '<"top"i>rt<"bottom"p><"clear">',
        "columnDefs": [
            {
                "targets": [ 5 ],
                "visible": false,
                "searchable": false
            }
        ],
        "order": [[ 5, "desc" ]]
    });
 

    $('#search_button').click(function() {

		table.columns().eq( 0 ).each( function ( colIdx ) {
	        var title_search_val = $('#find_dehari_title').val();
	        var category_search_val = $('#find_dehari_category').val();
	        var city_search_val = $('#find_dehari_city').val();
	        var budget_search_val = $('#find_dehari_budget').val();
			
			table.column( 0 ).search( title_search_val ).draw();
			table.column( 1 ).search( category_search_val ).draw();
			table.column( 2 ).search( city_search_val ).draw();
			table.column( 4 ).search( budget_search_val ).draw();
	    } );
	});

	$('#clear_search_button').click(function() {
			
		$('#find_dehari_title').val("");
	    $('#find_dehari_category').val("");
	    $('#find_dehari_city').val("");
	    $('#find_dehari_budget').val("");


	});

	$('#find_dehari_form').delegate('.dehari_details', 'click', function() {
	    dehari_popup(this);
	    return false;
	});

	function dehari_popup(row_clicked) {

		dehariID = $(row_clicked).attr("id");
		var dehariIDstart = "dehari_list_id_";
		dehariID = dehariID.substring(dehariIDstart.length,dehariID.length)
			
		$.ajax
	      ({
	          type: "POST",
	          //the url where you want to sent the userName and password to
	          url: "server/find_dehari.php",
	          //json object to sent to the authentication url
	          data : {
	          find_dehari: 1,
	          dehari_id : dehariID
	        } }).done(function(raw_data) {
	          var data = JSON.parse(raw_data);

	          $('#dehari_title_popup').html('Title</br>');
	          $('#dehari_address_popup').html('Address</br>');
	          $('#dehari_city_popup').html('City</br>');
	          $('#dehari_category_popup').html('Category</br>');
	          $('#dehari_phone_popup').html('Phone</br>');
	          $('#dehari_budget_popup').html('Budget</br>');
	          $('#dehari_description_popup').html('<hr>Description</br>');

	          $('#dehari_title_popup').append('<b>' + data['dehari_title'] + '</b>');
	          $('#dehari_address_popup').append('<b>' + data['dehari_address'] + '</b>');
	          $('#dehari_city_popup').append('<b>' + data['dehari_city'] + '</b>');
	          $('#dehari_category_popup').append('<b>' + data['dehari_category'] + '</b>');
	          $('#dehari_phone_popup').append('<b>' + data['dehari_phone'] + '</b>');
	          $('#dehari_budget_popup').append('<b>' + data['dehari_budget'] + '</b>');
	          $('#dehari_description_popup').append('<b>' + data['dehari_description'] + '</b> <br /> <hr>');

              $("#find_dehari_popup").dialog({
                  dialogClass: 'popup_style',
                  title: data['dehari_title'],
                  closeOnEscape: false,
                  width: '80%',
                  modal:true
                });

	      	});

	};


	$('#cancel_dehari_button').click(function() {
			
		$('#dehari_bid_popup').val('');
		$('#dehari_message_popup').val('');
		$('#select_dehari_button').val('SEND');
	    $('#cancel_dehari_button').val('CANCEL');
		$('#select_dehari_button').prop("disabled",false);
		$("#find_dehari_popup").dialog('close');


	});

	$('#select_dehari_button').click(function() {
		
		//var dehariID = $(row_clicked).attr("id");
		var bidValue = $('#dehari_bid_popup').val();
		var bidMessage = $('#dehari_message_popup').val();

		$.ajax
	      ({
	          type: "POST",
	          //the url where you want to sent the userName and password to
	          url: "server/find_dehari.php",
	          //json object to sent to the authentication url
	          data : {
	          select_dehari: 1,
	          dehari_id : parseInt(dehariID),
	          dehari_bid : parseInt(bidValue),
	          dehari_bid_message : bidMessage
	        } }).done(function(raw_data) {

	          if ($.trim(raw_data) === "success") {
	          	$('#select_dehari_button').val('SENT');
	          	$('#cancel_dehari_button').val('CLOSE');
				$('#select_dehari_button').prop("disabled",true);
	          } else {
	          	$('#select_dehari_button').val('TRY AGAIN');
	          }


	      	});


	});

/**
var find_dehari_table = $('.dehari_table').DataTable({
        "lengthMenu": [[7, 25, 50, -1], [7, 25, 50, "All"]]
    });

	$('#search_button').click(function() {

		var title_search_val = $('#find_dehari_title').val();
		
		find_dehari_table.column( 0 ).search( title_search_val ).draw();



	});

**/
});

