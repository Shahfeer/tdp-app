<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')

<style>
.card { border: 0px solid rgba(0,0,0,.125) !important; }
.card-body { padding: 0rem !important; }


.preloader-wrapper {
      display: flex;
      justify-content: center;
      background: rgba(22, 22, 22, 0.3);
      width: 100%;
      height: 100%;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1048;
      align-items: center;
    }

    .preloader-wrapper>.preloader {
      background: transparent url("/obd_call/public/Loader/ajaxloader.webp") no-repeat center top;
      min-width: 128px;
      min-height: 128px;
      
       z-index: 1048; 
       position: fixed;
    }

    .modal-footer {
    padding: 8px; /* Reduce the padding inside the footer */
    margin: 10px 0; /* Reduce the margin above and below the footer */
    display: flex; justify-content: flex-end;
}

.preloader-wrapper_stop {
      display: flex;
      justify-content: center;
      background: rgba(22, 22, 22, 0.3);
      width: 100%;
      height: 100%;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1048;
      align-items: center;
    }

    .preloader-wrapper_stop>.preloader {
      background: transparent url("/obd_call/public/Loader/ajaxloader.webp") no-repeat center top;
      min-width: 128px;
      min-height: 128px;
      
       z-index: 1048; 
       position: fixed;
    }
    .bg-gray-900 {
    background-color: #00ee5a !important;
    color:black !important;
  }

  .bg-gray-800 {
    background-color:#00ee5a !important;
    color:black !important;
  }

</style>



<meta name="csrf-token" content="{{ csrf_token() }}">

 <form>
    @csrf

<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">               
    <div class="px-3" style="text-align: center; color: black; height: 50px; padding-top: 8px;">
       <h2 class="text-2xl font-medium" style="font-weight: bold;">Campaign List</h2>
	<!-- <span class="mx-2 text-black text-xl uppercase font-bold"> Campaign List </span> -->
    </div>


   <!-- Create New Campaign Button -->
<div class="d-flex justify-content-end">
    <a href="{{ route('createcampaign') }}" class="md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 border-gray-500 rounded-full hover:border-gray-100" style="text-align: center;width: 230px;">Create a New Campaign</a>
</div
	

    <!-- Campaign List Table -->    
	  <!-- Filter Options -->
    <div class="card">
        <div class="card-header">

		  <div class="row mt-2" style="width: 100%;">
                <!--    <div class="col col-md-2" style="text-align: right;line-height: 40px;">
                        <select id='detail_approved' class="form-control" style="width: 100%">
                        <option value="">All Call</option>
                        <option value="created">Created Call</option>
                        <option value="processing">Processing Call</option>
			<option value="completed">Completed Call</option>
			<option value="declined">Declined Call</option>
                    	</select>
                    </div>  -->

		   <!-- From Date Input Field -->
		    <div class="col col-md-2" style="text-align: right;line-height: 40px;">
                        <label><strong>From Date :</strong></label>
                    </div>
		    <div class="col col-md-2">
			<input type="date" name="detail_from_date" id="detail_from_date" class="form-control" style="width: 100%" value="{{ Carbon\Carbon::now()->format('Y-m-d')}}" onblur="func_fixtodate()">
                    </div>

		   <!-- To Date Input Field -->
                    <div class="col col-md-1" style="text-align: right;line-height: 40px;">
                        <from class="form-group">
                        <label><strong>To Date :</strong></label>
                    </div><div class="col col-md-2">
                        <div class="flex">
			    <input type="date" name="detail_to_date" id="detail_to_date" class="form-control" style="width: 100%" value="{{ Carbon\Carbon::now()->format('Y-m-d')}}" onblur="func_fixtodate()">
                        </div>
                    </div><div class="col col-md-2">
			<button type="submit" class=" md:w-full bg-gray-800 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="detail_get_filter" style="width: 50%">Search</button>
                    </div>
                        </from>

                    </div>
                </div>
            
        </div>


        <div class="col card-body table-responsive mt-4">
            <table class="campaign_list-table hover stripe" id="campaign_list-table"   style="width:100%">
                <thead>
                    <tr>
                    <th>No.</th>
		  @if(Auth::user()->user_master_id === 1)
                <th>User Name</th>
                @endif
                    <th>Name</th>
                    <th>Context</th>
                    <th>Dialled</th>
		    <th>Success</th>
                    <th>Failed</th>
		    <th>Status</th>
            @if(Auth::user()->user_master_id === 1)
            <th>Campaign Action</th>
            <th>Neron Id</th>
            @endif
		    <th>Remarks</th>
                    <th style="width: 140px;">Created Date</th>
	 	    <th style="width: 140px;">Start Date</th>
		    <th style="width: 140px;">End Date</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form> 


<script>
    function func_fixtodate() {
        var today = new Date();
        var from_date = document.getElementById('detail_from_date');
        var fromDateLimit = new Date(today);
        fromDateLimit.setDate(fromDateLimit.getDate() - 30); // 30 days ago

        from_date.setAttribute('max', today.toISOString().split('T')[0]); // today as max date
        from_date.setAttribute('min', fromDateLimit.toISOString().split('T')[0]); // 30 days ago as min date

        var frmdate = from_date.value; // Get the selected "from date"
        if (frmdate) {
            document.getElementById('detail_to_date').setAttribute('min', frmdate); // Set "to date" min to "from date"
        }
    }

    function func_fixfrmdate() {
        var today = new Date();
        var to_date = document.getElementById('detail_to_date');
        
        to_date.setAttribute('max', today.toISOString().split('T')[0]); // today as max date

        var todate = to_date.value; // Get the selected "to date"
        if (todate) {
            document.getElementById('detail_from_date').setAttribute('max', todate); // Set "from date" max to "to date"
        }
    }

    // Initialize dates on page load
    window.onload = function () {
        func_fixtodate();
        func_fixfrmdate();
    };
</script>


<script>

function stopCampaign(campaign_name) {

    $('#alertModal_stop').modal('show');
    
    console.log(" campaign stop function");
    var campaignName = campaign_name;
    console.log(campaignName);

   $('.stop_campaign').on('click', function (){
      
	$(".preloader-wrapper_stop").show();

        $.ajax({
            url: '{{ route('stop_campaign') }}',
            method: 'POST',
            data: { campaign_name: campaign_name },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) 
            {
                if (response.success) 
                {
                    // Update the modal content with the response message
            		$('#responseMessage').text(response.message);
                    var table = $('#campaign_list-table').DataTable();
                    table.draw();

        	    } 
		        else 
		        {
            		// Handle the case where the API call was not successful
            		$('#responseMessage').text('API Error: ' + response.message);
        	    }

		$(".preloader-wrapper_stop").hide();

                $('#responseModal').modal('show');

                    $('body').on('click', function(e) 
                    {
                        // Check if the click target is outside of the modal
                        if (!$('#responseModal').is(e.target) && $('#responseModal').has(e.target).length === 0) 
                        {
                            // Close the modal if the click is outside
                            $('#responseModal').modal('hide');
                            // location.reload();
                        }
                    });
                
            },
            error: function(error) {

		$(".preloader-wrapper_stop").hide();

                // Handle errors
                $('#responseMessage').text('An error occurred: ' + error.statusText);
                $('#responseModal').modal('show');
                $('body').on('click', function(e) {
                    if (!$('#responseModal').is(e.target) && $('#responseModal').has(e.target).length === 0) {
                        $('#responseModal').modal('hide');
                       // location.reload();
                    }
                });
            }
        });
     })    
}

function restartCampaign(campaign_name) {

	 $('#alertModal_restart').modal('show');

	 $('.restart_campaign').on('click', function (){

    $(".preloader-wrapper").show();
    
    console.log("campaign restart")
    // Ajax call for restarting the campaign
    console.log('Restarting campaign: ' + campaign_name);
    // Perform your Ajax call or necessary actions here for restarting the campaign

    $.ajax({
            url: '{{ route('restart_campaign') }}',
            method: 'POST',
            data: { campaign_name: campaign_name },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) 
            {
                if (response.success) 
                {
                    // Update the modal content with the response message
            		$('#responseMessage').text(response.message);
                    var table = $('#campaign_list-table').DataTable();
                    table.draw();
        	    } 
		        else 
		        {
            		// Handle the case where the API call was not successful
            		$('#responseMessage').text('API Error: ' + response.message);
        	    }
	
		$(".preloader-wrapper").hide();

                $('#responseModal').modal('show');

                    $('body').on('click', function(e) 
                    {
                        // Check if the click target is outside of the modal
                        if (!$('#responseModal').is(e.target) && $('#responseModal').has(e.target).length === 0) 
                        {
                            // Close the modal if the click is outside
                            $('#responseModal').modal('hide');
                            //location.reload();
                        }
                    });
                
            },
            error: function(error) {
		$(".preloader-wrapper").hide();

                // Handle errors
                $('#responseMessage').text('An error occurred: ' + error.statusText);
                $('#responseModal').modal('show');
                $('body').on('click', function(e) {
                    if (!$('#responseModal').is(e.target) && $('#responseModal').has(e.target).length === 0) {
                        $('#responseModal').modal('hide');
                        //location.reload();
                    }
                });
            }
        });
    })    
}

</script>



 <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<!-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> -->

<!-- Confirmation Modal for stop the campaign -->
<div class="modal fade bs-example-modal-md" id="alertModal_stop" data-backdrop="true" tabindex="-1" role="dialog" aria-labelledby="alertModal_stop" aria-hidden="true"  style=" position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
    <div class="modal-dialog" style="pointer-events: auto;">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-body" style="border-top: 4px inset red;">
                <!-- Other confirmation content here -->
                <center>
               <!-- <p style="color: #333;">The Campaign - <span><strong id="campaign_name"></strong></span> is running...</p> -->
		<p style="color: #333;"><strong>Are you sure you want to stop the campaign?</strong></p>
                </center>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa;">
                <button type="button" class=" bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full stop_campaign"data-dismiss="modal">Yes</button>
                <button type="button" class=" bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full button0" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>


<!-- Confirmation Modal for restart the campaign -->
<div class="modal fade bs-example-modal-md" id="alertModal_restart" data-backdrop="true" tabindex="-1" role="dialog" aria-labelledby="alertModal_restart" aria-hidden="true"  style=" position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
    <div class="modal-dialog" style="pointer-events: auto;">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-body" style="border-top: 4px inset red;">
                <!-- Other confirmation content here -->
                <center>
               <!-- <p style="color: #333;">The Campaign - <span><strong id="campaign_name"></strong></span> is running...</p> -->
		<p style="color: #333;"><strong>Are you sure you want to restart the campaign?</strong></p>
                </center>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa;">
                <button type="button" class=" bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full restart_campaign"data-dismiss="modal">Yes</button>
                <button type="button" class=" bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full button0" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>


<!-- Approve response modal -->
<div class="modal fade bs-example-modal-md" id="responseModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="noChannelsModal" aria-hidden="true" data-backdrop="false" style=" position:fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
    <div class="modal-dialog">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-header" style="border-top: 4px inset red; text-align: center;">
                <h5 class="modal-title">Campaign Actions</h5>
            </div>
            <div class="modal-body">
                <p id="responseMessage"></p>
            </div>
		<div class="modal-footer">
                <button type="button" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="ok_Button" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>



<div class="preloader-wrapper_stop" style="display:none;">
      <div class="preloader">
      </div>
      <div class="text" style="color: white; background-color:#f27878; padding: 10px; margin-left:600px;">
     <b>Stopping the campaign can take some time<br/> Please wait.</b> 
      </div>
    </div>


<div class="preloader-wrapper" style="display:none;">
      <div class="preloader">
      </div>
      <div class="text" style="color: white; background-color:#f27878; padding: 10px; margin-left:600px;">
     <b>Restarting the campaign can take some time<br/> Please wait.</b> 
      </div>
    </div>


    @endsection
