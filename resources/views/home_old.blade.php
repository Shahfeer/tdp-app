<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')
<style>
    .shadow-lg {
        box-shadow: 10px 10px rgba(0, 0, 0, .175) !important;
    }
</style>


<script>
    // Function to reload the page
    function reloadPage() {
        location.reload();
    }

    // Set a timer to reload the page every 5000 milliseconds (5 seconds)
    setTimeout(reloadPage, 60000);
</script>


<div class="mx-auto w-full">
    @if(Auth::user()->user_master_id != 1)
    <div>
        <!-- Card starts -->
        <div class="flex flex-wrap -mx-4">
        @forelse($adminData as $admin)
            <!-- Total Calls Card -->
            <div class="w-full md:w-1/3 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                    <div class="flex-auto p-4">
                        <div class="flex flex-wrap">
                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                <h5 class="text-black uppercase font-bold text-xl">
                                    Total Calls
                                </h5>
                                <span class="font-semibold text-xl text-gray-800">
                                {{ $admin->total_call ?? 0 }}
                                </span>
                            </div>
                            <div class="relative w-auto px-2 flex-initial">
                                <div class="text-white text-2xl text-2xl p-3 font-bold text-center inline-flex items-center justify-center w-14 h-14 shadow-lg rounded-full bg-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                        <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-- Success Calls Card -->
            <div class="w-full md:w-1/3 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                    <div class="flex-auto p-4">
                        <div class="flex flex-wrap">
                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                <h5 class="text-black uppercase font-bold text-xl">
                                    Success Calls
                                </h5>
                                <span class="font-semibold text-xl text-gray-800">
                                    {{ $admin->total_success ?? 0 }}

                                </span>
                            </div>
                            <div class="relative w-auto px-2 flex-initial">
                                <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                                    <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Success Percentage Card -->
            <div class="w-full md:w-1/3 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                    <div class="flex-auto p-4">
                        <div class="flex flex-wrap">
                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                <h5 class="text-black    uppercase font-bold text-xl">
                                    Success Percentage
                                </h5>
                                <span class="font-semibold text-xl text-gray-800">
                                {{ number_format($admin->percentage ?? 0, 2) }}%
                                </span>
                            </div>
                            <div class="relative w-auto px-2 flex-initial">
                                <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                                    <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @empty
            <div class="w-full md:w-1/3 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                    <div class="flex-auto p-4">
                        <div class="flex flex-wrap">
                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                <h5 class="text-black uppercase font-bold text-xl">
                                    Total Calls
                                </h5>
                                <span class="font-semibold text-xl text-gray-800">
                                 0
                                </span>
                            </div>
                            <div class="relative w-auto px-2 flex-initial">
                                <div class="text-white text-2xl text-2xl p-3 font-bold text-center inline-flex items-center justify-center w-14 h-14 shadow-lg rounded-full bg-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                        <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-- Success Calls Card -->
            <div class="w-full md:w-1/3 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                    <div class="flex-auto p-4">
                        <div class="flex flex-wrap">
                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                <h5 class="text-black uppercase font-bold text-xl">
                                    Success Calls
                                </h5>
                                <span class="font-semibold text-xl text-gray-800">
                                    0
                                </span>
                            </div>
                            <div class="relative w-auto px-2 flex-initial">
                                <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                                    <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Success Percentage Card -->
            <div class="w-full md:w-1/3 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                    <div class="flex-auto p-4">
                        <div class="flex flex-wrap">
                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                <h5 class="text-black    uppercase font-bold text-xl">
                                    Success Percentage
                                </h5>
                                <span class="font-semibold text-xl text-gray-800">
                               0
                                </span>
                            </div>
                            <div class="relative w-auto px-2 flex-initial">
                                <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                                    <svg class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    @endif


    <!-- if the user is admin -->
<?php
use Illuminate\Support\Facades\DB;

$Ivr_Data = DB::table('prompt_masters')
->where('prompt_status', 'N')
->count();

$Camp_Data = DB::table('calls')
->where('call_status', 'C')
->count();

?>
    @if(Auth::user()->user_master_id == 1)

    <div class="flex flex-wrap ">

        <div class="w-full md:w-1/3 px-4">
    <a href="{{ route('approve_campaign') }}">
            <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                <div class="flex-auto p-4">
                    <div class="flex flex-wrap">
                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                            <h5 class="text-black uppercase font-bold text-xl">
                                Waiting For Campaign Approval
                            </h5>
                            <span class="font-semibold text-xl text-gray-800">
                            {{ $Camp_Data }}
                            </span>
                        </div>
                        <div class="relative w-auto px-2 flex-initial">
                            <div class="text-white text-2xl text-2xl p-3 font-bold text-center inline-flex items-center justify-center w-14 h-14 shadow-lg rounded-full bg-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                                    <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </a>
        </div>

        <div class="w-full md:w-1/3 px-4">
        <a href="{{ route('ivr_approve') }}">
            <div class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                <div class="flex-auto p-4">
                    <div class="flex flex-wrap">
                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                            <h5 class="text-black uppercase font-bold text-xl">
                                Waiting For IVR Approval
                            </h5>
                            <span class="font-semibold text-xl text-gray-800">

                             {{ $Ivr_Data }}
                        
                            </span>
                        </div>
                        <div class="relative w-auto px-2 flex-initial">
                            <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note" viewBox="0 0 16 16">
                                    <path d="M9 13c0 1.105-1.12 2-2.5 2S4 14.105 4 13s1.12-2 2.5-2 2.5.895 2.5 2" />
                                    <path fill-rule="evenodd" d="M9 3v10H8V3z" />
                                    <path d="M8 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 13 2.22V4L8 5z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            </a>
        </div>
    </div>

    <!-- Display data for admin -->
    <div class="flex flex-wrap -mx-4 mt-5">
        @foreach($adminData as $admin)
        <div class="w-full md:w-1/3 px-4 mb-4"> <!-- Add the "mb-4" class for margin-bottom -->
            <div class="relative flex flex-col min-w-0 break-words bg-white rounded xl:mb-0 shadow-lg">
                <div class="flex-auto p-4">
                    <div class="flex flex-wrap">
                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                            <h5 class="text-red-500 uppercase font-bold text-xl text-center">
                                {{ $admin->user_name }}
                            </h5>
                            <p class="font-semibold text-xl text-gray-800">
                                Total Calls: {{ $admin->total_call }}
                            </p>
                            <p class="font-semibold text-xl text-gray-800">
                                Success Calls: {{ $admin->total_success }}
                            </p>
                            <p class="font-semibold text-xl text-gray-800">
                                Success Percentage: {{ number_format($admin->percentage, 2) }}%
                            </p>
                            <!--  <p class="font-semibold text-xl text-gray-800">
                                Available Credit: {{ $admin->available_credits }}
                            </p> -->
                            <p class="font-semibold text-xl text-gray-800">
                                Available Credit:
                                @if (isset($admin->available_credits))
                                {{ $admin->available_credits }}
                                @else
                                &nbsp;&nbsp;&nbsp;&nbsp;-
                                @endif
                            </p>
                        </div>
                        <div class="relative w-auto px-2 flex-initial">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @endif


    <!-- Bar Chart -->

    @if(Auth::user()->user_master_id != 1)

    <div class="card" style="margin-top:30px">
        <div class="px-3" style="background-color: #FFF; height: 50px; padding-top: 8px; margin-bottom: 20px;">
            <h2 class="text-2xl font-medium">Today's Campaign</h2>
        </div>

        <div class="card-header" style="background-color:#fff">
            <div style="height:500px;width:900px;margin:auto;background-color:#fff">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>
    <div class="flex flex-wrap -mx-4">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js" integrity="sha512-vBmx0N/uQOXznm/Nbkp7h0P1RfLSj0HQrFSzV8m7rOGyj30fYAOKHYvCNez+yM8IrfnW0TCodDEjRqf6fodf/Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            // JavaScript for Chart.js
            const cdrData = @json($adminData);
            const currentDate = new Date().toLocaleDateString();
            const chartLabels = cdrData.map(() => currentDate);
            const totalData = cdrData.map((data) => data.total_call);
            const successData = cdrData.map((data) => data.total_success);
            const failureData = cdrData.map((data) => data.total_failure);

            const ctx = document.getElementById('barChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                            label: "Today's Total Calls",
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2,
                            data: totalData,
                        },
                        {
                            label: "Today's Success Calls",
                            //backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            //borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(91, 212, 103, 0.2)',
                            borderColor: 'rgba(91, 212, 103, 1)',
                            borderWidth: 2,
                            data: successData,
                        },
                        {
                            label: "Today's Failure Calls",
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            data: failureData,
                        },
                    ],
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                            },
                        }, ],
                    },
                },
            });
        </script>


    </div>
</div>
@endif

<!-- End of 'content' section -->
@endsection