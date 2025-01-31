@extends('layouts.app')
@section('content')
               
    <div class="mt-4 px-3 ">
        <h2 class="text-2xl font-medium">Detail Report</h2>
    </div>
    
    <div class="card">
        <div class="card-header">
            
                <div class="row mt-2">
                    <div class="col col-sm-12 col-md-3">
                        <label><strong>Filter :</strong></label>
                    <select id='detail_approved' class="form-control" style="width: 200px">
                        <option value="">All Call</option>
                        <option value="ANSWERED">Success Call</option>
                        <option value="NO ANSWER">Failure Call</option>
                    </select>
                    </div>
                    <div class="col  col-md-3">
                        <label><strong>From Date :</strong></label>
                        <input type="date" name="detail_from_date" id="detail_from_date" class="form-control" style="width:200px" value="{{ Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                    <div class="col  col-md-3">
                        <from class="form-group">
                        <label><strong>To Date :</strong></label>
                        <div class="flex">
                            <input type="date" name="detail_to_date" id="detail_to_date" class="form-control" style="width:200px" value="{{ Carbon\Carbon::now()->format('Y-m-d')}}">
                            <button type="submit" class=" btn btn-primary mx-2 font-bold" id="detail_get_filter" style="width:200px">Search</button>
                        </div>
                        </from>
                    </div>
                </div>
            
        </div>
        <div class="col card-body table-responsive">
            <table class="detail_data-table" id="detail_data-table"   style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Call Id</th>
                        <th>Receiver Mobile No</th>
                        <th>Sender Mobile No</th>
                        <th>Call Status</th>
			<th>Call Duration </th>
			<th>Context</th>
			<th>Campaign Name</th>
			<th>Call Time</th>
			<th>Digits Pressed</th>

                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
