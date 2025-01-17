<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')

<style>
.card { border: 0px solid rgba(0,0,0,.125) !important; }
.card-body { padding: 0rem !important; }
</style>



<meta name="csrf-token" content="{{ csrf_token() }}">

 <form>
    @csrf

<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">               
    <div class="px-3" style="text-align: center; color: black; height: 50px; padding-top: 8px;">
        <h2 class="text-2xl font-medium" style="font-weight: bold;">Campaign List</h2>
    </div>


   <!-- Create New Campaign Button -->
<div class="d-flex justify-content-end">
    <a href="{{ route('createcampaign') }}" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" style="text-align: center;width: 230px;">Create a New Campaign</a>
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
                    <div class="col col-md-2" style="text-align: right;line-height: 40px;">
                        <from class="form-group">
                        <label><strong>To Date :</strong></label>
                    </div><div class="col col-md-2">
                        <div class="flex">
			    <input type="date" name="detail_to_date" id="detail_to_date" class="form-control" style="width: 100%" value="{{ Carbon\Carbon::now()->format('Y-m-d')}}" onblur="func_fixtodate()">
                        </div>
                    </div><div class="col col-md-2">
			<button type="submit" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="detail_get_filter" style="width: 100%">Search</button>
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
                    <th>Campaign Name</th>
                    <th>Context</th>
                    <th>Total Calls</th>
		    <th>Success calls</th>
                    <th>Failure calls</th>
		    <th>Status</th>
            @if(Auth::user()->user_master_id === 1)
            <th>Campaign Action</th>
            @endif
		    <th>Remarks</th>
                    <th>Campaign Created Date</th>
	 	    <th>Campaign Start Date</th>
		    <th>Campaign Completed Date</th>
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
        var frmdate = $("#detail_from_date").val();
        $('#detail_to_date').attr('min', frmdate);
    }

    function func_fixfrmdate() {
        var todate = $("#detail_to_date").val();
        $('#detail_from_date').attr('max', todate);
    }
</script>


<script>

function stopCampaign(campaign_name) {
    
    console.log(" campaign stop function");
    var campaignName = campaign_name;
    console.log(campaignName);

      
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
                //     // Chan
                    // Update the modal content with the response message
            		$('#responseMessage').text(response.message);
        	    } 
		        else 
		        {
            		// Handle the case where the API call was not successful
            		$('#responseMessage').text('API Error: ' + response.message);
        	    }
                $('#responseModal').modal('show');

                    $('body').on('click', function(e) 
                    {
                        // Check if the click target is outside of the modal
                        if (!$('#responseModal').is(e.target) && $('#responseModal').has(e.target).length === 0) 
                        {
                            // Close the modal if the click is outside
                            $('#responseModal').modal('hide');
                            location.reload();
                        }
                    });
                
            },
            error: function(error) {
                // Handle errors
                $('#responseMessage').text('An error occurred: ' + error.statusText);
                $('#responseModal').modal('show');
                $('body').on('click', function(e) {
                    if (!$('#responseModal').is(e.target) && $('#responseModal').has(e.target).length === 0) {
                        $('#responseModal').modal('hide');
                        location.reload();
                    }
                });
            }
        });
    
}

function restartCampaign(campaign_name) {
    
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
                //     // Chan
                    // Update the modal content with the response message
            		$('#responseMessage').text(response.message);
        	    } 
		        else 
		        {
            		// Handle the case where the API call was not successful
            		$('#responseMessage').text('API Error: ' + response.message);
        	    }
                $('#responseModal').modal('show');

                    $('body').on('click', function(e) 
                    {
                        // Check if the click target is outside of the modal
                        if (!$('#responseModal').is(e.target) && $('#responseModal').has(e.target).length === 0) 
                        {
                            // Close the modal if the click is outside
                            $('#responseModal').modal('hide');
                            location.reload();
                        }
                    });
                
            },
            error: function(error) {
                // Handle errors
                $('#responseMessage').text('An error occurred: ' + error.statusText);
                $('#responseModal').modal('show');
                $('body').on('click', function(e) {
                    if (!$('#responseModal').is(e.target) && $('#responseModal').has(e.target).length === 0) {
                        $('#responseModal').modal('hide');
                        location.reload();
                    }
                });
            }
        });
    
}

</script>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>



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
                <button type="button" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="ok_Button" onclick="window.location.reload();">OK</button>
            </div>
        </div>
    </div>
</div>

    @endsection
