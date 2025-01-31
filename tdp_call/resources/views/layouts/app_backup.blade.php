<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>OBD CALL</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src=""></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine-ie11.min.js" defer></script>
    
        <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class=" bg-red-200 min-h-screen font-base">
<div id="app">
    
    <div class="flex flex-col md:flex-row"> 

        @include('includes.sidebar')

        <div class="w-full md:flex-1">
            <nav class="hidden md:flex justify-between items-center bg-white p-4 shadow-md h-16">
                <div>
                    <input class="px-4 py-2 bg-gray-200 border border-gray-300 rounded focus:outline-none" type="text"
                           placeholder="Search.."/>
                </div>
                <div>
                    <button class="mx-2 text-gray-700 focus:outline-none"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg class="h-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </div>
            </nav>
            <main>
                <!-- Replace with your content -->
                <div class="px-8 py-6">
                    @yield('content')
                </div>
                <!-- /End replace -->
            </main>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>


<!-- Datatable for home page -->
<script src="https://unpkg.com/@themesberg/flowbite@1.2.0/dist/datepicker.bundle.js"></script>
<script type="text/javascript">
    $(function () {
        
      var table = $('.data-table').DataTable({
        'columnDefs': [ {
        'targets': [1,2,3,4,], /* column index */
        'orderable': false, /* true or false */
     }],

        dom: 'lBrtip',
        "buttons": [{
                    extend: 'excel',
                    text: 'E<u>X</u>CEL',
                    className:'mx-2     font-bold',
                    key: {
                        key: 'x',
                        altKey: true
                    }, 
                    action: newExportAction
                },{
                    extend: 'pdf',
                    text: ' PDF ',
                    className:'mx-2 font-bold', 
                    action: newExportAction,
                    pageSize: 'A4',
                    
                },
                {
                    extend: 'csv',
                    text: ' CSV ',
                    className:'mx-2 font-bold', 
                    action: newExportAction
                }],
        
        select: true,
        scrollY:'40vh',
        scrollCollapse: true,  
          processing: true,
          serverSide: true,
          ajax: {
            url: "{{ route('home') }}",
            data: function (d) {
                d.approved = $('#approved').val(),
                d.to_date=$('#to_date').val(),
                d.from_date=$('#from_date').val()
            }
            
          },
          columns: [
              {data:'id', name: 'id'},
              {data:'disposition',name:'disposition'},
              {data:'dcontext',name:'Context'},
              {data:'accountcode',name:'Account Code'},
              {data:'calldate',name:'Call Date'}
          ]
      }); 
        $('#approved').change(function(){
        table.draw();
        }); 
        $('#get_filter').click(function(){
            
            table.draw();
        });
        function newExportAction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) {
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
                 dt.one('preXhr', function (e, s, data) {
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
     $('#call').click(function(){
         
        var formData = {
        
                output_file_path:$('.output').val(),
                input_file_path:$('.input').val(),
                time_interval:$('.time').val(),
                file_count:$('.count').val(),

                
            };
        
        $.ajax({
        url : "http://59.92.107.49:8000/index.php",
        type: "POST",
        data: formData
    
        });
            
        });
     });
    
    
  </script>

<!-- displaying data in table form using Datatable (yajra ajax) for detail report page -->

<script src="https://unpkg.com/@themesberg/flowbite@1.2.0/dist/datepicker.bundle.js"></script>
<script type="text/javascript">
    $(function () {
        
      var detail_table = $('.detail_data-table').DataTable({
        'columnDefs': [ {
        'targets': [1,2,3,4,], /* column index */
        'orderable': false, /* true or false */
     }],

        dom: 'lBrtip',
        "buttons": [{
                    extend: 'excel',
                    text: 'E<u>X</u>CEL',
                    className:'mx-2     font-bold',
                    key: {
                        key: 'x',
                        altKey: true
                    }, 
                    action: newExportAction
                },{
                    extend: 'pdf',
                    text: ' PDF ',
                    className:'mx-2 font-bold', 
                    action: newExportAction,
                    pageSize: 'A4',
                    
                },
                {
                    extend: 'csv',
                    text: ' CSV ',
                    className:'mx-2 font-bold', 
                    action: newExportAction,
		
                }],
        
      //  select: true,
	order: [[ 8, "desc" ]],
       // scrollY:'40vh',
       // scrollCollapse: true,  
          processing: true,
          serverSide: true,
//	lengthMenu: [[25, 100,10000, -1], [25, 100, 10000,"All"]],
 //   pageLength: 25,
          ajax: {
            url: "{{ route('detailreport') }}",
            data: function (d) {
                d.detail_approved = $('#detail_approved').val(),
                d.detail_to_date=$('#detail_to_date').val(),
                d.detail_from_date=$('#detail_from_date').val()
            }
            
          },
          columns: [
              {data:'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
              {data:'accountcode',name:'accountcode'},
              {data:'dst',name:'dst'},
              {data:'src',name:'src'},
              {data:'disposition',name:'disposition'},
	      {data:'billsec',name:'billsec'},
	      {data:'dcontext',name:'dcontext'},
	      {data:'campaign',name:'campaign'},
	      {data:'calldate',name:'calldate'},
	      {data:'dtmfpressed', name:'dtmfpressed'},
          ]
      }); 
        $('#detail_approved').change(function(){
        detail_table.draw();
        }); 
        $('#detail_get_filter').click(function(){
            
            detail_table.draw();
        });
        function newExportAction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length =2147483647;
             dt.one('preDraw', function (e, settings) {
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
                 dt.one('preXhr', function (e, s, data) {
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
     $('#call').click(function(){
         
        var formData = {
        
                output_file_path:$('.output').val(),
                input_file_path:$('.input').val(),
                time_interval:$('.time').val(),
                file_count:$('.count').val(),

                
            };
        
        $.ajax({
        url : "http://59.92.107.49:8000/index.php",
        type: "POST",
        data: formData
    
        });
            
        });
     });
    

  </script>


<script src="https://unpkg.com/@themesberg/flowbite@1.2.0/dist/datepicker.bundle.js"></script>
<script type="text/javascript">
    $(function () {
        
      var summary_table = $('.summary_data-table').DataTable({
        'columnDefs': [ {
        'targets': [1,2,3,4,], /* column index */
        'orderable': false, /* true or false */
     }],

        dom: 'lBrtip',
        "buttons": [{
                    extend: 'excel',
                    text: 'E<u>X</u>CEL',
                    className:'mx-2     font-bold',
                    key: {
                        key: 'x',
                        altKey: true
                    }, 
                    action: newExportAction
                },{
                    extend: 'pdf',
                    text: ' PDF ',
                    className:'mx-2 font-bold', 
                    action: newExportAction,
                    pageSize: 'A4',
                    
                },
                {
                    extend: 'csv',
                    text: ' CSV ',
                    className:'mx-2 font-bold', 
                    action: newExportAction
                }],
        
      //  select: true,
       // scrollY:'40vh',
       // scrollCollapse: true,  
          processing: true,
          serverSide: true,
          ajax: {
            url: "{{ route('summaryreport') }}",
            data: function (d) {
                //d.summary_approved = $('#summary_approved').val(),
                d.summary_to_date=$('#summary_to_date').val(),
                d.summary_from_date=$('#summary_from_date').val()
            }
            
          },
          columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
              {data:'calldate',name:'calldate'},
              {data:'total_call',name:'total_call',},
              {data:'total_success',name:'total_success'}, 
              {data:'total_failure',name:'total_failure'}
          ]
      }); 
        
        $('#summary_get_filter').click(function(){
            
            summary_table.draw();
        });
        function newExportAction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) {
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
                 dt.one('preXhr', function (e, s, data) {
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
     $('#call').click(function(){
         
        var formData = {
        
                output_file_path:$('.output').val(),
                input_file_path:$('.input').val(),
                time_interval:$('.time').val(),
                file_count:$('.count').val(),

                
            };
        
        $.ajax({
        url : "http://59.92.107.49:8000/index.php",
        type: "POST",
        data: formData
            
        });
            
        });
     });
    
    
  </script> 

  <style>
      button.dt-button, div.dt-button, a.dt-button, input.dt-button {
          background-color: rgb(248, 183, 183) 

      }
      div.dataTables_wrapper div.dataTables_length label {
          font-weight: bold;
          color: black;
      }
      a:hover {
          text-decoration: none;
            
      }
  </style>
</body>
</html>
