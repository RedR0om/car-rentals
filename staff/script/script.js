$( document ).ready(function() {	
	$('#example').DataTable({
		 "processing": true,
         "sAjaxSource":"data.php",
		 "pageLength": 10,
		 "dom": 'lBfrtip',
		 "buttons": [
            {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print'
                ]
            }
        ]
	});	
});