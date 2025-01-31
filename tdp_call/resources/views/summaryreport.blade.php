<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')

<style>
.card { border: 0px solid rgba(0,0,0,.125) !important; }
.card-body { padding: 0rem !important; }
.card-header { border: 1px solid rgba(0,0,0,.125) !important; }

.bg-gray-800 {
    background-color: #00ee5a !important;
    color:black !important;
  }
  .text-left {text-align: center ;}
  th, td {
    text-align: left; /* Aligns text to the left */
    padding: 8px; /* Adds some padding for better spacing */
}
tbody tr td {
    height: 50px; /* Adjust height as needed */
}
/* .mt-2{
    margin-left: 100px;
} */
</style>


<!-- <script>
    // Function to reload the page
    function reloadPage() {
        location.reload();
    }

    // Set a timer to reload the page every 5000 milliseconds (5 seconds)
    setTimeout(reloadPage, 60000);
</script> -->


<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">               
    <div class="px-3" style="text-align: center; color:black; height: 50px; padding-top: 8px;">
       <h2 class="text-2xl font-medium" style="font-weight: bold;text-align: center;">Call Holding Report</h2>
	 <!-- <span class="mx-2 text-black text-xl uppercase font-bold"> Call Holding Report </span> -->
    </div>
    
    <div class="card">
        <div class="card-header">

		<!-- Date Filter Section -->
                <div class="row mt-2" style="width: 100%; margin-left:130px !important;">
                    <div class="col col-md-2"  style="text-align: right;line-height: 40px;">
                        <label><strong>From Date :</strong></label>
		    </div><div class="col col-md-2">
                        <input type="date" name="summary_from_date" id="summary_from_date" class="form-control" style="width: 100%" value="{{ Carbon\Carbon::now()->format('Y-m-d')}}"  onblur="func_fixtodate()">
                    </div>
                    <div class="col col-md-1" style="text-align: right;line-height: 40px;">
                        <from class="form-group">
                        <label><strong>To Date :</strong></label>
		    </div><div class="col col-md-2">
                        <div class="flex">
                            <input type="date" name="summary_to_date" id="summary_to_date" class="form-control" style="width: 100%" value="{{ Carbon\Carbon::now()->format('Y-m-d')}}"  onblur="func_fixtodate()">
                        </div>
		    </div><div class="col col-md-2">
			<button type="submit" class=" md:w-full bg-gray-800 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="summary_get_filter" style="width: 50%">Search</button>
		    </div> 
                        </from>
                        
                    </div>
                </div>
            
        </div>

	<!-- Table Section -->
        <div class="col card-body table-responsive mt-4">
            <table class="summary_data-table hover stripe" id="summary_data-table"   style="width:100%">
                <thead>
                <tr>
                        <th>No.</th>
                         <th>Date</th>
                         @if(Auth::user()->user_master_id === 1)
                <th>User Name</th>
                @endif
                        <th>Campaign Name</th>
			<th>Particulars</th>
			<th>Pulse</th>
			<th>Dialled</th>
			<th>Success</th>
			<th>Success%</th>
                        <th class="word_break">1-5 Secs</th>
                        <th class="word_break">6-10 Secs</th>
                        <th class="word_break">11-20 Secs</th>
                        <th class="word_break">21-30 Secs</th>
                        <th class="word_break">31-45 Secs</th>
			<th class="word_break">46-60 Secs</th>
                    </tr>
                </thead>
                <tbody>
                   </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function func_fixtodate() 
    {
        var today = new Date();
        var from_date = document.getElementById('summary_from_date');
        var fromDateLimit = new Date(today);
        fromDateLimit.setDate(fromDateLimit.getDate() - 30); // 30 days ago

        from_date.setAttribute('max', today.toISOString().split('T')[0]); // today as max date
        from_date.setAttribute('min', fromDateLimit.toISOString().split('T')[0]); // November 11th as min date

        var frmdate = $("#summary_from_date").val();
        $('#summary_to_date').attr('min', frmdate);
    }

    function func_fixfrmdate() {
    var today = new Date();
    var to_date = document.getElementById('summary_to_date');
    var from_date = document.getElementById('summary_from_date');

    // Set max date for the to_date input to today
    to_date.setAttribute('max', today.toISOString().split('T')[0]); // Set today as max date
    to_date.setAttribute('min', today.toISOString().split('T')[0]); // Set minimum date to today

    // Set the initial value for the to_date input to today
    to_date.value = today.toISOString().split('T')[0];

    // Update from_date max value based on to_date
    var todate = to_date.value;
    from_date.setAttribute('max', todate); // Set max date for from_date to match to_date
}

// Initialize dates on page load
window.onload = function () {
    func_fixfrmdate(); // Call the function to set date limits
};

</script>

<!-- End of 'content' section -->
    @endsection
