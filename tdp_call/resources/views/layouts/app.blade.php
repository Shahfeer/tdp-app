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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"
        rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css" rel="stylesheet">

    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Popper.js -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


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

    <?php
    use Illuminate\Support\Facades\Auth;
    ?>



    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


    <!-- Modal HTML for datatable display data count more than 10000-->
    <div class="modal fade bs-example-modal-md" id="recordCountModal" data-backdrop="static" tabindex="-1"
        role="dialog" aria-labelledby="noChannelsModal" aria-hidden="true" data-backdrop="false"
        style=" position:fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
        <div class="modal-dialog">
            <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="modal-header" style="border-top: 4px inset red; text-align: center;">
                    <h5 class="modal-title" id="recordCountModalLabel">Record Count Alert</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Due to more records (more than 10K) click to download the report
                </div>
                <div class="modal-footer">
                    <button type="button" id="downloadButton"
                        class=" md:w-full bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full"
                        data-dismiss="modal">Download</button>
                    <button type="button"
                        class=" md:w-full bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full button0"
                        data-dismiss="modal" onclick = "window.location.reload();">Cancel</button>
                    <!-- Add a download button or link here to trigger the download -->
                </div>
            </div>
        </div>
    </div>

    <!-- <span class="tooltip-span" data-neron-id="123">Neron ID</span>

<span class="tooltip-campaign" data-campaign-id="123">Neron ID</span> -->


    <div id='loader' style="display: none;"></div>

    <div class="preloader-wrapper_1" style="display:none;">
        <div class="preloader_1">
        </div>
        <div class="text"
            style="color: white; background-color:#f27878; padding: 10px; margin-left:600px; margin-top:160px;">
            <b>Your file is downloading....<br /> Please wait.</b>
        </div>
    </div>

    <style>


    .custom-height {
        height: 5rem; /* or any other value like 16.4em or 16.4px */
    }


/* Tooltip styles */
.tooltip {
    position: relative;
    display: inline-block;
    /* Other styles as needed */
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: max-content;
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 5px;
    border-radius: 6px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}


        .preloader-wrapper_1 {
            display: flex;
            justify-content: center;
            width: 100%;
            height: 80%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 99999;
            align-items: center;
        }

        .preloader-wrapper_1>.preloader_1 {
            min-width: 128px;
            min-height: 128px;
            z-index: 99999;
            position: fixed;
        }

        .td_nowrap {
            white-space: nowrap !important;
        }

        .text-center {
            text-align: center;
        }

        .text_nowrap {
            text-wrap: nowrap;
        }

        .word_break {
            text-wrap: nowrap !important;
        }

        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.60) url("public/css/loader.gif") no-repeat center center;
            z-index: 99999;
        }

        .dt-buttons .dt-button {
            background-color: #00ee5a !important;
            color: black !important;
            border-radius: 50px !important;
            box-shadow: 0px 3px rgb(109 110 129) !important;
        }

        div.dataTables_wrapper {
            margin-bottom: 10px;
        }

        .tooltip-campaign.tooltip .tooltip-inner {
            max-width: 500px !important;
            /* Other styles */
        }

        .tooltip-inner {
            background-color: #E6E6FA;
            color: black;
            border: 1px solid black;
            max-width: 500px !important;

        }

        /* Changing the arrow color of the tooltip */
        .tooltip.bs-tooltip-auto[x-placement^="top"] .arrow::before {
            border-top-color: #F0FFF0;
        }


        .center-text {
            text-align: center;

        }
          a {
    color: #48ff00 !important;
    text-decoration: none !important;
    background-color: transparent !important;
}
.text-left{
    text-align: center !important;
}

    </style>
</head> <!-- End of the document's head section -->

<script>
    function toggleMenu() {
        // var sidebar = document.querySelector('.md\\:block');
        var sidebar = document.querySelector('.md\\:min-h-screen');
        console.log(sidebar);
        if (sidebar.classList.contains('menu-active')) {
            // If menu is currently active, hide it
            sidebar.classList.remove('menu-active');
            $('.menu_list').css({
                'display': '',
            });
        } else {
            // If menu is currently inactive, show it
            sidebar.classList.add('menu-active');
            $('.menu_list').css({
                'display': 'none',
            });
        }
    }
</script>

<body class=" bg-red-200 min-h-screen font-base">
    <div id="app">

        <div class="flex flex-col md:flex-row">

            <div class="w-full md:flex-1">


                <!-- Navigation bar -->
                <nav class="hidden md:flex justify-between items-center p-4 shadow-md custom-height" style="background-color: orange;">



			<!-- Left side with hamburger menu -->
			
			 <div style="background-color: lightgreen; min-height: 82px; width: 270px; position:absolute; left: 0;">
                                <button onclick="toggleMenu()" class="menu-trigger md:hidden" style="background-color: white; margin: 17px;">
                                <img src="https://img.icons8.com/?size=80&id=LPdxnDK2Fzn4&format=png" 
     alt="Menu" 
     class="w-6 h-6 text-black" 
     style="width: 48px; height: 48px; background-color:#00ee5a; color: white !important; padding: 5px;">

                                </button>
                                <h5 style="float: right;margin-top: 27px;margin-right: 70px; font-weight: bold">Select Menu</h5>
                        </div>
			<div>
				@php
                            		$user = Auth::user(); // Get the currently logged-in user
                            		$userMasterId = Auth::user()->user_master_id;

                            		// Check if the user and their associated credits exist
                            		if ($user && $user->credits) {
                                		$available_credits = $user->credits->available_credits;
                            		} else {
                                		$available_credits = 0; // Set a default value if no credits are found
                            		}
                        	@endphp

                        	<div style="font-weight: bold; text-transform: uppercase; margin-left:255px; text-align: right;"">
                            		@if ($userMasterId == 2)
                                    <span style="color: #000; background-color: #ffffff; border-radius: 5px; padding: 5px;">Available Credits: {{ $available_credits }}</span>

                            		@endif
                        	</div>
                        	<input class="px-4 py-2 bg-gray-200 border border-gray-300 rounded focus:outline-none"
                            		type="text" placeholder="Search.." style="display: none" />
                    	</div>

                    	<div class="relative">
        			<div style="display: block; margin-bottom: 20px;">
            				<img src="https://yourpostman.in/accounting_portal/public/css/celebmedia_logo.png" alt="logo" class="logo" style="width: 150px; height: 58px;">
				<div style="text-align: right;font-weight: bold;float: right;color: #000;font-size: 13px;font-family: "Century_Gothic", sans-serif !important;"
                                title="{{ Auth::user()->name }}">Logged in as {{ Auth::user()->name }}</div>
        		</div>

                </nav> <!-- End of navigation bar -->


	<div class="flex">
            <!-- Sidebar -->
            @include('includes.sidebar')

            <!-- Main content area -->
            <div class="w-full md:flex-1">
                <main id="content" class="px-8 py-6">
                    <!-- Your content -->
                    @yield('content')
                </main>
            </div>
        </div>


            <meta name="csrf-token" content="{{ csrf_token() }}">
            <form id="logout-form" action="{{ route('logout-form') }}" method="POST" style="display: none;">
                @csrf
            </form>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#downloadButton').on('click', function() {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                var detail_from_date = $('#detail_from_date').val();
                var detail_to_date = $('#detail_to_date').val();
                var detail_search = $('#detail_search').val();
                var detail_approved = $('#detail_approved').val();

                $("#loader").show();
                $(".preloader-wrapper").show();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                $.ajax({
                    method: 'POST',
                    url: "{{ route('exportasCSV') }}",
                    data: {
                        detail_from_date: detail_from_date,
                        detail_to_date: detail_to_date,
                        detail_search: detail_search,
                        detail_approved: detail_approved
                    },
                    success: function(response) {
                        downloadCSV(response);
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error('Error exporting data:', error);
                    }
                })

            });

            //Function to handle CSV download
            function downloadCSV(response) {

                //var files = JSON.parse(response).files;
                var files = response.files;
                console.log(files);
                if (files && files.length > 0) {
                    files.forEach(function(file) {
                        var fileName = file.filename;
                        console.log(fileName);
                        var csvData = file.csv;
                        console.log(csvData);

                        var blob = new Blob([csvData], {
                            type: 'text/csv'
                        });

                        // For IE browser, use msSaveOrOpenBlob method
                        if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                            window.navigator.msSaveOrOpenBlob(blob, fileName);
                        } else {
                            // For other browsers, create a download link
                            var downloadLink = document.createElement('a');
                            var url = window.URL.createObjectURL(blob);

                            downloadLink.href = url;
                            downloadLink.setAttribute('download', fileName);

                            document.body.appendChild(downloadLink);
                            downloadLink.click();

                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(downloadLink);

                            $("#loader").hide();
                            $(".preloader-wrapper").hide();

                            location.reload();
                        }
                    });
                } else {
                    console.log('No files to download');
                }

            }

        });
    </script>


    <script>
        // JavaScript code for DataTable 'detail_data-table' initialization and customization
        $(function() {



            var detail_table = $('#detail_data-table').DataTable({
                dom: 'lBfrtip',
                buttons: [{
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
                processing: true,
                serverSide: true,
                searching: true,

                initComplete: function() {
                    // Initially enable the date filter and buttons
                    $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);
                },

                ajax: {
                    url: "{{ route('detailreport') }}",
                    data: function(d) {
                        d.detail_approved = $('#detail_approved').val(),
                            d.detail_to_date = $('#detail_to_date').val(),
                            d.detail_from_date = $('#detail_from_date').val(),
                            d.detail_search = $('#detail_search').val()
                    }

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    },
                    @if (Auth::user()->user_master_id === 1)
                        {
                            data: 'name',
                            name: 'name',
                            className: 'text-left',
                            orderable: true,
                            searchable: true
                        },
                    @endif 
		    {
                     	data: 'campaign_date',
                        name: 'campaign_date',
                        className: 'td_nowrap text-left'
                    },

		    {
    data: 'campaign_name',
    name: 'campaign_name',
    className: "text-left",
    render: function(data, type, full, meta) {
        // Truncate the campaign name to first 10 characters
        var truncatedText = data.substring(0, 10) + (data.length > 10 ? '...' : '');
        // Full campaign name as tooltip
        var tooltipText = data;
        // Return truncated text with tooltip
        return '<span title="' + data + '">' + data + '</span>';
    }
},

                    {
                        data: 'context',
                        name: 'context',
                        className: 'td_nowrap text-left'
                    },
                    {
                        data: 'total_calls',
                        name: 'total_calls',
                        className: 'text-center'
                    },
                    {
                        data: 'total_success',
                        name: 'total_success',
                        className: "text-center"
                    },
                    {
                        data: 'total_failure',
                        name: 'total_failure',
                        className: "text-center"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: "text-center"
                    },
                ],
                language: {
                    "emptyTable": "No data available for this period"
                },

                drawCallback: function() {
                    // Re-enable the date filter inputs and buttons after data has been filtered
                    $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);
                }

            });

            $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);

            $('#detail_approved').change(function() {

            });

            $('#detail_get_filter').click(function() {
                $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', true);

                detail_table.draw();
            });

            //});

            function checkDataCountAndDisplayModal() {
                var totalRecordCount = detail_table.page.info().recordsTotal;

                if (totalRecordCount > 10000) {

                    $('#detail_data-table tbody').empty();
                    $('#detail_data-table tbody').html(
                        '<tr><td colspan="12" class="center-text">No data available</td></tr>');

                    // Display modal
                    $('#recordCountModal').modal('show');

                    var dataTable = $('#detail_data-table').DataTable();

                }
            }

            // Trigger modal check after table initialization and after every draw
            detail_table.on('init.dt draw.dt', function() {
                checkDataCountAndDisplayModal();
            });

        });

        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function(e, settings) {
                    // Call the original action function
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    dt.one('preXhr', function(e, s, data) {
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
        $(function() {
            var summary_table = $('#summary_data-table').DataTable({
                dom: 'lBfrtip',
                buttons: [{
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
                        "extend": 'pdfHtml5',
                        "orientation": 'landscape',
                        "pageSize": 'TABLOID',
                        "footer": true,
                        "action": newexportaction
                    }
                ],

                processing: true,
                serverSide: true,
                initComplete: function() {
                    $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled',
                        false);
                },
                ajax: {
                    url: "{{ route('summaryreport') }}",
                    data: function(d) {
                        d.summary_approved = $('#summary_approved').val(),
                            d.summary_to_date = $('#summary_to_date').val(),
                            d.summary_from_date = $('#summary_from_date').val(),
                            d.summary_search = $('#summary_search').val()
                    }

                },

		columns:
              [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center"},
                {data:'campaign_date',name:'campaign_date', className: "text-center text_nowrap", width: "100px"},

                @if(Auth::user()->user_master_id === 1)
                { data: 'name', name: 'name', className: 'text-left', orderable: true, searchable: true },
                @endif
                <!-- {data:'campaign_name',name:'campaign_name', className: "text-left"},-->
                {
    data: 'campaign_name',
    name: 'campaign_name',
    className: "text-left",
    render: function(data, type, full, meta) {
        // Truncate the campaign name to first 10 characters
        var truncatedText = data.substring(0, 10);
        // Full campaign name as tooltip
        var tooltipText = data;
        // Return truncated text with tooltip
        // return '<span title="' + tooltipText + '">' + truncatedText + '</span>';
        return '<span title="' + data + '">' + data + '</span>';
    }
}, 
{data:'particulars',name:'particulars', className: "text-center"},
                {data:'average_call_hold',name:'average_call_hold', className: "text-center"},
                {data:'total_calls',name:'total_calls', className: "text-center"},
                {data:'call_answered',name:'call_answered', className: "text-center"},
                {data:'success_percentage',name:'success_percentage', className: "text-center"},
                {data:'1_5_secs',name:'1_5_secs', className: "text-center text_nowrap"}, 
		{data:'6_10_secs',name:'6_10_secs', className: "text-center text_nowrap"}, 
		{data:'11_20_secs',name:'11_20_secs', className: "text-center text_nowrap"}, 
		{data:'21_30_secs',name:'21_30_secs', className: "text-center text_nowrap"}, 
		{data:'31_45_secs',name:'31_45_secs', className: "text-center text_nowrap"}, 
		{data:'46_60_secs',name:'46_60_secs', className: "text-center text_nowrap"}, 
        ],


                language: {
                    "emptyTable": "No data available for this period"
                },

                drawCallback: function() {
                    // Re-enable the date filter inputs after data has been filtered
                    $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled',
                        false);
                }

            });

            // Initially enable the date filter inputs
            $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled', false);

            $('#detail_approved').change(function() {
                detail_table.draw();
            });

            $('#summary_get_filter').click(function() {

                $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled', true);

                summary_table.draw();
            });

        });

        $('#summary_get_filter').addClass('bg-gray-700');


        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function(e, settings) {
                    // Call the original action function
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    dt.one('preXhr', function(e, s, data) {
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


        function toggleDropdown() {
            var dropdownMenu = document.getElementById("dropdown-menu");
            dropdownMenu.classList.toggle("hidden");
        }

        // Close the dropdown menu if clicked outside
        window.addEventListener('click', function(event) {
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
        $(function() {
            var summary_report_table = $('#summary_report_data-table').DataTable({
                dom: 'lBfrtip',
                buttons: [{
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
                        "extend": 'pdfHtml5',
                        "orientation": 'landscape',
                        "pageSize": 'TABLOID',
                        "footer": true,
                        "action": newexportaction
                    }
                ],

                processing: true,
                serverSide: true,

                initComplete: function() {
                    // Initially enable the date filter and buttons
                    $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled',
                        false);
                },

                ajax: {
                    url: "{{ route('summary_report') }}",
                    data: function(d) {
                        d.summary_approved = $('#summary_approved').val(),
                            d.summary_to_date = $('#summary_to_date').val(),
                            d.summary_from_date = $('#summary_from_date').val(),
                            d.summary_search = $('#summary_search').val()
                    }

                },
			columns:
        [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center" },
                {data:'campaign_date',name:'campaign_date', className: "text-center td_nowrap", width: '150px'},

                @if(Auth::user()->user_master_id === 1)
                { data: 'name', name: 'name', className: 'text-left', orderable: true, searchable: true },
                @endif

              <!--  {data:'campaign_name',name:'campaign_name', className: "text-left"}, -->

{
    data: 'campaign_name',
    name: 'campaign_name',
    className: "text-left",
    render: function(data, type, full, meta) {
        // Truncate the campaign name to first 10 characters
        var truncatedText = data.substring(0, 10);
        // Full campaign name as tooltip
        var tooltipText = data;
        // Return truncated text with tooltip
        // return '<span title="' + tooltipText + '">' + truncatedText + '</span>';
        return '<span title="' + data + '">' + data + '</span>';
    }
},

                {data:'particulars',name:'particulars', className: "text-left"},
                {data:'average_call_hold',name:'average_call_hold', className: "text-center"},
                {data:'total_dialled',name:'total_dialled', className: "text-center"},
                {data:'total_success',name:'total_success', className: "text-center"},
                {data:'success_percentage',name:'success_percentage', className: "text-center"},
                {data:'first_attempt',name:'first_attempt', className: "text-center"},
                {data:'retry_1',name:'retry_1', className: "text-center"},
                {data:'retry_2',name:'retry_1', className: "text-center"},
                {data:'total_failed',name:'total_failed', className: "text-center"},
        ],
                language: {
                    "emptyTable": "No data available for this period"
                },

                drawCallback: function() {
                    // Re-enable the date filter inputs and buttons after data has been filtered
                    $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled',
                        false);
                }

            });

            $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled', false);

            $('#detail_approved').change(function() {
                detail_table.draw();
            });

            $('#summary_get_filter').click(function() {

                $('#summary_from_date, #summary_to_date, #summary_get_filter').prop('disabled', true);

                summary_report_table.draw();
            });
        });

        $('#summary_get_filter').addClass('bg-gray-700');


        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function(e, settings) {
                    // Call the original action function
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    dt.one('preXhr', function(e, s, data) {
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


        function toggleDropdown() {
            var dropdownMenu = document.getElementById("dropdown-menu");
            dropdownMenu.classList.toggle("hidden");
        }

        // Close the dropdown menu if clicked outside
        window.addEventListener('click', function(event) {
            var dropdownMenu = document.getElementById("dropdown-menu");
            var profileButton = document.querySelector('.text-gray-700');

            //      if (!profileButton.contains(event.target)) {
            //            dropdownMenu.classList.add('hidden');
            //    }
        });
    </script>


    <script>
        // JavaScript code for DataTable 'campaign_list-table' initialization and customization
        $(function() {

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

            var campaign_table = $('#campaign_list-table').DataTable({
                dom: 'lBfrtip',
                buttons: [{
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
                        "extend": 'pdfHtml5',
                        "orientation": 'landscape',
                        "pageSize": 'TABLOID',
                        "footer": true,
                        "action": newexportaction
                    }
                ],

                columnDefs: [{
                        width: "2%",
                        targets: 0
                    },
                    {
                        width: "10%",
                        targets: 1
                    },
                    {
                        width: "10%",
                        targets: 2
                    },
                    {
                        width: "5%",
                        targets: 3
                    },
                    {
                        width: "5%",
                        targets: 4
                    },
                    {
                        width: "5%",
                        targets: 5
                    },
                    {
                        width: columnWidth,
                        targets: 6
                    },
                    {
                        width: columnWidth1,
                        targets: 7
                    },
                    {
                        width: columnWidth3,
                        targets: 8
                    },
                    {
                        width: columnWidth4,
                        targets: 9
                    },
                    {
                        width: columnWidth2,
                        targets: 10
                    },
                ],

                processing: true,
                serverSide: true,

                initComplete: function() {
                    // Initially enable the date filter and buttons
                    $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);
                },

                ajax: {
                    url: "{{ route('campaign_list') }}",
                    data: function(d) {
                        d.detail_approved = $('#detail_approved').val(),
                            d.detail_to_date = $('#detail_to_date').val(),
                            d.detail_from_date = $('#detail_from_date').val(),
                            d.detail_search = $('#detail_search').val()
                    }

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    },

                    @if (Auth::user()->user_master_id === 1)
                        {
                            data: 'name',
                            name: 'name',
                            className: 'text-left',
                            orderable: true,
                            searchable: true
                        },
                    @endif
		                {
    data: 'campaign_name',
    name: 'campaign_name',
    className: "text-left",
    render: function(data, type, full, meta) {
        // Truncate the campaign name to first 10 characters
        var truncatedText = data.substring(0, 10);
        // Full campaign name as tooltip
        var tooltipText = data;
        // Return truncated text with tooltip
        // return '<span title="' + tooltipText + '">' + truncatedText + '</span>';
        return '<span title="' + data + '">' + data + '</span>';
    }
},
                    {
                        data: 'context',
                        name: 'context',
                        className: 'td_nowrap text-left'
                    },
                    {
                        data: 'total_calls',
                        name: 'total_calls',
                        className: 'text-center'
                    },
                    {
                        data: 'total_success',
                        name: 'total_success',
                        className: "text-center"
                    },
                    {
                        data: 'total_failure',
                        name: 'total_failure',
                        className: "text-center"
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: "text-center",
                        render: function(data, type, full, meta) {
                            if (type === 'display') {
                                return getStatusLabel(data);
                            }
                            return data; // For sorting and filtering
                        }
                    },
                    @if (Auth::user()->user_master_id === 1)
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: "text-center word_break",
                            exportOptions: {
                                columns: ":visible"
                            }
                        }, {
                            data: 'neron_id',
                            name: 'neron_id',
                            orderable: false,
                            searchable: false,
                            className: "text-center word_break",
                            exportOptions: {
                                columns: ":visible"
                            },
                            "render": function(data, type, row, meta) {
                                if (type === 'display') {
                                    if (data !== null && data !== undefined && data !== '') {
                                        return '<span class="tooltip-span" data-neron-id="' + data +
                                            '">' + data + '</span>';
                                    } else {
                                        return '-'; // Display an empty string if neron_id is null, undefined, or empty
                                    }
                                }
                                return data;
                            },
                        },
                    @endif {
                        "data": 'remarks',
                        name: 'remarks',
                        className: "text-center word_break",
                        render: function(data, type, row) {
                            return (data !== null && data.trim() !== '') ? data :
                                '-'; // Display '-' for null or empty strings
                        }
                    },
                    {
                        data: 'calldates',
                        name: 'calldates',
                        className: "text-center word_break"
                    },
                    {
                        data: 'startdates',
                        name: 'startdates',
                        className: "text-center word_break",
                        render: function(data, type, row) {
                            return (data !== null && data.trim() !== '') ? data :
                                '-'; // Display '-' for null or empty strings
                        }
                    },
                    {
                        data: 'completedates',
                        name: 'completedates',
                        className: "text-center word_break",
                        render: function(data, type, row) {
                            return (data !== null && data.trim() !== '') ? data :
                                '-'; // Display '-' for null or empty strings
                        }
                    },
                ],
                language: {
                    "emptyTable": "No data available for this period"
                },

                drawCallback: function() {
                    // Re-enable the date filter inputs and buttons after data has been filtered
                    $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);
                }

            });


            // Function to handle tooltip display on hover
            $('body').tooltip({
                selector: '.tooltip-span',
                title: function() {
                    var neronId = $(this).data('neron-id');
                    //return fetchNeronDetails(neronId); // Return the details from the function
                    fetchNeronDetails(neronId, $(this));
                    return 'Loading...'; // Placeholder text while loading
                },
                html: true,
                trigger: 'hover'
            });

            // Function to fetch neron_details from your API
            function fetchNeronDetails(neronId, element) {
                // Make an AJAX call to your API to fetch neron_details based on neronId
                // Example:
                $.ajax({
                    url: '/neron_details',
                    method: 'GET',
                    data: {
                        neron_id: neronId
                    },
                    success: function(response) {
                        // Assuming the response contains the neron_details
                        var neronDetails = response.neron_details;

                        var detailsHTML = generateDetailsHTML(
                            neronDetails); // Generate HTML for details

                        element.attr('data-original-title', detailsHTML); // Set tooltip content
                        element.tooltip('show'); // Show tooltip
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        element.attr('data-original-title', 'Error fetching details');
                        element.tooltip('show');
                    }
                });
            }

            // Function to generate HTML for neron_details
            function generateDetailsHTML(neronDetails) {
                // Customize this based on how you want to display the details
                var html = '<div>';
                html += '<p><strong>Neron ID:</strong> ' + neronDetails.neron_id + '</p>';
                html += '<p><strong>Board Name:</strong> ' + neronDetails.board_name + '</p>';
                html += '<p><strong>Server ID:</strong> ' + neronDetails.server_id + '</p>';
                // Add more details as needed
                html += '</div>';
                return html;
            }

            $('.tooltip-span').tooltip({
                html: true
            });

            function getStatusLabel(status) {
                switch (status) {
    case 'O':
        return '<button class="btn btn-success" style="border-radius: 30px;" disabled>Completed</button>';
    case 'P':
        return '<button class="btn btn-warning" style="border-radius: 30px;" disabled>Processing</button>';
    case 'D':
        return '<button class="btn btn-danger" style="border-radius: 30px;" disabled>Declined</button>';
    case 'C':
        return '<button class="btn btn-primary" style="border-radius: 30px;" disabled>Under Process</button>';
    case 'S':
        return '<button class="btn btn-danger" style="border-radius: 30px;" disabled>Campaign Stopped</button>';
    default:
        return '<button class="btn btn-danger" style="border-radius: 30px;" disabled>Failed</button>';
}

            }

            $('#campaign_list-table tbody').on('mouseenter', 'td', function() {
                var colIdx = campaign_table.cell(this).index().column;
                $(campaign_table.cells().nodes()).removeClass('highlight');
                $(campaign_table.column(colIdx).nodes()).addClass('highlight');
            });

            $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);

            $('#detail_approved').change(function() {
                //detail_table.draw();
                var approvedValue = $('#detail_approved').val();
                campaign_table.columns(8).search(approvedValue).draw();
            });

            $('#detail_get_filter').click(function() {

                $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', true);

                campaign_table.draw();
            });
        });

        $('#detail_get_filter').addClass('bg-gray-700');


        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function(e, settings) {
                    // Call the original action function
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    dt.one('preXhr', function(e, s, data) {
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


        function toggleDropdown() {
            var dropdownMenu = document.getElementById("dropdown-menu");
            dropdownMenu.classList.toggle("hidden");
        }

        // Close the dropdown menu if clicked outside
        window.addEventListener('click', function(event) {
            var dropdownMenu = document.getElementById("dropdown-menu");
            var profileButton = document.querySelector('.text-gray-700');
        });
    </script>


    <script>
        // JavaScript code for DataTable 'credit-management table' initialization and customization
        $(function() {
            var credit_table = $('#credit_table').DataTable({
                dom: 'lBfrtip',

                scrollCollapse: true,
                scrollX: true,
                scrollY: 700,
                colReorder: true,
                buttons: [{
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
                ajax: {
                    url: "{{ route('credit_management') }}",
                    data: function(d) {
                        d.detail_approved = $('#detail_approved').val(),
                            d.detail_to_date = $('#detail_to_date').val(),
                            d.detail_from_date = $('#detail_from_date').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    },
                    {
                        data: 'user_name',
                        name: 'user_name',
                        orderable: false,
                        className: "text-left"
                    },
                    {
                        data: 'total_credits',
                        name: 'total_credits',
                        className: 'text-center'
                    },
                    {
                        data: 'used_credits',
                        name: 'used_credits',
                        className: 'text-center'
                    },
                    {
                        data: 'available_credits',
                        name: 'available_credits',
                        className: 'td_nowrap text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        exportOptions: {
                            columns: ":visible"
                        }
                    },
                ],
                rowCallback: function(row, data) {

                }
            });

            $('#credit_table tbody').on('mouseenter', 'td', function() {
                var colIdx = credit_table.cell(this).index().column;
                $(credit_table.cells().nodes()).removeClass('highlight');
                $(credit_table.column(colIdx).nodes()).addClass('highlight');
            });

            $('#detail_approved').change(function() {
                credit_table.draw();
            });

            $('#detail_get_filter').click(function() {
                credit_table.draw();
            });

            credit_table.on('draw', function() {
                var body = $(credit_table.table().body());
                body.unhighlight();
                body.highlight(credit_table.search());
            });

        });

        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function(e, settings) {
                    // Call the original action function
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    dt.one('preXhr', function(e, s, data) {
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
        $(function() {
            var approve_campaign_table = $('#approve_campaign_list-table').DataTable({

                dom: 'lBfrtip',

                scrollCollapse: true,
                scrollX: true,
                scrollY: 700,
                buttons: [{
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
                ajax: {
                    url: "{{ route('approve_campaign') }}",
                    data: function(d) {
                        d.detail__approved = $('#detail_approved').val(),
                            d.detail_to_date = $('#detail_to_date').val(),
                            d.detail_from_date = $('#detail_from_date').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    },
                    {
                        data: 'user_name',
                        name: 'user_name',
                        className: 'text-left',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'campaign_name',
                        name: 'campaign_name',
                        className: 'text-left'
                    },
                    {
                        data: 'context',
                        name: 'context',
                        className: 'td_nowrap text-left'
                    },
                    {
                        data: 'mobile_numbers',
                        name: 'mobile_numbers',
                        className: 'text-center'
                    },
                    {
                        data: 'call_entry_time',
                        name: 'call_entry_time',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        exportOptions: {
                            columns: ":visible"
                        }
                    },
                ],
                rowCallback: function(row, data) {

                }
            });

            $('#approve_campaign_list-table tbody').on('mouseenter', 'td', function() {
                var colIdx = approve_campaign_table.cell(this).index().column;
                $(approve_campaign_table.cells().nodes()).removeClass('highlight');
                $(approve_campaign_table.column(colIdx).nodes()).addClass('highlight');
            });

            $('#detail_approved').change(function() {
                approve_campaign_table.draw();
            });

            $('#detail_get_filter').click(function() {
                approve_campaign_table.draw();
            });

            approve_campaign_table.on('draw', function() {
                var body = $(approve_campaign_table.table().body());
                body.unhighlight();
                body.highlight(approve_campaign_table.search());
            });

        });

        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function(e, settings) {
                    // Call the original action function
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    dt.one('preXhr', function(e, s, data) {
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


  <!--  <script>
        // JavaScript code for DataTable 'context_list-table' initialization and customization
        $(function() {
            var campaign_table = $('#context_list-table').DataTable({
                dom: 'lBfrtip',
                fixedHeader: {
                    header: true,
                    footer: true
                },
                scrollCollapse: true,
                scrollX: true,
                scrollY: 700,
                colReorder: true,
                order: [
                    [6, "desc"]
                ],
                buttons: [{
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
                        "orientation": 'landscape',
                        "pageSize": 'TABLOID',
                        "footer": true,
                        "action": newexportaction,
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    },
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('context_list') }}",
                    data: function(d) {
                        d.detail_approved = $('#detail_approved').val(),
                            d.detail_to_date = $('#detail_to_date').val(),
                            d.detail_from_date = $('#detail_from_date').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    },

                    @if (Auth::user()->user_master_id === 1)
                        {
                            data: 'user_name',
                            name: 'user_name',
                            className: 'text-left',
                            orderable: true,
                            searchable: true
                        },
                    @endif {
                        data: 'campaign_type',
                        name: 'campaign_type',
                        className: 'td_nowrap text-left',
                        render: function(data, type, row) {
                            if (data === 'C') {
                                return '<span style="color: green;">Customised</span>';
                            } else if (data === 'N') {
                                return '<span style="color: blue;">Generic</span>';
                            }
                        }
                    },
                    {
                        data: 'context',
                        name: 'context',
                        className: 'td_nowrap text-left'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        className: 'td_nowrap text-left'
                    },
                    {
                        data: 'prompt',
                        name: 'prompt',
                        className: "text-left"
                    },
                    {
                        data: 'prompt_status',
                        name: 'prompt_status',
                        className: "text-center",
                        render: function(data, type, row) {
                            if (data === 'Y') {
                                return '<span style="color: green;">ACTIVE</span>';
                            } else if (data === 'N') {
                                return '<span style="color: blue;">NEW PROMPT</span>';
                            } else if (data === 'R') {
                                return '<span style="color: red;">REJECTED</span>';
                            } else {
                                return '<span style="color: black;">-</span>';
                            }
                        }
                    },
                    {
                        data: 'entry_time',
                        name: 'entry_time',
                        className: "text-center"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        exportOptions: {
                            columns: ":visible"
                        }
                    },
                ],
                rowCallback: function(row, data) {

                }
            });

            $('#context_list-table tbody').on('mouseenter', 'td', function() {
                var colIdx = campaign_table.cell(this).index().column;
                $(campaign_table.cells().nodes()).removeClass('highlight');
                $(campaign_table.column(colIdx).nodes()).addClass('highlight');
            });

            $('#detail_approved').change(function() {
                campaign_table.draw();
            });

            $('#detail_get_filter').click(function() {
                campaign_table.draw();
            });

            campaign_table.on('draw', function() {
                var body = $(campaign_table.table().body());
                body.unhighlight();
                body.highlight(campaign_table.search());
            });
        });

        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function(e, settings) {
                    // Call the original action function
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    dt.one('preXhr', function(e, s, data) {
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
    </script> -->


    <script>
        // JavaScript code for DataTable 'context_list-table' initialization and customization
        $(function() {
            var detail_table = $('#context_list-table').DataTable({
                dom: 'lBfrtip',
                buttons: [{
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
                processing: true,
                serverSide: true,
                searching: true,

                initComplete: function() {
                    // Initially enable the date filter and buttons
                    $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);
                },

                ajax: {
                    url: "{{ route('context_list') }}",
                    data: function(d) {
                        d.detail_approved = $('#detail_approved').val(),
                            d.detail_to_date = $('#detail_to_date').val(),
                            d.detail_from_date = $('#detail_from_date').val(),
                            d.detail_search = $('#detail_search').val()
                    }

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    },
                    @if (Auth::user()->user_master_id === 1)
                        {
                            data: 'user_name',
                            name: 'user_name',
                            className: 'text-left',
                            orderable: true,
                            searchable: true
                        },
                    @endif {
                        data: 'campaign_type',
                        name: 'campaign_type',
                        className: 'td_nowrap text-left',
                        render: function(data, type, row) {
                            if (data === 'C') {
                                return '<span style="color: green;">Customised</span>';
                            } else if (data === 'N') {
                                return '<span style="color: blue;">Generic</span>';
                            }
                        }
                    },
                    {
                        data: 'context',
                        name: 'context',
                        className: 'td_nowrap text-left'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        className: 'td_nowrap text-left'
                    },
                    {
                        data: 'prompt',
                        name: 'prompt',
                        className: "text-left"
                    },
                    {
                        data: 'prompt_status',
                        name: 'prompt_status',
                        className: "text-center",
                        render: function(data, type, row) {
    if (data === 'Y') {
        return '<button class="btn btn-success" style="border-radius:30px; cursor:none;">ACTIVE</button>';
    } else if (data === 'N') {
        return '<button class="btn btn-info" style="border-radius:30px; cursor:none;">NEW PROMPT</button>';
    } else if (data === 'R') {
        return '<button class="btn btn-danger" style="border-radius:30px; cursor:none;">REJECTED</button>';
    } else {
        return '<button class="btn btn-secondary" style="border-radius:30px; cursor:none;">UNKNOWN</button>';
    }
}

                    },
                    {
                        data: 'entry_time',
                        name: 'entry_time',
                        className: "text-center"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: "text-center"
                    },
                ],
                language: {
                    "emptyTable": "No data available for this period"
                },

                drawCallback: function() {
                    // Re-enable the date filter inputs and buttons after data has been filtered
                    $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);
                }
            });

            $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', false);

            $('#detail_approved').change(function() {

            });

            $('#detail_get_filter').click(function() {
                $('#detail_from_date, #detail_to_date, #detail_get_filter').prop('disabled', true);
                detail_table.draw();
            });

            function checkDataCountAndDisplayModal() {
                var totalRecordCount = detail_table.page.info().recordsTotal;

                if (totalRecordCount > 10000) {

                    $('#detail_data-table tbody').empty();
                    $('#detail_data-table tbody').html(
                        '<tr><td colspan="12" class="center-text">No data available</td></tr>');

                    // Display modal
                    $('#recordCountModal').modal('show');

                    var dataTable = $('#detail_data-table').DataTable();

                }
            }

            // Trigger modal check after table initialization and after every draw
            detail_table.on('init.dt draw.dt', function() {
                checkDataCountAndDisplayModal();
            });
        });

        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function(e, settings) {
                    // Call the original action function
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    dt.one('preXhr', function(e, s, data) {
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
        $(function() {
            var gsm_table = $('#gsm_table').DataTable({
                dom: 'lBfrtip',
                fixedHeader: {
                    header: true,
                    footer: true
                },
                scrollCollapse: true,
                scrollX: true,
                scrollY: 700,
                colReorder: true,
                buttons: [{
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
                ajax: {
                    url: "{{ route('gsm_board') }}",
                    data: function(d) {
                        d.detail_approved = $('#detail_approved').val(),
                            d.detail_to_date = $('#detail_to_date').val(),
                            d.detail_from_date = $('#detail_from_date').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    },
                    {
                        data: 'board_name',
                        name: 'board_name',
                        orderable: false,
                        searchable: false,
                        className: "text-left"
                    },
                    {
                        data: 'server_id',
                        name: 'server_id',
                        orderable: false,
                        searchable: false,
                        className: "text-left",
                        render: function(data, type, row) {
                            return '<a href="#" class="server-details" data-server-id="' + row
                                .server_id + '">' + data + '</a>';
                        }
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address',
                        className: 'text-center'
                    },
                    {
                        data: 'neron_status',
                        name: 'neron_status',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (data === 'Y') {
                                return '<span style="color: green;">Active</span>';
                            } else if (data === 'N') {
                                return '<span style="color: red;">Inactive</span>';
                            } else {
                                return data; // If neither 'Y' nor 'N', display the value as is
                            }
                        }
                    },
                    {
                        data: 'running_status',
                        name: 'running_status',
                        className: 'td_nowrap text-center',
                        "render": function(data, type, row, meta) {
                            if (type === 'display') {
                                if (data !== null && data !== undefined && data !== '') {
                                    //console.log(data);
                                    return '<span class="tooltip-campaign" data-campaign-id="' +
                                        data + '">' + data + '</span>';
                                } else {
                                    return ''; // Display an empty string if neron_id is null, undefined, or empty
                                }
                            }
                            return data;
                        }
                    },
                    {
                        data: 'neron_con_time',
                        name: 'neron_con_time',
                        className: 'td_nowrap text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        exportOptions: {
                            columns: ":visible"
                        }
                    },
                ],
                rowCallback: function(row, data) {

                }
            });

            // Tooltip initialization
            $(document).on('mouseenter', '.tooltip-campaign', function() {
                var campaignId = $(this).data('campaign-id');
                var runningStatus = $(this).data(
                    'campaign-id'); // Assuming running status is available as data attribute

                if (runningStatus === 'Idle') {
                    return; // Don't display tooltip if running status is 'Idle'
                }

                var element = $(this);

                $.ajax({
                    url: 'campaign_details', // URL to fetch campaign details
                    method: 'GET',
                    data: {
                        campaign_id: campaignId
                    },
                    success: function(response) {
                        var campaignDetails = response.campaign_details;
                        var detailsHTML = generateCampaignHTML(campaignDetails);
                        element.attr('data-original-title', detailsHTML);

                        // Show Bootstrap tooltip
                        element.tooltip({
                            title: detailsHTML,
                            html: true,
                            trigger: 'hover'
                        }).tooltip('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        element.attr('data-original-title', 'Something went wrong');

                        // Show Bootstrap tooltip with error message
                        element.tooltip({
                            title: 'Something went wrong',
                            trigger: 'hover'
                        }).tooltip('show');
                    }
                });
            });

            // Function to generate HTML for campaign details
            function generateCampaignHTML(campaignDetails) {
                var html = '<div>';
                // Customize HTML structure based on campaign details received
                html += '<p><strong>Campaign ID:</strong> ' + campaignDetails.campaign_id + '</p>';
                html += '<p><strong>User Name:</strong> ' + campaignDetails.user_name + '</p>';
                html += '<p><strong>Campaign Name:</strong> ' + campaignDetails.campaign_name + '</p>';
                html += '<p><strong>Context:</strong> ' + campaignDetails.context + '</p>';
                html += '<p><strong>Total Calls:</strong> ' + campaignDetails.no_of_mobile_numbers + '</p>';
                html += '<p><strong>Campaign Created Date:</strong> ' + campaignDetails.call_entry_time + '</p>';
                // Check if campaign start time is null or undefined
                if (campaignDetails.call_start_time === null || campaignDetails.call_start_time === undefined) {
                    html += '<p><strong>Campaign Start Time:</strong> </p>'; // Display empty if null or undefined
                } else {
                    html += '<p><strong>Campaign Start Time:</strong> ' + campaignDetails.call_start_time + '</p>';
                } // Add more details as needed
                html += '</div>';
                return html;
            }

            $('.tooltip-campaign').tooltip({
                html: true
            });


            // Handle click event on the 'server_id' link
            $('#gsm_table tbody').on('click', 'a.server-details', function(e) {
                e.preventDefault();

                var serverId = $(this).data('server-id');

                // Make an AJAX call to fetch channel status details based on the serverId
                // Replace this with your own AJAX call to fetch channel status details
                $.ajax({
                    url: 'channel_status',
                    method: 'GET',
                    data: {
                        serverId: serverId
                    },
                    success: function(response) {
                        // Update modal content with channel status details
                        var channelStatusData = response
                            .channel_status; // Assuming 'channel_status' is an array

                        var modalContent = '<table class="table">';
                        modalContent +=
                            '<thead><tr><th>Channel</th><th>SIM Number</th><th>Status</th></tr></thead>';
                        modalContent += '<tbody>';

                        // Loop through the channel status data and construct rows
                        for (var i = 0; i < channelStatusData.length; i++) {
                            modalContent += '<tr>';
                            modalContent += '<td>' + channelStatusData[i].channel + '</td>';
                            modalContent += '<td>' + channelStatusData[i].sim_number + '</td>';

                            // Check if status is 'Idle' for conditional styling
                            if (channelStatusData[i].status.toLowerCase() === 'idle') {
                                modalContent += '<td style="color: green;">' +
                                    channelStatusData[i].status + '</td>';
                            } else {
                                modalContent += '<td style="color: red;">' + channelStatusData[
                                    i].status + '</td>';
                            }

                            modalContent += '</tr>';
                        }

                        modalContent += '</tbody></table>';

                        // Update modal content with channel status details
                        $('#channelStatusDetails').html(modalContent);
                        $('#channelStatusModal').modal('show');
                    },
                    error: function(error) {
                        // Handle error
                        console.error('Error fetching channel status:', error);
                    }
                });
            });

            $('#gsm_table tbody').on('mouseenter', 'td', function() {
                var colIdx = gsm_table.cell(this).index().column;
                $(gsm_table.cells().nodes()).removeClass('highlight');
                $(gsm_table.column(colIdx).nodes()).addClass('highlight');
            });

            $('#detail_approved').change(function() {
                gsm_table.draw();
            });

            $('#detail_get_filter').click(function() {
                gsm_table.draw();
            });

            gsm_table.on('draw', function() {
                var body = $(gsm_table.table().body());
                body.unhighlight();
                body.highlight(gsm_table.search());
            });

        });

        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function(e, settings) {
                    // Call the original action function
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    dt.one('preXhr', function(e, s, data) {
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
        $(function() {
            var approve_campaign_table = $('#ivr_approve-table').DataTable({

                dom: 'lBfrtip',

                scrollCollapse: true,
                scrollX: true,
                scrollY: 700,
                buttons: [{
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
                ajax: {
                    url: "{{ route('ivr_approve') }}",
                    data: function(d) {
                        d.detail__approved = $('#detail_approved').val(),
                            d.detail_to_date = $('#detail_to_date').val(),
                            d.detail_from_date = $('#detail_from_date').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    },

                    @if (Auth::user()->user_master_id === 1)
                        {
                            data: 'user_name',
                            name: 'user_name',
                            className: 'text-left',
                            orderable: true,
                            searchable: true
                        },
                    @endif {
                        data: 'campaign_type',
                        name: 'campaign_type',
                        className: 'td_nowrap text-left',
                        render: function(data, type, row) {
                            if (data === 'C') {
                                return '<span style="color: green;">Customised</span>';
                            } else if (data === 'N') {
                                return '<span style="color: blue;">Generic</span>';
                            }
                        }
                    },
                    {
                        data: 'context',
                        name: 'context',
                        className: 'td_nowrap text-left'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        className: 'td_nowrap text-left'
                    },
                    {
                        data: 'prompt',
                        name: 'prompt',
                        className: "text-left"
                    },
                    {
                        data: 'entry_time',
                        name: 'entry_time',
                        className: "text-center"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        exportOptions: {
                            columns: ":visible"
                        }
                    },
                    {
                        data: 'approve',
                        name: 'approve',
                        orderable: false,
                        searchable: false,
                        exportOptions: {
                            columns: ":visible"
                        }
                    },
                ],
                rowCallback: function(row, data) {

                }
            });

            $('#approve_campaign_list-table tbody').on('mouseenter', 'td', function() {
                var colIdx = approve_campaign_table.cell(this).index().column;
                $(approve_campaign_table.cells().nodes()).removeClass('highlight');
                $(approve_campaign_table.column(colIdx).nodes()).addClass('highlight');
            });

            $('#detail_approved').change(function() {
                approve_campaign_table.draw();
            });

            $('#detail_get_filter').click(function() {
                approve_campaign_table.draw();
            });

            approve_campaign_table.on('draw', function() {
                var body = $(approve_campaign_table.table().body());
                body.unhighlight();
                body.highlight(approve_campaign_table.search());
            });

        });

        function newexportaction(e, dt, button, config) {
            var self = this;
            var oldStart = dt.settings()[0]._iDisplayStart;
            dt.one('preXhr', function(e, s, data) {
                // Just this once, load all data from the server...
                data.start = 0;
                data.length = 2147483647;
                dt.one('preDraw', function(e, settings) {
                    // Call the original action function
                    if (button[0].className.indexOf('buttons-copy') >= 0) {
                        $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                        $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                        $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                        $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                            $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                            $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                    } else if (button[0].className.indexOf('buttons-print') >= 0) {
                        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                    }
                    dt.one('preXhr', function(e, s, data) {
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
