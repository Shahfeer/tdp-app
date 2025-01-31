<!-- Document type declaration -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<!-- Start of the document's head section -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>OBD CALL</title>

	<!-- Link to the CSS stylesheets -->
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">

	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    	<link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    	<link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet">
    	<link href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css" rel="stylesheet">

	<!-- Link to jQuery and DataTables JavaScript libraries -->
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

	<!-- Link to the application's JavaScript -->    
	<script src="{{ asset('js/app.js') }}" defer></script>

	<style>
		.text-center 
		{
			text-align: center;
		}
		.text_nowrap 
		{ 
			text-wrap: nowrap; 
		}
		.word_break 
		{ 
			text-wrap: nowrap !important; 
		}
		#loader 
		{
    			display: none;
    			position: fixed;
    			top: 0;
    			left: 0;
    			right: 0;
    			bottom: 0;
    			width: 100%;
    			background: rgba(0,0,0,0.60) url("https://yj360.in/obd_call/public/css/loader.gif") no-repeat center center;
    			z-index: 99999;
		}
		.dt-buttons .dt-button 
		{
    			background-color: #151228 !important;
    			color: #FFF !important;
    			border-radius: 50px !important;
    			box-shadow: 0px 3px rgb(109 110 129) !important;
		}
		div.dataTables_wrapper 
		{ 
			margin-bottom: 10px; 
		}
	</style>
</head>  <!-- End of the document's head section -->

<body class=" bg-red-200 min-h-screen font-base">
<div id="app">
    
    <div class="flex flex-col md:flex-row"> 

        @include('includes.sidebar')

        	<div class="w-full md:flex-1">

	    		<!-- Navigation bar -->
            		<nav class="hidden md:flex justify-between items-center bg-white p-4 shadow-md h-16">
                <div>

		@php
	    	$user = Auth::user(); // Get the currently logged-in user
		$userMasterId = Auth::user()->user_master_id;

    		// Check if the user and their associated credits exist
    		if ($user && $user->credits) 
		{
        		$available_credits = $user->credits->available_credits;
    		} 
		else 
		{
        		$available_credits = 0; // Set a default value if no credits are found
    		}
		@endphp

		    <div style="font-weight: bold; text-transform: uppercase; float: right;">
			@if ($userMasterId == 2)
			<span style="color: #FF0000;">Available Credits: {{ $available_credits }}</span>
			@endif
		    </div>
                    <input class="px-4 py-2 bg-gray-200 border border-gray-300 rounded focus:outline-none" type="text"
                           placeholder="Search.." style="display: none"/>
    </div>

    <div class="relative">

        <button style='background-color:#FFF'>
	    <!-- User profile icon and name -->
            <svg class="h-8 w-8 text-black-500" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="float: left;">
                <path stroke="none" d="M0 0h24v24H0z"/>
                <circle cx="12" cy="7" r="4" />
                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
            </svg> <div style="font-weight: bold; text-transform: uppercase; float: left;" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</div>

        </button>

	<button class="" style="background-color: #4b9ccd; border-radius: 50%; padding: 10px; color: #FFF; margin-left: 10px;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
		<!-- Logout button -->
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
	</button>

    </div>

    <!-- <div>
        <button class="mx-2 text-gray-700 focus:outline-none"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   <svg class="h-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         viewBox="0 0 24 24" stroke="currentColor">
                            <path
                               d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                   </svg>
        </button>
     </div> -->

  </nav>  <!-- End of navigation bar -->

    <main>
          <!-- Replace with your content -->
          <div class="px-8 py-6">
                    @yield('content')
          </div>
                <!-- /End replace -->
    </main>
  </div>
     <form id="logout-form" action="{{ route('logout-form') }}" method="POST" style="display: none;">
           @csrf
     </form>
 </div>
</div>


<script>

// JavaScript code for DataTable 'detail_data-table' initialization and customization
$(function () {

var detail_table= $('#detail_data-table').DataTable( {
        dom: 'lBfrtip',
        buttons: 
	[
		{
			"extend": 'excel',
			"text": 'EXCEL',
			"titleAttr": 'EXCEL',
			"action": newexportaction
		},
		{
			"extend": 'csv',
			"text": 'CSV',
			"titleAttr": 'CSV',
			"action": newexportaction
		},
		{
			/*"extend": 'pdfHtml5',
			"text": 'PDF',
			"titleAttr": 'PDF',
			"orientation": 'landscape',
                	"pageSize": 'A4',
			"action": newexportaction */

			extend: 'pdfHtml5',
            		orientation: 'landscape',
            		pageSize: 'TABLOID',
            		footer: true,
		}
        ], 
        processing: true,
        serverSide: true,

        initComplete: function () 
	{
            // Initially enable the date filter and buttons
            $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);
        },

        ajax: 
	{
            url: "{{ route('detailreport') }}",
            data: function (d) 
	    {
                d.detail_approved = $('#detail_approved').val(),
                d.detail_to_date=$('#detail_to_date').val(),
                d.detail_from_date=$('#detail_from_date').val(),
                d.detail_search=$('#detail_search').val()
            }
            
        },
        columns: 
	[
              	{data:'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center" },
	      	@if(Auth::user()->user_master_id === 1)
              	{ data: 'user_name', name: 'user_name', className: 'text-left', orderable: true, searchable: true },
    		@endif
              	{data:'campaign_name',name:'campaign_name', className: "text-left", orderable: true, searchable: true},
              	{data:'dst',name:'dst', className: "text-center",  orderable: true, searchable: true},
              	{data:'src',name:'src', className: "text-center", orderable: true, searchable: true},
              	{data:'disposition',name:'disposition', className: "text-left",  orderable: true, searchable: true,
		createdCell: function (cell, cellData, rowData, rowIndex, colIndex) 
		{
                	if (cellData === 'ANSWERED') 
			{
                    		$(cell).addClass('text-bold text-success');
                	} 
			else if (cellData === 'NO ANSWER' || cellData === 'BUSY' || cellData === 'FAILED') 
			{
                    		$(cell).addClass('text-bold text-danger');
                	}
            	}
	       	},
	      	{data:'retry_count',name:'retry_count', className: "text-center",  orderable: true, searchable: true},
	      	{data:'billsec',name:'billsec', className: "text-center", orderable: true, searchable: true},
	      	{data:'context',name:'context', className: "text-left", orderable: true, searchable: true},
	      	{data:'calldate',name:'calldate', className: "text-center", orderable: true, searchable: true, width: '200px'},
	      	{data:'last_call_time',name:'last_call_time', className: "text-center",  searchable: true, width: '200px'},
	      	{data:'hangupdate',name:'hangupdate', className: "text-center",  searchable: true, width: '200px'},
        ],
	language: 
	{
            "emptyTable": "No data available for this period"
        },

	drawCallback: function () 
	{
            // Re-enable the date filter inputs and buttons after data has been filtered
            $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);
        }

      	}); 

	$('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);

 	$('#detail_approved').change(function()
	{
		var status = $(this).val();

		var isAdmin = {{ Auth::user()->user_master_id === 1 ? 'true' : 'false' }};
        	var columnIndex = isAdmin ? 5 : 4; // Define the column index based on user role

		var statusMapping = 
		{
            		'answered': ['answered'],
            		'no answer': ['no answer', 'busy', 'failed'], // Include all failure statuses
        	};

		var statusesToFilter = statusMapping[status] || [];

        	// Use the DataTable API to search and filter the "Call Status" column
        	detail_table.column(columnIndex) // Adjust the column index based on your table structure
	     	.search(statusesToFilter.join('|'), true, false)
            	.draw();
        }); 
 
	$('#detail_get_filter').click(function()
	{
		$('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', true);
            
            	detail_table.draw();
        });

});


function newexportaction(e, dt, button, config) 
{
        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;
        dt.one('preXhr', function (e, s, data) 
	{
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) 
	     {
                // Call the original action function
                if (button[0].className.indexOf('buttons-copy') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-excel') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-csv') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-pdf') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-print') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                }
                dt.one('preXhr', function (e, s, data) 
		{
                     // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                     // Set the property to what it was before exporting.
                     settings._iDisplayStart = oldStart;
                     data.start = oldStart;
                });
                // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                setTimeout(dt.ajax.reload, 0);
                // Prevent rendering of the full data to the DOM
                return false;
             });
         });
         // Requery the server with the new one-time export settings
         dt.ajax.reload();
     }
</script>


<script>

// JavaScript code for DataTable 'summary_data-table' initialization and customization
$(function () {
var summary_table= $('#summary_data-table').DataTable( {
        dom: 'lBfrtip',
        buttons: 
	[
                {
                        "extend": 'excel',
                        "text": 'EXCEL',
                        "titleAttr": 'EXCEL',
                        "action": newexportaction
                },
                {
                        "extend": 'csv',
                        "text": 'CSV',
                        "titleAttr": 'CSV',
                        "action": newexportaction
                },
                {
                      /*  "extend": 'pdf',
                        "text": 'PDF',
                        "titleAttr": 'PDF',
                        "action": newexportaction */

			extend: 'pdfHtml5',
	            	orientation: 'landscape',
        	   	pageSize: 'TABLOID',
            		footer: true,
                }
        ],
	 
        processing: true,
        serverSide: true,
	initComplete: function () 
	{
            $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled', false);
        },
        ajax: 
	{
            url: "{{ route('summaryreport') }}",
            data: function (d) 
	    {
               	d.summary_approved = $('#summary_approved').val(),
                d.summary_to_date=$('#summary_to_date').val(),
                d.summary_from_date=$('#summary_from_date').val(),
               	d.summary_search=$('#summary_search').val()
            }
            
        },

        columns: 
	[
            	{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center"},  
	      	{data:'calldates',name:'calldates', className: "text-center text_nowrap", width: "150px"},
		
		@if(Auth::user()->user_master_id === 1)
        	{ data: 'user_name', name: 'user_name', className: 'text-left', orderable: true, searchable: true },
    		@endif

              	{data:'campaign_name',name:'campaign_name', className: "text-left"},
              	{data:'call_1_5',name:'call_1_5', className: "text-center text_nowrap"}, 
              	{data:'call_6_10',name:'call_6_10', className: "text-center text_nowrap"},
	      	{data:'call_11_15',name:'call_11_15', className: "text-center text_nowrap"},
	      	{data:'call_16_20',name:'call_16_20', className: "text-center text_nowrap"},
	      	{data:'call_21_25',name:'call_21_25', className: "text-center text_nowrap"},
	      	{data:'call_26_30',name:'call_26_30', className: "text-center text_nowrap"},
	      	{data:'call_31_45',name:'call_31_45', className: "text-center text_nowrap"},
	      	{data:'call_46_60',name:'call_46_60', className: "text-center text_nowrap"},
	      	{data:'call_answered',name:'call_answered', className: "text-center"},
	      	{data:'call_not_answered',name:'call_not_answered', className: "text-center"},
              	{data:'grand_total',name:'grand_total', className: "text-center"},
        ],
	language: 
	{
            "emptyTable": "No data available for this period"
        },

	drawCallback: function () 
	{
            // Re-enable the date filter inputs after data has been filtered
            $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled', false);
        }

      }); 

    // Initially enable the date filter inputs
    $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled', false);

    $('#detail_approved').change(function()
    {
     	 detail_table.draw();
    }); 
  
    $('#summary_get_filter').click(function()
    {
	
	 $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled', true);
            
            summary_table.draw();
    });

});

$('#summary_get_filter').addClass('bg-gray-700');


function newexportaction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) 
	     {
                 // Call the original action function
                if (button[0].className.indexOf('buttons-copy') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-excel') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-csv') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-pdf') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-print') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                }
                dt.one('preXhr', function (e, s, data) 
		{
                     // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                     // Set the property to what it was before exporting.
                     settings._iDisplayStart = oldStart;
                     data.start = oldStart;
                 });
                 // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                 setTimeout(dt.ajax.reload, 0);
                 // Prevent rendering of the full data to the DOM
                 return false;
             });
         });
         // Requery the server with the new one-time export settings
         dt.ajax.reload();
     }


    function toggleDropdown() 
    {
        var dropdownMenu = document.getElementById("dropdown-menu");
        dropdownMenu.classList.toggle("hidden");
    }

    // Close the dropdown menu if clicked outside
    window.addEventListener('click', function(event) 
    {
        var dropdownMenu = document.getElementById("dropdown-menu");
        var profileButton = document.querySelector('.text-gray-700');

/*        if (!profileButton.contains(event.target)) 
	{
            dropdownMenu.classList.add('hidden');
        } */
    });
</script>




<script>

// JavaScript code for DataTable 'summary_data-table' initialization and customization
$(function () {
var summary_report_table= $('#summary_report_data-table').DataTable( {
        dom: 'lBfrtip',
        buttons: 
	[
                {
                        "extend": 'excel',
                        "text": 'EXCEL',
                        "titleAttr": 'EXCEL',
                        "action": newexportaction
                },
                {
                        "extend": 'csv',
                        "text": 'CSV',
                        "titleAttr": 'CSV',
                        "action": newexportaction
                },
                {
                       /* "extend": 'pdf',
                        "text": 'PDF',
                        "titleAttr": 'PDF',
                        "action": newexportaction */

			extend: 'pdfHtml5',
            		orientation: 'landscape',
            		pageSize: 'TABLOID',
            		footer: true,
               }
        ],
	 
        processing: true,
        serverSide: true,

	initComplete: function () 
	{
            // Initially enable the date filter and buttons
            $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled', false);
        },

        ajax: 
	{
            url: "{{ route('summary_report') }}",
            data: function (d) 
	    {
               d.summary_approved = $('#summary_approved').val(),
                d.summary_to_date=$('#summary_to_date').val(),
                d.summary_from_date=$('#summary_from_date').val(),
               d.summary_search=$('#summary_search').val()
            }
            
        },
	columns: 
	[
          	  <!-- { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center" },  -->
              	{data:'calldates',name:'calldates', className: "text-center"},
                
		@if(Auth::user()->user_master_id === 1)
        	{ data: 'user_name', name: 'user_name', className: 'text-left', orderable: true, searchable: true },
    		@endif

                {data:'campaign_name',name:'campaign_name', className: "text-left"},
              	{data:'count_total',name:'count_total', className: "text-center"},
              	{data:'count_dialled_total',name:'count_dialled_total', className: "text-center"},
              	{data:'count_success',name:'count_success', className: "text-center"},
                {data:'count_failure',name:'count_failure', className: "text-center"},
                {data:'count_success_percentage',name:'count_success_percentage', className: "text-center"},
                {data:'average_aht',name:'average_aht', className: "text-center"},
        ],
	language: 
	{
            "emptyTable": "No data available for this period"
        },

	drawCallback: function () 
	{
            // Re-enable the date filter inputs and buttons after data has been filtered
            $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled', false);
        }

      }); 

	$('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled', false);

	$('#detail_approved').change(function()
	{
      		detail_table.draw();
 	}); 
  
 	$('#summary_get_filter').click(function()
	{

		$('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled', true);
	            
            	summary_report_table.draw();
       });
});

$('#summary_get_filter').addClass('bg-gray-700');


function newexportaction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) 
	 {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) 
	     {
                // Call the original action function
                if (button[0].className.indexOf('buttons-copy') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-excel') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-csv') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-pdf') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-print') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                }
                dt.one('preXhr', function (e, s, data) 
		{
                     // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                     // Set the property to what it was before exporting.
                     settings._iDisplayStart = oldStart;
                     data.start = oldStart;
                });
                // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                setTimeout(dt.ajax.reload, 0);
                // Prevent rendering of the full data to the DOM
                return false;
             });
         });
         // Requery the server with the new one-time export settings
         dt.ajax.reload();
     }


    function toggleDropdown() 
    {
        var dropdownMenu = document.getElementById("dropdown-menu");
        dropdownMenu.classList.toggle("hidden");
    }

    // Close the dropdown menu if clicked outside
    window.addEventListener('click', function(event) 
    {
        var dropdownMenu = document.getElementById("dropdown-menu");
        var profileButton = document.querySelector('.text-gray-700');

  //      if (!profileButton.contains(event.target)) {
//            dropdownMenu.classList.add('hidden');
    //    }
    });
</script>


<script>

// JavaScript code for DataTable 'campaign_list-table' initialization and customization
$(function () {

var userMasterId = <?php echo Auth::user()->user_master_id; ?>;
    var columnWidth = userMasterId === 1 ? "5%" : "20%";
	

var userMasterId1 = <?php echo Auth::user()->user_master_id; ?>;
    var columnWidth1 = userMasterId === 1 ? "20%" : "";

var userMasterId1 = <?php echo Auth::user()->user_master_id; ?>;
    var columnWidth2 = userMasterId === 1 ? "" : "10%";


var userMasterId1 = <?php echo Auth::user()->user_master_id; ?>;
    var columnWidth3 = userMasterId === 1 ? "" : "10%";


var userMasterId1 = <?php echo Auth::user()->user_master_id; ?>;
    var columnWidth4 = userMasterId === 1 ? "" : "10%";

var campaign_table = $('#campaign_list-table').DataTable( {
        dom: 'lBfrtip',
        buttons: 
	[
                {
                        "extend": 'excel',
                        "text": 'EXCEL',
                        "titleAttr": 'EXCEL',
                        "action": newexportaction
                },
                {
                        "extend": 'csv',
                        "text": 'CSV',
                        "titleAttr": 'CSV',
                        "action": newexportaction
                },
                {
			extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'TABLOID',
                        footer: true,

                    }
        ],
	
	columnDefs: 
	[
            { width: "2%", targets: 0 },
            { width: "10%", targets: 1 },
            { width: "10%", targets: 2 },
	    { width: "5%", targets: 3 },
	    { width: "5%", targets: 4 },
	    { width: "5%", targets: 5 },
	    { width: columnWidth, targets: 6 },
	    { width: columnWidth1, targets: 7 },
	    { width: columnWidth3, targets: 8 },
	    { width: columnWidth4, targets: 9 },
	    { width: columnWidth2, targets: 10 },
        ],
        	 
        processing: true,
        serverSide: true,

	initComplete: function () 
	{
            // Initially enable the date filter and buttons
            $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);
        },

        ajax: 
	{
            url: "{{ route('campaign_list') }}",
            data: function (d) 
	    {
               	d.detail_approved = $('#detail_approved').val(),
                d.detail_to_date=$('#detail_to_date').val(),
                d.detail_from_date=$('#detail_from_date').val(),
               	d.detail_search=$('#detail_search').val()
            }
            
        },
	columns: 
	[ 
		{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center" },
	  
		@if(Auth::user()->user_master_id === 1)
        	{ data: 'user_name', name: 'user_name', className: 'text-left', orderable: true, searchable: true },
    		@endif
          
		{ data: 'campaignId', name: 'campaignId', className: 'text-left' },
          	{ data: 'context', name: 'context', className: 'td_nowrap text-left' },
          	{ data: 'total_calls', name: 'total_calls', className: 'text-center'},
	        { data:'total_success',name:'total_success', className: "text-center"}, 
          	{data:'total_failure',name:'total_failure', className: "text-center"},
	        {data: 'status', name:'status', className: "text-center", render: function (data, type, full, meta) {
            	if (type === 'display') 
		{
			          return getStatusLabel(data);
            	}
            	return data; // For sorting and filtering
        	}},
		{data:'remarks',name:'remarks', className: "text-center word_break"},
          	{data:'calldates',name:'calldates', className: "text-center word_break"},
	  	{data:'startdates', name:'startdates', className: "text-center word_break"},
	  	{data:'completedates', name:'completedates', className: "text-center word_break"},
        ],
	language: 
	{
            "emptyTable": "No data available for this period"
        },

	drawCallback: function () 
	{
            // Re-enable the date filter inputs and buttons after data has been filtered
            $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);
        }

      }); 

	function getStatusLabel(status)
	{

        	switch (status)
		{
                	case 'O':
                        	return '<span class="text-success">Completed</span>';
                	case 'P':
                        	return '<span class="text-warning">Processing</span>';
                	case 'D':
                        	return '<span class="text-danger">Declined</span>';
                	case 'C':
                        	return '<span class="text-primary">Your campaign is under process, delivery will start shortly</span>';
                	default:
                        	return 'Unknown';
        	}
	}

        $('#campaign_list-table tbody').on('mouseenter', 'td', function ()
        {
                var colIdx = campaign_table.cell(this).index().column;
                $(campaign_table.cells().nodes()).removeClass('highlight');
                $(campaign_table.column(colIdx).nodes()).addClass('highlight');
        });

	$('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);

	$('#detail_approved').change(function()
	{
      		//detail_table.draw();
		var approvedValue = $('#detail_approved').val();
        	campaign_table.columns(8).search(approvedValue).draw();
 	}); 
  
 	$('#detail_get_filter').click(function()
	{

		$('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', true);
	            
            	campaign_table.draw();
       });
});

$('#detail_get_filter').addClass('bg-gray-700');


function newexportaction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) 
	 {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) 
	     {
                // Call the original action function
                if (button[0].className.indexOf('buttons-copy') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-excel') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-csv') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-pdf') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                } 
		else if (button[0].className.indexOf('buttons-print') >= 0) 
		{
                     $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                }
                dt.one('preXhr', function (e, s, data) 
		{
                     // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                     // Set the property to what it was before exporting.
                     settings._iDisplayStart = oldStart;
                     data.start = oldStart;
                });
                // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                setTimeout(dt.ajax.reload, 0);
                // Prevent rendering of the full data to the DOM
                return false;
             });
         });
         // Requery the server with the new one-time export settings
         dt.ajax.reload();
     }


    function toggleDropdown() 
    {
        var dropdownMenu = document.getElementById("dropdown-menu");
        dropdownMenu.classList.toggle("hidden");
    }

    // Close the dropdown menu if clicked outside
    window.addEventListener('click', function(event) 
    {
        var dropdownMenu = document.getElementById("dropdown-menu");
        var profileButton = document.querySelector('.text-gray-700');

    });
</script>


<script>

// JavaScript code for DataTable 'credit-management table' initialization and customization
$(function () {
      var credit_table = $('#credit_table').DataTable({
        dom: 'lBfrtip',
        fixedHeader: 
	{
          header: true,
          footer: true
        },
        scrollCollapse: true,
        scrollX: true,
        scrollY: 700,
        colReorder: true,
        buttons: 
	[
          {
            "extend": 'excel',
            "text": 'EXCEL',
            "titleAttr": 'EXCEL',
            "action": newexportaction,
            exportOptions: {
              columns: "thead th:not(.noExport)"
            }
          },
          {
            "extend": 'csv',
            "text": 'CSV',
            "titleAttr": 'CSV',
            "action": newexportaction,
            exportOptions: {
              columns: "thead th:not(.noExport)"
            }
          },
          {
            "extend": 'pdfHtml5',
            "text": 'PDF',
            "titleAttr": 'PDF',
            "orientation": 'landscape',
            "pageSize": 'sra3',
            "action": newexportaction,
            exportOptions: {
              columns: "thead th:not(.noExport)"
            }
          }, 'colvis'
        ],
        processing: true,
        serverSide: true,
        ajax: 
	{
          url: "{{ route('credit_management') }}",
          data: function (d) 
	  {
            	d.detail_approved = $('#detail_approved').val(),
              	d.detail_to_date = $('#detail_to_date').val(),
              	d.detail_from_date = $('#detail_from_date').val()
          }
        },
        columns: 
	[
          	{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center" },
          	{ data: 'user_name', name: 'user_name', orderable: false, searchable: false, className: "text-left" },
          	{ data: 'total_credits', name: 'total_credits', className: 'text-center' },
	  	{ data: 'used_credits', name: 'used_credits', className: 'text-center' },
         	{ data: 'available_credits', name: 'available_credits', className: 'td_nowrap text-center' },
	  	{data: 'action', name: 'action', orderable: false, searchable: false, exportOptions: { columns: ":visible" }},
        ],
        rowCallback: function (row, data) {

        }
      });

      	$('#credit_table tbody').on('mouseenter', 'td', function () 
	{
        	var colIdx = credit_table.cell(this).index().column;
        	$(credit_table.cells().nodes()).removeClass('highlight');
        	$(credit_table.column(colIdx).nodes()).addClass('highlight');
      	});

      	$('#detail_approved').change(function () 
	{
        	credit_table.draw();
      	});

      	$('#detail_get_filter').click(function () 
	{
        	credit_table.draw();
      	});

      	credit_table.on('draw', function () 
	{
        	var body = $(credit_table.table().body());
        	body.unhighlight();
        	body.highlight(credit_table.search());
      	});

    });

    function newexportaction(e, dt, button, config) {
      var self = this;
      var oldStart = dt.settings()[0]._iDisplayStart;
      dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) 
	{
          // Call the original action function
          if (button[0].className.indexOf('buttons-copy') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-excel') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
              $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
              $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-csv') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
              $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
              $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-pdf') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
              $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
              $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-print') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
          }
          dt.one('preXhr', function (e, s, data) 
	  {
            // DataTables thinks the first item displayed is index 0, but we're not drawing that.
            // Set the property to what it was before exporting.
            settings._iDisplayStart = oldStart;
            data.start = oldStart;
          });
          // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
          setTimeout(dt.ajax.reload, 0);
          // Prevent rendering of the full data to the DOM
          return false;
        });
      });
      // Requery the server with the new one-time export settings
      dt.ajax.reload();
    }
</script>



 <script>

// JavaScript code for DataTable 'approve_campaign_list-table' initialization and customization
$(function () {
      var approve_campaign_table = $('#approve_campaign_list-table').DataTable({

        dom: 'lBfrtip',
        fixedHeader: 
	{
          header: true,
          footer: true
        },
        scrollCollapse: true,
        scrollX: true,
        scrollY: 700,
        colReorder: true,
        order: [[2, "desc"]],
        buttons: 
	[
          {
            "extend": 'excel',
            "text": 'EXCEL',
            "titleAttr": 'EXCEL',
            "action": newexportaction,
            exportOptions: {
              columns: "thead th:not(.noExport)"
            }
          },
          {
            "extend": 'csv',
            "text": 'CSV',
            "titleAttr": 'CSV',
            "action": newexportaction,
            exportOptions: {
              columns: "thead th:not(.noExport)"
            }
          },
          {
            "extend": 'pdfHtml5',
            "text": 'PDF',
            "titleAttr": 'PDF',
            "orientation": 'landscape',
            "pageSize": 'sra3',
            "action": newexportaction,
            exportOptions: {
              columns: "thead th:not(.noExport)"
            }
          }, 'colvis'
        ],
        processing: true,
        serverSide: true,
        ajax: 
	{
          url: "{{ route('approve_campaign') }}",
          data: function (d) 
	  {
            	d.detail__approved = $('#detail_approved').val(),
              	d.detail_to_date = $('#detail_to_date').val(),
              	d.detail_from_date = $('#detail_from_date').val()
          }
        },
        columns: 
	[
          	{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center" },
          	{ data: 'user_name', name: 'user_name', className: 'text-left', orderable: true, searchable: true },
          	{ data: 'campaign_name', name: 'campaign_name', className: 'text-left' },
          	{ data: 'context', name: 'context', className: 'td_nowrap text-left' },
          	{ data: 'mobile_numbers', name: 'mobile_numbers', className: 'text-center'},
	  	{ data: 'call_entry_time', name: 'call_entry_time', className: 'text-center'},
          	{data: 'action', name: 'action', orderable: false, searchable: false,  exportOptions: { columns: ":visible" }},
        ],
        rowCallback: function (row, data) {

        }
      });

      	$('#approve_campaign_list-table tbody').on('mouseenter', 'td', function () 
	{
        	var colIdx = approve_campaign_table.cell(this).index().column;
        	$(approve_campaign_table.cells().nodes()).removeClass('highlight');
        	$(approve_campaign_table.column(colIdx).nodes()).addClass('highlight');
      	});

      	$('#detail_approved').change(function () 
	{
        	approve_campaign_table.draw();
      	});

      	$('#detail_get_filter').click(function () 
	{
        	approve_campaign_table.draw();
      	});

      	approve_campaign_table.on('draw', function () 
	{
        	var body = $(approve_campaign_table.table().body());
        	body.unhighlight();
        	body.highlight(approve_campaign_table.search());
      	});

    });

    function newexportaction(e, dt, button, config) {
      var self = this;
      var oldStart = dt.settings()[0]._iDisplayStart;
      dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) 
	{
          // Call the original action function
          if (button[0].className.indexOf('buttons-copy') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-excel') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
              $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
              $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-csv') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
              $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
              $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-pdf') >= 0)
	  {
            $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
              $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
              $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
         } 
	 else if (button[0].className.indexOf('buttons-print') >= 0) 
	 {
            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
         }
         dt.one('preXhr', function (e, s, data) 
	 {
            // DataTables thinks the first item displayed is index 0, but we're not drawing that.
            // Set the property to what it was before exporting.
            settings._iDisplayStart = oldStart;
            data.start = oldStart;
         });
         // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
         setTimeout(dt.ajax.reload, 0);
         // Prevent rendering of the full data to the DOM
         return false;
        });
      });
      // Requery the server with the new one-time export settings
      dt.ajax.reload();
    }
</script>  


<script>

// JavaScript code for DataTable 'context_list-table' initialization and customization
$(function () {
      var campaign_table = $('#context_list-table').DataTable({
        dom: 'lBfrtip',
        fixedHeader: 
	{
          header: true,
          footer: true
        },
        scrollCollapse: true,
        scrollX: true,
        scrollY: 700,
        colReorder: true,
        order: [[2, "desc"]],
        buttons: 
	[
          {
            "extend": 'excel',
            "text": 'EXCEL',
            "titleAttr": 'EXCEL',
            "action": newexportaction,
            exportOptions: {
              columns: "thead th:not(.noExport)"
            }
          },
          {
            "extend": 'csv',
            "text": 'CSV',
            "titleAttr": 'CSV',
            "action": newexportaction,
            exportOptions: {
              columns: "thead th:not(.noExport)"
            }
          },
          {
		extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'TABLOID',
                footer: true,
                exportOptions: {
               columns: "thead th:not(.noExport)"
            }
          }, 
        ],
        processing: true,
        serverSide: true,
        ajax: 
	{
          url: "{{ route('context_list') }}",
          data: function (d) 
	  {
            	d.detail_approved = $('#detail_approved').val(),
              	d.detail_to_date = $('#detail_to_date').val(),
              	d.detail_from_date = $('#detail_from_date').val()
          }
        },
        columns: 
	[
          	{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center" },
	  	
		@if(Auth::user()->user_master_id === 1)
        	{ data: 'user_name', name: 'user_name', className: 'text-left', orderable: true, searchable: true },
    		@endif
          	
		{ data: 'context', name: 'context', className: 'td_nowrap text-left' },
	  	{ data: 'remarks', name: 'remarks', className: 'td_nowrap text-left' },
	  	{data:'prompt',name:'prompt', className: "text-left"}, 
          	{data:'entry_time',name:'entry_time', className: "text-center"},
          	{data: 'action', name: 'action', orderable: false, searchable: false, exportOptions: { columns: ":visible" }},
        ],
        rowCallback: function (row, data) {

        }
      });

      	$('#context_list-table tbody').on('mouseenter', 'td', function () 
	{
        	var colIdx = campaign_table.cell(this).index().column;
       	 	$(campaign_table.cells().nodes()).removeClass('highlight');
        	$(campaign_table.column(colIdx).nodes()).addClass('highlight');
      	});

      	$('#detail_approved').change(function () 
	{
        	campaign_table.draw();
      	});

      	$('#detail_get_filter').click(function () 
	{
        	campaign_table.draw();
      	});

      	campaign_table.on('draw', function () 
	{
        	var body = $(campaign_table.table().body());
        	body.unhighlight();
        	body.highlight(campaign_table.search());
      	});

    });

    function newexportaction(e, dt, button, config) {
      var self = this;
      var oldStart = dt.settings()[0]._iDisplayStart;
      dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) 
	{
          // Call the original action function
          if (button[0].className.indexOf('buttons-copy') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-excel') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
              $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
              $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-csv') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
              $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
              $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-pdf') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
              $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
              $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-print') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
          }
          dt.one('preXhr', function (e, s, data) 
	  {
            // DataTables thinks the first item displayed is index 0, but we're not drawing that.
            // Set the property to what it was before exporting.
            settings._iDisplayStart = oldStart;
            data.start = oldStart;
          });
          // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
          setTimeout(dt.ajax.reload, 0);
          // Prevent rendering of the full data to the DOM
          return false;
        });
      });
      // Requery the server with the new one-time export settings
      dt.ajax.reload();
    }
</script>


<script>

// JavaScript code for DataTable 'gsm board table' initialization and customization
$(function () {
      var gsm_table = $('#gsm_table').DataTable({
        dom: 'lBfrtip',
        fixedHeader: 
	{
          header: true,
          footer: true
        },
        scrollCollapse: true,
        scrollX: true,
        scrollY: 700,
        colReorder: true,
        buttons: 
	[
          {
            "extend": 'excel',
            "text": 'EXCEL',
            "titleAttr": 'EXCEL',
            "action": newexportaction,
            exportOptions: {
              columns: "thead th:not(.noExport)"
            }
          },
          {
            "extend": 'csv',
            "text": 'CSV',
            "titleAttr": 'CSV',
            "action": newexportaction,
            exportOptions: {
              columns: "thead th:not(.noExport)"
            }
          },
          {
            "extend": 'pdfHtml5',
            "text": 'PDF',
            "titleAttr": 'PDF',
            "orientation": 'landscape',
            "pageSize": 'sra3',
            "action": newexportaction,
            exportOptions: {
              columns: "thead th:not(.noExport)"
            }
          }, 'colvis'
        ],
        processing: true,
        serverSide: true,
        ajax: 
	{
          url: "{{ route('gsm_board') }}",
          data: function (d) 
	  {
            	d.detail_approved = $('#detail_approved').val(),
              	d.detail_to_date = $('#detail_to_date').val(),
              	d.detail_from_date = $('#detail_from_date').val()
          }
        },
        columns: 
	[
          	{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center" },
          	{ data: 'user_name', name: 'user_name', orderable: false, searchable: false, className: "text-left" },
          	{ data: 'total_credits', name: 'total_credits', className: 'text-center' },
	  	{ data: 'used_credits', name: 'used_credits', className: 'text-center' },
         	{ data: 'available_credits', name: 'available_credits', className: 'td_nowrap text-center' },
	  	{data: 'action', name: 'action', orderable: false, searchable: false, exportOptions: { columns: ":visible" }},
        ],
        rowCallback: function (row, data) {

        }
      });

      	$('#gsm_table tbody').on('mouseenter', 'td', function () 
	{
        	var colIdx = gsm_table.cell(this).index().column;
        	$(gsm_table.cells().nodes()).removeClass('highlight');
        	$(gsm_table.column(colIdx).nodes()).addClass('highlight');
      	});

      	$('#detail_approved').change(function () 
	{
        	gsm_table.draw();
      	});

      	$('#detail_get_filter').click(function () 
	{
        	gsm_table.draw();
      	});

      	gsm_table.on('draw', function () 
	{
        	var body = $(gsm_table.table().body());
        	body.unhighlight();
        	body.highlight(gsm_table.search());
      	});

    });

    function newexportaction(e, dt, button, config) {
      var self = this;
      var oldStart = dt.settings()[0]._iDisplayStart;
      dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) 
	{
          // Call the original action function
          if (button[0].className.indexOf('buttons-copy') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-excel') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
              $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
              $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-csv') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
              $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
              $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-pdf') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
              $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
              $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
          } 
	  else if (button[0].className.indexOf('buttons-print') >= 0) 
	  {
            $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
          }
          dt.one('preXhr', function (e, s, data) 
	  {
            // DataTables thinks the first item displayed is index 0, but we're not drawing that.
            // Set the property to what it was before exporting.
            settings._iDisplayStart = oldStart;
            data.start = oldStart;
          });
          // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
          setTimeout(dt.ajax.reload, 0);
          // Prevent rendering of the full data to the DOM
          return false;
        });
      });
      // Requery the server with the new one-time export settings
      dt.ajax.reload();
    }
</script>



</body>
<!-- End of the HTML document -->
</html>
