<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')
               
    <div class="px-3" style="background-color: #FFF; height: 50px; padding-top: 8px;">
        <h2 class="text-2xl font-medium">Summary Report</h2>
    </div>
    
    <div class="card">
        <div class="card-header">

		<!-- Date Filter Section -->
                <div class="row mt-2" style="width: 100%;">
                    <div class="col col-md-2"  style="text-align: right;line-height: 40px;">
                        <label><strong>From Date :</strong></label>
		    </div><div class="col col-md-3">
                        <input type="date" name="summary_from_date" id="summary_from_date" class="form-control" style="width: 100%" value="{{ Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                    <div class="col col-md-2" style="text-align: right;line-height: 40px;">
                        <from class="form-group">
                        <label><strong>To Date Date :</strong></label>
		    </div><div class="col col-md-3">
                        <div class="flex">
                            <input type="date" name="summary_to_date" id="summary_to_date" class="form-control" style="width: 100%" value="{{ Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
		    </div><div class="col col-md-2">
			<button type="submit" class="py-2 px-4 bg-gray-700 text-white rounded hover:bg-gray-600 focus:outline-none" id="summary_get_filter" style="width: 100%">Search</button>
		    </div> 
                        </from>
                        
                    </div>
                </div>
            
        </div>

	<!-- Table Section -->
        <div class="col card-body table-responsive">
            <table class="summary_report_data-table hover stripe" id="summary_report_data-table"   style="width:100%">
                <thead>
                    <tr>
                    	<th>No.</th>
			<th>Call Date</th>
                        <th>Total Calls</th>
                        <th>Success calls</th>
                        <th>Failure calls</th>
                    </tr>
                </thead>
                <tbody>
                   </tbody>
            </table>
        </div>
    </div>

<!-- End of 'content' section -->
    @endsection

