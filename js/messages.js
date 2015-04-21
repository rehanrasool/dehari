
$(document).ready(function() {

	var dehariID;

    // DataTable
    var table = $('.message_table').DataTable({
        "lengthMenu": [[6, 25, 50, -1], [6, 25, 50, "All"]],
        "dom": '<"top"i>rt<"bottom"p><"clear">'
    });
 

    $('#message_search_button').click(function() {

		table.columns().eq( 0 ).each( function ( colIdx ) {
	        var title_search_val = $('#find_message_title').val();
	        var user_search_val = $('#find_message_user').val();
			
			table.column( 0 ).search( title_search_val ).draw();
			table.column( 1 ).search( user_search_val ).draw();
	    } );
	});

    $('#new_message_button').click(function() {
    	var url = "new_message.php";
		$(location).attr('href',url);
	});

});

