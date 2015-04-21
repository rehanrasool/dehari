
$(document).ready(function() {

	var dehariID;

    // DataTable
    var worker_table = $('.incoming_dehari_table').DataTable({
        "lengthMenu": [[7, 25, 50, -1], [7, 25, 50, "All"]],
        "dom": '<"top">rt<"bottom"ip><"clear">',
        "columnDefs": [
            {
                "targets": [ 5 ],
                "visible": false,
                "searchable": false
            }
        ],
        "order": [[ 5, "desc" ]]
    });

    var client_table = $('.outgoing_dehari_table').DataTable({
        "lengthMenu": [[7, 25, 50, -1], [7, 25, 50, "All"]],
        "dom": '<"top">rt<"bottom"ip><"clear">',
        "columnDefs": [
            {
                "targets": [ 5 ],
                "visible": false,
                "searchable": false
            }
        ],
        "order": [[ 5, "desc" ]]
    });


    $("#worker_mode_button").click(function() {
		$.ajax
        ({
          type: "POST",
          //the url where you want to sent the userName and password to
          url: "server/find_dehari.php",
          //json object to sent to the authentication url
          data : {
          select_user_mode: 1,
          mode : 'worker'
        } }).done(function(raw_data) {

          if ($.trim(raw_data) === "success") {
          	console.log('set');
          } else {
          	console.log('failed');
          }
      	});
        $("#client_table_section").hide("slow");
        $("#worker_table_section").show("slow");
        $('html, body').stop().animate({
            scrollTop: $('#worker_table_section').offset().top
        }, 1500, 'easeInOutExpo');
    });

    $("#client_mode_button").click(function() {
		$.ajax
        ({
          type: "POST",
          //the url where you want to sent the userName and password to
          url: "server/find_dehari.php",
          //json object to sent to the authentication url
          data : {
          select_user_mode: 1,
          mode : 'client'
        } }).done(function(raw_data) {

          if ($.trim(raw_data) === "success") {
          	console.log('set');
          } else {
          	console.log('failed');
          }
      	});
        $("#worker_table_section").hide("slow");
        $("#client_table_section").show("slow");
        $('html, body').stop().animate({
            scrollTop: $('#client_table_section').offset().top
        }, 1500, 'easeInOutExpo');
    });
 
	$('#client_dehari_search').on('input', function() {
		var title_search_val = $(this).val();
		client_table.columns().eq( 0 ).each( function ( colIdx ) {
	        
	        var category_search_val = $('#outgoing_dehari_status').val();
			
			client_table.column( 0 ).search( title_search_val ).draw();
			client_table.column( 4 ).search( category_search_val ).draw();
	    } );
	});

    $('#worker_dehari_search').on('input', function() {
    	var title_search_val = $(this).val();
		worker_table.columns().eq( 0 ).each( function ( colIdx ) {
	        
	        var category_search_val = $('#incoming_dehari_status').val();
			
			worker_table.column( 0 ).search( title_search_val ).draw();
			worker_table.column( 4 ).search( category_search_val ).draw();
	    } );
	});

	$('.client_dehari_filter_buttons').click( function() {
    	var title_search_val = $('#client_dehari_search').val();
		var category_search_val = ($(this).val() == 'ALL')? '': $(this).val();

    	$('.client_dehari_filter_buttons').each( function() {
    		if ($(this).hasClass("red_button")) {
    			$(this).removeClass("red_button").addClass("brown_button");
    		}
    	});

    	$(this).removeClass("brown_button").addClass("red_button");

		client_table.columns().eq( 0 ).each( function ( colIdx ) {
	        
			client_table.column( 0 ).search( title_search_val ).draw();
			client_table.column( 4 ).search( category_search_val ).draw();
	    } );
	});

	$('.worker_dehari_filter_buttons').click( function() {
    	var title_search_val = $('#worker_dehari_search').val();
		var category_search_val = ($(this).val() == 'ALL')? '': $(this).val();

    	$('.worker_dehari_filter_buttons').each( function() {
    		if ($(this).hasClass("red_button")) {
    			$(this).removeClass("red_button").addClass("brown_button");
    		}
    	});

    	$(this).removeClass("brown_button").addClass("red_button");

		worker_table.columns().eq( 0 ).each( function ( colIdx ) {
	        
			worker_table.column( 0 ).search( title_search_val ).draw();
			worker_table.column( 4 ).search( category_search_val ).draw();
	    } );
	});













	$('#incoming_bids_submitted_update_dehari_button').click(function() {
		
		//var dehariID = $(row_clicked).attr("id");
		var bidValue = $('#incoming_bids_submitted_dehari_bid_popup').val();
		var bidMessage = $('#incoming_bids_submitted_dehari_message_popup').val();

		$.ajax
	      ({
	          type: "POST",
	          //the url where you want to sent the userName and password to
	          url: "server/find_dehari.php",
	          //json object to sent to the authentication url
	          data : {
	          update_bid: 1,
	          dehari_id : parseInt(dehariID),
	          dehari_bid : parseInt(bidValue),
	          dehari_bid_message : bidMessage
	        } }).done(function(raw_data) {

	          if ($.trim(raw_data) === "success") {
	          	$('#incoming_bids_submitted_update_dehari_button').val('UPDATED');
				$('#incoming_bids_submitted_update_dehari_button').prop("disabled",true);
	          } else {
	          	$('#incoming_bids_submitted_update_dehari_button').val('TRY AGAIN');
	          }


	      	});


	});



	// Incoming - Bids Submitted
	$('#incoming_outgoing').delegate('.incoming_bids_submitted_details', 'click', function() {
	    incoming_bids_submitted_popup(this);
	    return false;
	});

	function incoming_bids_submitted_popup(row_clicked) {

		dehariID = $(row_clicked).attr("id");
		var dehariIDstart = "dehari_list_id_";
		dehariID = dehariID.substring(dehariIDstart.length,dehariID.length);
			
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

	          $('#incoming_bids_submitted_dehari_title_popup').html('Title</br>');
	          $('#incoming_bids_submitted_dehari_address_popup').html('Address</br>');
	          $('#incoming_bids_submitted_dehari_city_popup').html('City</br>');
	          $('#incoming_bids_submitted_dehari_category_popup').html('Category</br>');
	          $('#incoming_bids_submitted_dehari_phone_popup').html('Phone</br>');
	          $('#incoming_bids_submitted_dehari_budget_popup').html('Budget</br>');
	          $('#incoming_bids_submitted_dehari_description_popup').html('<hr>Description</br>');

	          $('#incoming_bids_submitted_dehari_title_popup').append('<b>' + data['dehari_title'] + '</b>');
	          $('#incoming_bids_submitted_dehari_address_popup').append('<b>' + data['dehari_address'] + '</b>');
	          $('#incoming_bids_submitted_dehari_city_popup').append('<b>' + data['dehari_city'] + '</b>');
	          $('#incoming_bids_submitted_dehari_category_popup').append('<b>' + data['dehari_category'] + '</b>');
	          $('#incoming_bids_submitted_dehari_phone_popup').append('<b>' + data['dehari_phone'] + '</b>');
	          $('#incoming_bids_submitted_dehari_budget_popup').append('<b>' + data['dehari_budget'] + '</b>');
	          $('#incoming_bids_submitted_dehari_description_popup').append('<b>' + data['dehari_description'] + '</b> <br /> <hr>');

	          var dehari_user_bid = data['dehari_bids_array'][data['user_id']];
	          $('#incoming_bids_submitted_dehari_bid_popup').val(dehari_user_bid);

              $("#incoming_bids_submitted_popup").dialog({
                  dialogClass: 'popup_style',
                  title: data['dehari_title'],
                  closeOnEscape: false,
                  width: '80%',
                  modal:true
                });

	      	});

	};


	$('#incoming_bids_submitted_close_dehari_button').click(function() {
			
		$('#incoming_bids_submitted_dehari_bid_popup').val('');
		$('#incoming_bids_submitted_dehari_message_popup').val('');
		$('#incoming_bids_submitted_update_dehari_button').val('UPDATE');
		$('#incoming_bids_submitted_update_dehari_button').prop("disabled",false);
		$("#incoming_bids_submitted_popup").dialog('close');


	});

	// Incoming - Work In Progress
	$('#incoming_outgoing').delegate('.incoming_in_progress_details', 'click', function() {
	    incoming_in_progress_popup(this);
	    return false;
	});

	function incoming_in_progress_popup(row_clicked) {

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

	          $('#incoming_in_progress_dehari_title_popup').html('Title</br>');
	          $('#incoming_in_progress_dehari_address_popup').html('Address</br>');
	          $('#incoming_in_progress_dehari_city_popup').html('City</br>');
	          $('#incoming_in_progress_dehari_category_popup').html('Category</br>');
	          $('#incoming_in_progress_dehari_phone_popup').html('Phone</br>');
	          $('#incoming_in_progress_dehari_budget_popup').html('Budget</br>');
	          $('#incoming_in_progress_dehari_description_popup').html('<hr>Description</br>');

	          $('#incoming_in_progress_dehari_title_popup').append('<b>' + data['dehari_title'] + '</b>');
	          $('#incoming_in_progress_dehari_address_popup').append('<b>' + data['dehari_address'] + '</b>');
	          $('#incoming_in_progress_dehari_city_popup').append('<b>' + data['dehari_city'] + '</b>');
	          $('#incoming_in_progress_dehari_category_popup').append('<b>' + data['dehari_category'] + '</b>');
	          $('#incoming_in_progress_dehari_phone_popup').append('<b>' + data['dehari_phone'] + '</b>');
	          $('#incoming_in_progress_dehari_budget_popup').append('<b>' + data['dehari_budget'] + '</b>');
	          $('#incoming_in_progress_dehari_description_popup').append('<b>' + data['dehari_description'] + '</b> <br /> <hr>');

	          var dehari_user_bid = data['dehari_bids_array'][data['user_id']];
	          $('#incoming_in_progress_dehari_bid_popup').val(dehari_user_bid);

              $("#incoming_in_progress_popup").dialog({
                  dialogClass: 'popup_style',
                  title: data['dehari_title'],
                  closeOnEscape: false,
                  width: '80%',
                  modal:true
                });

	      	});

	};


	$('#incoming_in_progress_close_dehari_button').click(function() {
			
		$('#incoming_in_progress_dehari_bid_popup').val('');
		$('#incoming_in_progress_dehari_message_popup').val('');
		$('#incoming_in_progress_rate_popup').val(0);
		//$('#incoming_bids_submitted_update_dehari_button').val('UPDATE');
		//$('#incoming_bids_submitted_update_dehari_button').prop("disabled",false);
		$("#incoming_in_progress_popup").dialog('close');

	});

	$('#incoming_in_progress_mark_complete_button').click(function() {
		
		//var dehariID = $(row_clicked).attr("id");
		var clientRating = $('#incoming_in_progress_rate_popup').val();

		$.ajax
	      ({
	          type: "POST",
	          //the url where you want to sent the userName and password to
	          url: "server/find_dehari.php",
	          //json object to sent to the authentication url
	          data : {
	          incoming_dehari_completed: 1,
	          dehari_id : parseInt(dehariID),
	          client_rating : parseInt(clientRating)
	        } }).done(function(raw_data) {

	          if ($.trim(raw_data) === "success") {
	          	$('#incoming_in_progress_mark_complete_button').val('COMPLETE');
				$('#incoming_in_progress_mark_complete_button').prop("disabled",true);
	          } else {
	          	$('#incoming_in_progress_mark_complete_button').val('TRY AGAIN');
	          }

	      	});
	});

	// Incoming - Work Completed
	$('#incoming_outgoing').delegate('.incoming_completed_details', 'click', function() {
	    incoming_completed_popup(this);
	    return false;
	});

	function incoming_completed_popup(row_clicked) {

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

	          $('#incoming_completed_dehari_title_popup').html('Title</br>');
	          $('#incoming_completed_dehari_address_popup').html('Address</br>');
	          $('#incoming_completed_dehari_city_popup').html('City</br>');
	          $('#incoming_completed_dehari_category_popup').html('Category</br>');
	          $('#incoming_completed_dehari_phone_popup').html('Phone</br>');
	          $('#incoming_completed_dehari_budget_popup').html('Budget</br>');
	          $('#incoming_completed_dehari_description_popup').html('<hr>Description</br>');

	          $('#incoming_completed_dehari_title_popup').append('<b>' + data['dehari_title'] + '</b>');
	          $('#incoming_completed_dehari_address_popup').append('<b>' + data['dehari_address'] + '</b>');
	          $('#incoming_completed_dehari_city_popup').append('<b>' + data['dehari_city'] + '</b>');
	          $('#incoming_completed_dehari_category_popup').append('<b>' + data['dehari_category'] + '</b>');
	          $('#incoming_completed_dehari_phone_popup').append('<b>' + data['dehari_phone'] + '</b>');
	          $('#incoming_completed_dehari_budget_popup').append('<b>' + data['dehari_budget'] + '</b>');
	          $('#incoming_completed_dehari_description_popup').append('<b>' + data['dehari_description'] + '</b> <br /> <hr>');

	          var dehari_user_bid = data['dehari_selected_bid'];
	          dehari_user_bid_split = dehari_user_bid.split(":");
	          dehari_bid_value = dehari_user_bid_split[1];
	          $('#incoming_completed_dehari_bid_popup').val(dehari_bid_value);
	          $('#incoming_completed_in_rate_popup').val(data['dehari_worker_rating']);
	          $('#incoming_completed_out_rate_popup').val(data['dehari_client_rating']);

              $("#incoming_completed_popup").dialog({
                  dialogClass: 'popup_style',
                  title: data['dehari_title'],
                  closeOnEscape: false,
                  width: '80%',
                  modal:true
                });

	      	});

	};


	$('#incoming_completed_close_dehari_button').click(function() {
			
		$('#incoming_completed_dehari_bid_popup').val('');
		$('#incoming_completed_rate_popup').val(0);
		//$('#incoming_bids_submitted_update_dehari_button').val('UPDATE');
		//$('#incoming_bids_submitted_update_dehari_button').prop("disabled",false);
		$("#incoming_completed_popup").dialog('close');

	});


	//Outgoing - Work Posted

	$('#incoming_outgoing').delegate('.outgoing_posted_details', 'click', function() {
	    outgoing_posted_popup(this);
	    return false;
	});

	function outgoing_posted_popup(row_clicked) {

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

	          $('#outgoing_posted_dehari_title_popup').html('Title</br>');
	          $('#outgoing_posted_dehari_address_popup').html('Address</br>');
	          $('#outgoing_posted_dehari_city_popup').html('City</br>');
	          $('#outgoing_posted_dehari_category_popup').html('Category</br>');
	          $('#outgoing_posted_dehari_phone_popup').html('Phone</br>');
	          $('#outgoing_posted_dehari_budget_popup').html('Budget</br>');
	          $('#outgoing_posted_dehari_description_popup').html('<hr>Description</br>');

	          $('#outgoing_posted_dehari_title_popup').append('<b>' + data['dehari_title'] + '</b>');
	          $('#outgoing_posted_dehari_address_popup').append('<b>' + data['dehari_address'] + '</b>');
	          $('#outgoing_posted_dehari_city_popup').append('<b>' + data['dehari_city'] + '</b>');
	          $('#outgoing_posted_dehari_category_popup').append('<b>' + data['dehari_category'] + '</b>');
	          $('#outgoing_posted_dehari_phone_popup').append('<b>' + data['dehari_phone'] + '</b>');
	          $('#outgoing_posted_dehari_budget_popup').append('<b>' + data['dehari_budget'] + '</b>');
	          $('#outgoing_posted_dehari_description_popup').append('<b>' + data['dehari_description'] + '</b> <br /> <hr>');

              $("#outgoing_posted_popup").dialog({
                  dialogClass: 'popup_style',
                  title: data['dehari_title'],
                  closeOnEscape: false,
                  width: '80%',
                  modal:true
                });

	      	});

	};

	$('#outgoing_posted_close_dehari_button').click(function() {
		$("#outgoing_posted_popup").dialog('close');
	});


	//Outgoing - Bids Received

	$('#incoming_outgoing').delegate('.outgoing_bids_received_details', 'click', function() {
	    outgoing_bids_received_popup(this);
	    return false;
	});

	function outgoing_bids_received_popup(row_clicked) {

		var outgoing_bids_received_table = $('.outgoing_bids_received_table').DataTable({
	        "lengthMenu": [[6, 25, 50, -1], [6, 25, 50, "All"]],
	        "dom": '<"top">rt<"bottom"ip><"clear">',
	        "order": [[ 2, "desc" ]],
	        "destroy": true,
			"columnDefs": [ {
			    "targets": 6,
			    "render": function ( data, type, full, meta ) {
			      return '<a href="#" class="outgoing_bids_received_select" id="outgoing_bids_received_select_'+full[0]+'_' +full[1]+'_' + full[2] + '">SELECT</a>';
			    	}
  				}, {
  				"targets": [0,1],
			    "visible": false
				} 
  			]
	    });

		dehariID = $(row_clicked).attr("id");
		var dehariIDstart = "dehari_list_id_";
		dehariID = dehariID.substring(dehariIDstart.length,dehariID.length);

		$.ajax
	      ({
	          type: "POST",
	          //the url where you want to sent the userName and password to
	          url: "server/find_dehari.php",
	          //json object to sent to the authentication url
	          data : {
	          get_bids: 1,
	          dehari_id : dehariID
	        } }).done(function(raw_data) {
	          var data = JSON.parse(raw_data);

	          for (var key in data) {
	          	outgoing_bids_received_table.row.add( [
	          		data[key]['dehari_id'],
	          		data[key]['bid_user_id'],
		            data[key]['bid_value'],
		            'MESSAGE GOES HERE',
		            data[key]['bid_user_name'],
		            data[key]['bid_user_rating'],
		            'SELECT'
		        ] ).draw();
	          }


              $("#outgoing_bids_received_popup").dialog({
                  dialogClass: 'popup_style',
                  title: data['dehari_title'],
                  closeOnEscape: false,
                  width: '80%',
                  modal:true
                });

	      	});

	};

	$('#outgoing_bids_received_close_dehari_button').click(function() {
		var outgoing_bids_received_table = $('.outgoing_bids_received_table').DataTable().destroy();
		$('.outgoing_bids_received_table tbody').html('');
		//outgoing_bids_received_table.destroy();
		$("#outgoing_bids_received_popup").dialog('close');
	});

	//Outgoing - Bids Received - Bid Selected
	$('.outgoing_bids_received_table').delegate('.outgoing_bids_received_select', 'click', function() {
	    outgoing_bids_received_select(this);
	    return false;
	});

	function outgoing_bids_received_select(row_clicked) {

		bidSelected = $(row_clicked).attr("id");
		var bidSelectedStart = "outgoing_bids_received_select_";
		bidSelected = bidSelected.substring(bidSelectedStart.length,bidSelected.length);
		bidSelectedSplit = bidSelected.split("_");
		dehariID = bidSelectedSplit[0];
		bidUserID = bidSelectedSplit[1];
		bidValue = bidSelectedSplit[2];

		$.ajax
	      ({
	          type: "POST",
	          //the url where you want to sent the userName and password to
	          url: "server/find_dehari.php",
	          //json object to sent to the authentication url
	          data : {
	          select_bid: 1,
	          dehari_id : dehariID,
	          bid_user: bidUserID,
	          bid_value: bidValue
	        } }).done(function(raw_data) {
	          //var data = JSON.parse(raw_data);


              $(row_clicked).html('SELECTED');

	      	});

	};


	// Outgoing - Work In Progress
	$('#incoming_outgoing').delegate('.outgoing_in_progress_details', 'click', function() {
	    outgoing_in_progress_popup(this);
	    return false;
	});

	function outgoing_in_progress_popup(row_clicked) {

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

	          $('#outgoing_in_progress_dehari_title_popup').html('Title</br>');
	          $('#outgoing_in_progress_dehari_address_popup').html('Address</br>');
	          $('#outgoing_in_progress_dehari_city_popup').html('City</br>');
	          $('#outgoing_in_progress_dehari_category_popup').html('Category</br>');
	          $('#outgoing_in_progress_dehari_phone_popup').html('Phone</br>');
	          $('#outgoing_in_progress_dehari_budget_popup').html('Budget</br>');
	          $('#outgoing_in_progress_dehari_description_popup').html('<hr>Description</br>');

	          $('#outgoing_in_progress_dehari_title_popup').append('<b>' + data['dehari_title'] + '</b>');
	          $('#outgoing_in_progress_dehari_address_popup').append('<b>' + data['dehari_address'] + '</b>');
	          $('#outgoing_in_progress_dehari_city_popup').append('<b>' + data['dehari_city'] + '</b>');
	          $('#outgoing_in_progress_dehari_category_popup').append('<b>' + data['dehari_category'] + '</b>');
	          $('#outgoing_in_progress_dehari_phone_popup').append('<b>' + data['dehari_phone'] + '</b>');
	          $('#outgoing_in_progress_dehari_budget_popup').append('<b>' + data['dehari_budget'] + '</b>');
	          $('#outgoing_in_progress_dehari_description_popup').append('<b>' + data['dehari_description'] + '</b> <br /> <hr>');

	          var dehari_user_bid = data['dehari_selected_bid'];
	          dehari_user_bid_split = dehari_user_bid.split(":");
	          dehari_bid_value = dehari_user_bid_split[1];
	          $('#outgoing_in_progress_dehari_bid_popup').val(dehari_bid_value);

              $("#outgoing_in_progress_popup").dialog({
                  dialogClass: 'popup_style',
                  title: data['dehari_title'],
                  closeOnEscape: false,
                  width: '80%',
                  modal:true
                });

	      	});

	};


	$('#outgoing_in_progress_close_dehari_button').click(function() {
			
		$('#outgoing_in_progress_dehari_bid_popup').val('');
		$('#outgoing_in_progress_dehari_message_popup').val('');
		$('#outgoing_in_progress_rate_popup').val(0);
		$("#outgoing_in_progress_popup").dialog('close');

	});

	$('#outgoing_in_progress_mark_complete_button').click(function() {
		
		//var dehariID = $(row_clicked).attr("id");
		var workerRating = $('#outgoing_in_progress_rate_popup').val();

		$.ajax
	      ({
	          type: "POST",
	          //the url where you want to sent the userName and password to
	          url: "server/find_dehari.php",
	          //json object to sent to the authentication url
	          data : {
	          outgoing_dehari_completed: 1,
	          dehari_id : parseInt(dehariID),
	          worker_rating : parseInt(workerRating)
	        } }).done(function(raw_data) {

	          if ($.trim(raw_data) === "success") {
	          	$('#outgoing_in_progress_mark_complete_button').val('COMPLETE');
				$('#outgoing_in_progress_mark_complete_button').prop("disabled",true);
	          } else {
	          	$('#outgoing_in_progress_mark_complete_button').val('TRY AGAIN');
	          }

	      	});
	});

	// Outgoing - Work Completed
	$('#incoming_outgoing').delegate('.outgoing_completed_details', 'click', function() {
	    outgoing_completed_popup(this);
	    return false;
	});

	function outgoing_completed_popup(row_clicked) {

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

	          $('#outgoing_completed_dehari_title_popup').html('Title</br>');
	          $('#outgoing_completed_dehari_address_popup').html('Address</br>');
	          $('#outgoing_completed_dehari_city_popup').html('City</br>');
	          $('#outgoing_completed_dehari_category_popup').html('Category</br>');
	          $('#outgoing_completed_dehari_phone_popup').html('Phone</br>');
	          $('#outgoing_completed_dehari_budget_popup').html('Budget</br>');
	          $('#outgoing_completed_dehari_description_popup').html('<hr>Description</br>');

	          $('#outgoing_completed_dehari_title_popup').append('<b>' + data['dehari_title'] + '</b>');
	          $('#outgoing_completed_dehari_address_popup').append('<b>' + data['dehari_address'] + '</b>');
	          $('#outgoing_completed_dehari_city_popup').append('<b>' + data['dehari_city'] + '</b>');
	          $('#outgoing_completed_dehari_category_popup').append('<b>' + data['dehari_category'] + '</b>');
	          $('#outgoing_completed_dehari_phone_popup').append('<b>' + data['dehari_phone'] + '</b>');
	          $('#outgoing_completed_dehari_budget_popup').append('<b>' + data['dehari_budget'] + '</b>');
	          $('#outgoing_completed_dehari_description_popup').append('<b>' + data['dehari_description'] + '</b> <br /> <hr>');

	          var dehari_user_bid = data['dehari_selected_bid'];
	          dehari_user_bid_split = dehari_user_bid.split(":");
	          dehari_bid_value = dehari_user_bid_split[1];
	          $('#outgoing_completed_dehari_bid_popup').val(dehari_bid_value);
	          $('#outgoing_completed_in_rate_popup').val(data['dehari_client_rating']);
	          $('#outgoing_completed_out_rate_popup').val(data['dehari_worker_rating']);

              $("#outgoing_completed_popup").dialog({
                  dialogClass: 'popup_style',
                  title: data['dehari_title'],
                  closeOnEscape: false,
                  width: '80%',
                  modal:true
                });

	      	});

	};

	$('#outgoing_completed_close_dehari_button').click(function() {
			
		$('#outgoing_completed_dehari_bid_popup').val('');
		$('#outgoing_completed_in_rate_popup').val(0);
		$('#outgoing_completed_out_rate_popup').val(0);
		//$('#outgoing_bids_submitted_update_dehari_button').val('UPDATE');
		//$('#outgoing_bids_submitted_update_dehari_button').prop("disabled",false);
		$("#outgoing_completed_popup").dialog('close');

	});

});

