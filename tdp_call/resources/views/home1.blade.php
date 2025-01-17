@extends('layouts.app')
@section('content')
    <div class="mx-auto w-full">
        <div>
            <!-- Card stats -->
            <div class="flex flex-wrap -mx-4">
                <div class="w-full md:w-1/3 px-4">
                    <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                        <div class="flex-auto p-4">
                            <div class="flex flex-wrap">
                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                    <h5 class="text-black uppercase font-bold text-xl">
                                        Total Calls  
                                    </h5>
                                    <span class="font-semibold text-xl text-gray-800">
                                        {{{$datas['total_call']}}}
                        </span>
                                </div>  
                                <div class="relative w-auto px-2 flex-initial">
                                    <div
                                            class="text-white text-2xl text-2xl p-3 font-bold text-center inline-flex items-center justify-center w-14 h-14 shadow-lg rounded-full bg-orange-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                              </svg>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-4">
                    <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                        <div class="flex-auto p-4">
                            <div class="flex flex-wrap">
                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                    <h5 class="text-black uppercase font-bold text-xl">
                                        Success Calls
                                    </h5>
                                    <span class="font-semibold text-xl text-gray-800">
                                        {{{$datas['total_success']}}}

                        </span>
                                </div>
                                <div class="relative w-auto px-2 flex-initial">
                                    <div
                                            class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-pink-500">
                                        <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                             stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/3 px-4">
                    <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                        <div class="flex-auto p-4">
                            <div class="flex flex-wrap">
                                <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                    <h5 class="text-black    uppercase font-bold text-xl">
                                        Success Percentage
                                    </h5>
                                    <span class="font-semibold text-xl text-gray-800">
                                        {{{number_format((($datas['total_success']/$datas['total_call']) * 100),2)."%"}}}
                                        
                                        
                                        
                          
                        </span>
                                </div>
                                    <div class="relative w-auto px-2 flex-initial">
                                        <div
                                                class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-blue-500">
                                            <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
    <div class="mt-4 px-3 ">
      <!-- <h2 class="text-2xl font-medium">CDR Report</h2>-->
    </div>
    <div class="card" style="display: none;">
        <div class="card-header">
            
                <div class="row mt-2">
                    <div class="col col-sm-12 col-md-3">
                        <label><strong>Filter :</strong></label>
                    <select id='approved' class="form-control" style="width: 200px">
                        <option value="">All Call</option>
                        <option value="ANSWERED">Success Call</option>
                        <option value="NO ANSWER">Failer Call</option>
                    </select>
                    </div>
                    <div class="col  col-md-3">
                        <label><strong>From Date :</strong></label>
                        <input type="date" name="from_date" id="from_date" class="form-control" style="width:200px" value="{{ Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                    <div class="col  col-md-3">
                        <from class="form-group">
                        <label><strong>To Date Date :</strong></label>
                        <div class="flex">
                            <input type="date" name="to_date" id="to_date" class="form-control" style="width:200px" value="{{ Carbon\Carbon::now()->format('Y-m-d')}}">
                            <button type="submit" class=" btn btn-primary mx-2 font-bold" id="get_filter" style="width:200px">Get</button>
                        </div>
                        </from>
                    </div>
                </div>
            
        </div>
        <div class="col card-body table-responsive">
            <table class="data-table" id="data-table"   style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Answered Call</th>
                        <th>Context</th>
                        <th>Account Code</th>
                        <th>Call Date</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection


