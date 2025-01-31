@extends('layouts.app')
@section('content')


<style>
.card { border: 0px solid rgba(0,0,0,.125) !important; }
.card-body { padding: 0rem !important; }
.card-header { border: 1px solid rgba(0,0,0,.125) !important; }


red-background {
    background-color: red;
}

.default-background {
    background-color: #fff; /* Default background color */
}

.bg-gray-800 {
    background-color: #00ee5a !important;
    color:black !important;
  }
  .btn-dark-green {
    background-color: #006400; /* Dark green color */
    color: white; /* Text color */
    text-decoration: none; /* Remove underline */
    padding: 10px 15px; /* Padding for the button */
    border-radius: 5px; /* Rounded corners */
}
.btn-success {
    color: white !important;
    background-color: green !important;
    padding: 12px 24px; /* Adjust padding */
    font-size: 16px; /* Adjust font size */
}

</style>


<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">
     <!-- Page Title -->               
    <div style="height: 50px; color: black; text-align: center; padding-top: 8px; font-size: 30px;" class="px-3">
        <h2 class="text-2xl font-medium" style="font-weight: bold;">Call Detail Report</h2>
    </div>

    
    <!-- Filter Options -->
    <div class="card">
        <div class="card-header">

		<div class="row mt-2" style="width: 100%;">
                    <div class="col col-md-2" style="text-align: right;line-height: 40px;">
                        <select id='detail_approved' class="form-control" style="width: 100%">
                        <option value="">All Call</option>
                        <option value="answered">Success Call</option>
                        <option value="no answer">Failure Call</option>
                    	</select>
                    </div> 

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
			<button type="submit" class=" md:w-full bg-gray-800 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="detail_get_filter" style="width: 100%">Search</button>
                    </div>
                        </from>

                    </div>
                </div>

                
        </div>

	 <!-- Table for Displaying Detail Data -->
        <div class="col card-body table-responsive mt-4" id="table-div">
            <table class="detail_data-table hover stripe" id="detail_data-table" style="width:100%">
                <thead>
                    <tr>
                    <th>No.</th>
                    @if(Auth::user()->user_master_id === 1)
                    <th>User Name</th>
                    @endif
		    <th>Date</th>
                    <th>Campaign Name</th>
                    <th>Particulars</th>
                    <th>Dialled</th>
                    <th>Success</th>
                    <th>Failed</th>
                    <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    function downloadCdr(campaign_id) {
        // Append the campaign name to the download link's URL
        console.log(campaign_id);

        $.ajax({
            url: '{{ route("get_download_url") }}', // Replace with your route to fetch download_url
            type: 'GET',
            data: {campaign_id: campaign_id},
            success: function(response) {
                var download_url = response.download_url;
                if (download_url) {
                    // Redirect the user to the download URL
                    window.location.href = download_url;
                } else {
                    console.error('Download URL not found.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.error(xhr.responseText);
            }
        });
        

    }
</script>

<script>
    // function func_fixtodate() 
    // {
    //     var today = new Date();
    //     var from_date = document.getElementById('detail_from_date');
    //     var fromDateLimit = new Date(today);
    //     fromDateLimit.setDate(fromDateLimit.getDate() - 90); // 30 days ago

    //     from_date.setAttribute('max', today.toISOString().split('T')[0]); // today as max date
    //     from_date.setAttribute('min', fromDateLimit.toISOString().split('T')[0]); // November 11th as min date

    //     var frmdate = $("#detail_from_date").val();
    //     $('#detail_to_date').attr('min', frmdate);
    // }

    // function func_fixfrmdate() 
    // {
    //     var today = new Date();
    //     var to_date = document.getElementById('detail_to_date');
    //     var toDateLimit = new Date(today);
    //     toDateLimit.setDate(toDateLimit.getDate() - 90); // 30 days ago

    //     to_date.setAttribute('max', today.toISOString().split('T')[0]); //today as max date
    //     to_date.setAttribute('min', toDateLimit.toISOString().split('T')[0]);

    //     var todate = $("#detail_to_date").val();
    //     $('#detail_from_date').attr('max', todate);
    // }

    // // Initialize dates on page load
    // window.onload = function () {
    //     func_fixtodate();
    //     func_fixfrmdate();
    // };

    $(document).ready(function() {
    // Get the current date
    var currentDate = new Date();
    var currentDateString = currentDate.toISOString().split("T")[0];

    // Set the default From Date and To Date to the current date
    $('#detail_from_date').val(currentDateString);
    $('#detail_to_date').val(currentDateString);

    // Set the max attribute of both dates to today
    $('#detail_from_date').attr('max', currentDateString);
    $('#detail_to_date').attr('max', currentDateString);

    // Disable previous dates for From Date (up to 30 days ago)
    var thirtyDaysAgo = new Date(currentDate);
    thirtyDaysAgo.setDate(currentDate.getDate() - 30);
    var thirtyDaysAgoString = thirtyDaysAgo.toISOString().split("T")[0];

    $('#detail_from_date').attr('min', thirtyDaysAgoString);
    $('#detail_to_date').attr('min', currentDateString); // Initially hide previous dates for To Date

    // Handle change event for From Date
    $('#detail_from_date').change(function() {
        // Parse selected date
        var selectedFromDate = new Date($(this).val());

        // Update To Date to start from the selected From Date
        // $('#detail_to_date').val(selectedFromDate.toISOString().split("T")[0]);

        // Set the min attribute of To Date to the selected From Date
        $('#detail_to_date').attr('min', selectedFromDate.toISOString().split("T")[0]);
    });
});


</script>

@endsection
