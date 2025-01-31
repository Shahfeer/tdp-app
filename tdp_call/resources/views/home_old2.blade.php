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
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

<div class="mx-auto w-full">

@if(Auth::user()->user_master_id != 1)
@php $displayContent = false; @endphp
    @foreach($adminData as $admin)
        @if(date('Y-m-d', strtotime($admin->call_entry_time)) == now()->toDateString())
            @php $displayContent = true; @endphp
            @break
        @endif
    @endforeach

    @if($displayContent)
    <div>
        <!-- Card starts -->
        <div class="card-header" style="background-color:#fff">
            <div class="px-3" style="background-color: #FFF; height: 30px; padding-top: 8px; margin-bottom: 20px;">
                <h2 class="text-2xl font-bold">Today's Campaign Summary</h2>
            </div>
        <div class="flex flex-wrap -mx-4">
        @forelse($adminData as $admin)
        @if(date('Y-m-d', strtotime($admin->call_entry_time)) == now()->toDateString())
            <!-- Total Calls Card -->
            <div class="w-full md:w-1/3 px-2 mb-3" style="width: 33%;">
            <div class="relative w-full" style="
                @if($admin->call_status == 'O')
                    background-color: #9fdf9f; /* green color */
                @elseif($admin->call_status == 'P')
                    background-color: #ffa64d; /* purple color */
                @elseif($admin->call_status == 'C')
                    background-color: #80ccff; /* Light blue color */
                @elseif($admin->call_status == 'D')
                    background-color: #ff8080; /* Light red color */
                @endif">
                    <div class="flex-auto p-4">
                        <div class="flex flex-wrap" style = "height: 150px;">
                            <div class="relative w-full">
                                <h5 class="text-black uppercase font-bold text-xl" >
                                    {{ $admin->campaign_name ?? 0 }}
                                </h5>
                            <span class="font-normal text-xl text-gray-800">
                                Total Calls - {{ $admin->no_of_mobile_numbers ?? 0 }}
                            </span>
                            </br>
                            <span class="font-normal text-xl text-gray-800">
                                Status - 
                                @if($admin->call_status == 'P')
                                    InProgress
                                @elseif($admin->call_status == 'C')
                                    Pending
                                @elseif($admin->call_status == 'O')
                                    Completed
                                @elseif($admin->call_status == 'D')
                                    Rejected
                                @elseif($admin->call_status == 'S')
                                    Stopped
                                @else
                                    Unknown
                                @endif
                            </span>
                            </br>
                            </br>
                            @if($admin->call_status == 'O')
                            <span class="font-normal text-xl text-gray-800" style="float: left">
                                Success Calls - {{ $admin->total_success ?? 0 }}
                            </span>
                            <span class="font-normal text-xl text-gray-800" style="float: right">
                                Failure Calls - {{ $admin->total_failure ?? 0 }}
                            </span>
                            @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endif
            @empty
            @endforelse
        </div>
    </div>
</div>
@endif
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
            <div class="px-3" style="background-color: #FFF; height: 20px; padding-top: 10px; margin-bottom: 20px;">
                <h2 style="float: right; text: center" class="text-2xl font-bold">Last one Week's Campaign</h2>
                <h2 class="text-2xl font-bold">Last 3 Day's Campaign</h2>
            </div>

            <!-- <div class="px-3" style="background-color: #FFF; height: 50px; padding-top: 8px; margin-bottom: 20px;">
               
            </div> -->

            <!-- <div class="card-header" style="background-color:#fff"> -->
                <div class="flex flex-wrap">
                    <div class="w-full md:w-1/2 px-4">
                        <div style="height:600px;width:110%;background-color:#fff">
                        <label for="yAxisScale">Total calls Scale:</label>
                        <select id="yAxisScale" style = "max-width:700px; height: 28px;">
                            <option value="200000">2,00,000</option>
                            <option value="500000">5,00,000</option>
                        </select>
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 px-2">
                        <div style="height:300px;width:200%;max-width:600px;background-color:#fff">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
<!--  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js" integrity="sha512-vBmx0N/uQOXznm/Nbkp7h0P1RfLSj0HQrFSzV8m7rOGyj30fYAOKHYvCNez+yM8IrfnW0TCodDEjRqf6fodf/Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>  
    <script>

        // JavaScript for Chart.js
        const cdrData1 = @json($adminData);

        // Filter the cdrData1 array to include only elements where summary_report_status is 'Y'
        const filteredData = cdrData1.filter(data => data.summary_report_status === 'Y');
         console.log(filteredData);

         // Get the current date
        const currentDate = new Date();

        // Filter the cdrData1 array to include only data for the last three days
        const lastThreeDaysData = filteredData.filter(data => {
            const reportDate = new Date(data.report_date);
            // Calculate the difference in days between the current date and the report date
            const differenceInDays = Math.ceil((currentDate - reportDate) / (1000 * 60 * 60 * 24));
            // Return true if the difference is less than or equal to 3 (last three days)
            return differenceInDays <= 3;
        });
        
        // Create an object to store aggregated data by date
        const aggregatedData = {};

     
        // Iterate over lastThreeDaysData to aggregate data by date
        lastThreeDaysData.forEach(data => {
            const chartLabels = data.report_date;
            if (!aggregatedData[chartLabels]) {
                // Initialize aggregatedData[date] if it doesn't exist
                aggregatedData[chartLabels] = {
                    total: 0,
                    success: 0,
                    failure: 0,
                };
            }
            // Increment total count
            aggregatedData[chartLabels].total += parseInt(data.total_call);
            aggregatedData[chartLabels].success += parseInt(data.total_success);
            aggregatedData[chartLabels].failure += parseInt(data.total_failure) + parseInt(data.total_busy) + parseInt(data.total_no_answer);
        });


        // Extract aggregated data into arrays for chart rendering
        const dates = Object.keys(aggregatedData);
        console.log(dates)
        const totalData = dates.map(chartLabels => aggregatedData[chartLabels].total);
        console.log(totalData);
        const successData = dates.map(chartLabels => aggregatedData[chartLabels].success);
        console.log(successData);
        const failureData = dates.map(chartLabels => aggregatedData[chartLabels].failure);
        console.log(failureData);

        const ctx = document.getElementById('barChart').getContext('2d');
        let chart; // Define chart variable outside to access it inside event listener

        function updateChart(yAxisScale) {
            console.log(yAxisScale);
            const maxDataValue = Math.max(...totalData); // Get maximum data value
            console.log(maxDataValue);
            const numTicks = Math.ceil(maxDataValue / yAxisScale); // Calculate number of ticks based on maxDataValue and yAxisScale
            console.log(numTicks);

            chart.options.scales.yAxes[0].ticks.min = 0;
            chart.options.scales.yAxes[0].ticks.max = yAxisScale * numTicks;
            chart.options.scales.yAxes[0].ticks.stepSize = yAxisScale;
            
            chart.update();
        }

        chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [{
                    label: "Total Calls",
                    backgroundColor: 'rgba(77, 121, 255)',
                    borderColor: 'rgba(77, 121, 255)',
                    borderWidth: 3,
                    data: totalData,
                },
                {
                    label: "Success Calls",
                    backgroundColor: 'rgba(60, 179, 113)',
                    borderColor: 'rgba(60, 179, 113)',
                    borderWidth: 3,
                    data: successData,
                },
                {
                    label: "Failure Calls",
                    backgroundColor: 'rgba(255, 0, 0)',
                    borderColor: 'rgba(255, 0, 0)',
                    borderWidth: 3,
                    data: failureData,
                },
            ],
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        min: 400000,
                    },
                    gridLines: {
                        drawBorder: true, // Display y-axis border line
                        drawOnChartArea: false, // Do not draw grid lines within the chart area
                    },
                }],
                xAxes: [{
                    gridLines: {
                        drawBorder: true, // Display x-axis border line
                        drawOnChartArea: false, // Do not draw grid lines within the chart area
                    },
                },],
            },
        },
    });

    updateChart(400000);

   // Event listener for changing y-axis scale
   document.getElementById('yAxisScale').addEventListener('change', function() {
    console.log("!!!");
        const yAxisScale = parseInt(this.value);
        console.log(yAxisScale);
        updateChart(yAxisScale);
    });

   // Initialize variables to store total counts
    let totalCallsCount = 0;
    let totalSuccessCount = 0;
    let totalFailureCount = 0;
    let totalBusyCount = 0;
    let totalNoanswerCount = 0;

    // Iterate over the cdrData1 array to accumulate counts
    filteredData.forEach(data => {
        // Convert string values to numbers using parseInt() or parseFloat()
        const totalCall = parseInt(data.total_call);
        const totalSuccess = parseInt(data.total_success);
        const totalFailure = parseInt(data.total_failure);
        const totalBusy = parseInt(data.total_busy);
        const totalNoanswer = parseInt(data.total_no_answer);

        // Check if the conversion is successful
        if (!isNaN(totalCall)) totalCallsCount += totalCall;
        if (!isNaN(totalSuccess)) totalSuccessCount += totalSuccess;
        if (!isNaN(totalFailure)) totalFailureCount += totalFailure;
        if (!isNaN(totalBusy)) totalBusyCount += totalBusy;
        if (!isNaN(totalNoanswer)) totalNoanswerCount += totalNoanswer;
    });

    console.log("Total Calls Count:", totalCallsCount);
    console.log("Total Success Count:", totalSuccessCount);
    console.log("Total Failure Count:", totalFailureCount);
    console.log("Total Busy Count:", totalBusyCount);
    console.log("Total No answer Count:", totalNoanswerCount);

    const totalCalls = totalCallsCount; // Assuming you have already calculated the totalCallsCount
    // Calculate success and failure percentages with two decimal places
    const successPercentage = ((totalSuccessCount / totalCalls) * 100).toFixed(2);
    const failurePercentage = ((totalFailureCount / totalCalls) * 100).toFixed(2);
    const busyPercentage = ((totalBusyCount / totalCalls) * 100).toFixed(2);
    const noanswerPercentage = ((totalNoanswerCount / totalCalls) * 100).toFixed(2);

    if (totalCallsCount !== 0 || totalSuccessCount !== 0 || totalFailureCount !== 0 || totalBusyCount != 0 || totalNoanswerCount != 0)
    {
        const ctxPie = document.getElementById('pieChart').getContext('2d');
const pieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: ['Success', 'Failure', 'Busy', 'No Answer'],
        datasets: [{
            label: 'Call Status',
            backgroundColor: [
                'rgba(0, 179, 60)', // Success (dark green)
                'rgba(255, 51, 51)', // Failure (dark red)
                'rgba(255, 214, 51)', // Busy (dark yellow)
                'rgba(140, 26, 255)', // No answer (dark purple)
            ],
            borderColor: [
                'rgba(0, 179, 60)', // Success (dark green)
                'rgba(255, 51, 51)', // Failure (dark red)
                'rgba(255, 214, 51)', // Busy (dark yellow)
                'rgba(140, 26, 255)', // No answer (dark purple)
            ],
            borderWidth: 1,
            data: [successPercentage, failurePercentage, busyPercentage, noanswerPercentage],
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            datalabels: {
                formatter: (value, ctxPie) => {
                    return value + '%';
                },
                color: '#fff',
                font: {
                    size: '14',
                    weight: 'bold'
                }
            }
        }
    },
});
    }
</script>  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js" integrity="sha512-vBmx0N/uQOXznm/Nbkp7h0P1RfLSj0HQrFSzV8m7rOGyj30fYAOKHYvCNez+yM8IrfnW0TCodDEjRqf6fodf/Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script> -->
    <script>
	// JavaScript for Chart.js
        const cdrData1 = @json($adminData);

        // Filter the cdrData1 array to include only elements where summary_report_status is 'Y'
        const filteredData = cdrData1.filter(data => data.summary_report_status === 'Y');
         console.log(filteredData);

          // Get the current date
        const currentDate = new Date();

// Filter the cdrData1 array to include only data for the last three days
const lastThreeDaysData = filteredData.filter(data => {
    const reportDate = new Date(data.report_date);
    // Calculate the difference in days between the current date and the report date
    const differenceInDays = Math.ceil((currentDate - reportDate) / (1000 * 60 * 60 * 24));
    // Return true if the difference is less than or equal to 3 (last three days)
    return differenceInDays <= 3;
});


        // Create an object to store aggregated data by date
        const aggregatedData = {};

        // Iterate over cdrData1 to aggregate data by date
        lastThreeDaysData.forEach(data => {
            const chartLabels = data.report_date;
            if (!aggregatedData[chartLabels]) {
                // Initialize aggregatedData[date] if it doesn't exist
                aggregatedData[chartLabels] = {
                    total: 0,
                    success: 0,
                    failure: 0,
                };
            }
            // Increment total count
            aggregatedData[chartLabels].total += parseInt(data.total_call);
            aggregatedData[chartLabels].success += parseInt(data.total_success);
            aggregatedData[chartLabels].failure += parseInt(data.total_failure) + parseInt(data.total_busy) + parseInt(data.total_no_answer);
        });
// Extract aggregated data into arrays for chart rendering
const dates = Object.keys(aggregatedData);
        console.log(dates)
        const totalData = dates.map(chartLabels => aggregatedData[chartLabels].total);
        console.log(totalData);
        const successData = dates.map(chartLabels => aggregatedData[chartLabels].success);
        console.log(successData);
        const failureData = dates.map(chartLabels => aggregatedData[chartLabels].failure);
        console.log(failureData);

        // const ctx = document.getElementById('barChart').getContext('2d');
        // const chart = new Chart(ctx, {

            const ctx = document.getElementById('barChart').getContext('2d');
        let chart; // Define chart variable outside to access it inside event listener

        function updateChart(yAxisScale) {
            console.log(yAxisScale);
            const maxDataValue = Math.max(...totalData); // Get maximum data value
            console.log(maxDataValue);
            const numTicks = Math.ceil(maxDataValue / yAxisScale); // Calculate number of ticks based on maxDataValue and yAxisScale
            console.log(numTicks);

            chart.options.scales.yAxes[0].ticks.min = 0;
            chart.options.scales.yAxes[0].ticks.max = yAxisScale * numTicks;
            chart.options.scales.yAxes[0].ticks.stepSize = yAxisScale;
            
            chart.update();
        }

        chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [{
                    label: "Total Calls",
                    backgroundColor: 'rgba(77, 121, 255)',
                    borderColor: 'rgba(77, 121, 255)',
                    borderWidth: 2,
                    data: totalData,
                    barPercentage: 0.9, // Adjust bar width relative to available space
                    categoryPercentage: 0.7, // Adjust category width relative to available space
                },
                {
                    label: "Success Calls",
                    backgroundColor: 'rgba(60, 179, 113)',
                    borderColor: 'rgba(60, 179, 113)',
                    borderWidth: 2,
                    data: successData,
                    barPercentage: 0.9, // Adjust bar width relative to available space
                    categoryPercentage: 0.5, // Adjust category width relative to available space
                },
                {
                    label: "Failure Calls(Busy + No Answer + Failed)",
                    backgroundColor: 'rgba(255, 0, 0)',
                    borderColor: 'rgba(255, 0, 0)',
                    borderWidth: 2,
                    data: failureData,
                    barPercentage: 0.9, // Adjust bar width relative to available space
                    categoryPercentage: 0.7, // Adjust category width relative to available space
                },
            ],
	},
    options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        min: 400000,
                    },
                    gridLines: {
                        drawBorder: true, // Display y-axis border line
                        drawOnChartArea: false, // Do not draw grid lines within the chart area
                    },
                }],
                xAxes: [{
                    gridLines: {
                        drawBorder: true, // Display x-axis border line
                        drawOnChartArea: false, // Do not draw grid lines within the chart area
                    },
                },],
            },
	},
    });

    updateChart(400000);

// Event listener for changing y-axis scale
document.getElementById('yAxisScale').addEventListener('change', function() {
 console.log("!!!");
     const yAxisScale = parseInt(this.value);
     console.log(yAxisScale);
     updateChart(yAxisScale);
 });


// Check if all data arrays are equal to 0
const isNoData = totalData.every(value => value === 0) &&
                 successData.every(value => value === 0) &&
                 failureData.every(value => value === 0);

    // If all data arrays are 0, display the message
    if (isNoData)
    {
     	const container = document.getElementById('barChart').parentNode;
        const message = document.createElement('p');
        message.textContent = "There is no data for the last one week.";
        message.style.textAlign = 'center';
        message.style.fontWeight = 'bold';
        message.style.marginTop = '20px'; // Add some top margin for spacing
        container.appendChild(message);
    }
   // Initialize variables to store total counts
    let totalCallsCount = 0;
    let totalSuccessCount = 0;
    let totalFailureCount = 0;
    let totalBusyCount = 0;
    let totalNoanswerCount = 0;

// Iterate over the cdrData1 array to accumulate counts
filteredData.forEach(data => {
        // Convert string values to numbers using parseInt() or parseFloat()
        const totalCall = parseInt(data.total_call);
        const totalSuccess = parseInt(data.total_success);
        const totalFailure = parseInt(data.total_failure);
        const totalBusy = parseInt(data.total_busy);
        const totalNoanswer = parseInt(data.total_no_answer);

        // Check if the conversion is successful
     	if (!isNaN(totalCall)) totalCallsCount += totalCall;
        if (!isNaN(totalSuccess)) totalSuccessCount += totalSuccess;
        if (!isNaN(totalFailure)) totalFailureCount += totalFailure;
        if (!isNaN(totalBusy)) totalBusyCount += totalBusy;
        if (!isNaN(totalNoanswer)) totalNoanswerCount += totalNoanswer;
    });

    console.log("Total Calls Count:", totalCallsCount);
    console.log("Total Success Count:", totalSuccessCount);
    console.log("Total Failure Count:", totalFailureCount);
    console.log("Total Busy Count:", totalBusyCount);
    console.log("Total No answer Count:", totalNoanswerCount);

    const totalCalls = totalCallsCount; // Assuming you have already calculated the totalCallsCount
    // Calculate success and failure percentages with two decimal places
    const successPercentage = ((totalSuccessCount / totalCalls) * 100).toFixed(2);
    const failurePercentage = ((totalFailureCount / totalCalls) * 100).toFixed(2);
    const busyPercentage = ((totalBusyCount / totalCalls) * 100).toFixed(2);
    const noanswerPercentage = ((totalNoanswerCount / totalCalls) * 100).toFixed(2);

    if (totalCallsCount !== 0 || totalSuccessCount !== 0 || totalFailureCount !== 0 || totalBusyCount != 0 || totalNoanswerCount != 0)
  {
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Success', 'Failure', 'Busy', 'No Answer'],
            datasets: [{
                label: 'Call Status',
                backgroundColor: [
                'rgba(0, 179, 60)', // Success (dark green)
                'rgba(255, 51, 51)',  // Failure (dark red)
                'rgba(255, 214, 51)', // Busy (dark yellow)
                'rgba(140, 26, 255)', // No answer (dark purple)
            ],
            borderColor: [
                'rgba(0, 179, 60)', // Success (dark green)
                'rgba(255, 51, 51)',  // Failure (dark red)
                'rgba(255, 214, 51)', // Busy (dark yellow)
                'rgba(140, 26, 255)', // No answer (dark purple)
            ],
              	borderWidth: 1,
                data: [successPercentage, failurePercentage, busyPercentage, noanswerPercentage],
            }],
        },
	options: {
	tooltips: {
      callbacks: {
        label: (tooltipItem, data) => {
        console.log(".........")
    console.log(data)
          var value = data.datasets[0].data[tooltipItem.index];
          var total = data.datasets[0].data.reduce((a, b) => a + b, 0);
          var pct = 100 / total * value;
          var pctRounded = Math.round(pct * 10) / 10;
          return data.labels[tooltipItem.index]+": "+value +'%';
        }
      }
    },
       responsive: true,
            maintainAspectRatio: false,
            legend: {
            position: 'bottom',
        },
	 plugins: {
            datalabels: {
                formatter: (value, ctxPie) => {
            const datapoints = ctx.chart.data.datasets[0].data
                const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                const percentage = value / total * 100
                return percentage.toFixed(2) + "%";
//                    return value + '%';
                },
                color: '#fff',
                font: {
                    size: '14',
                    weight: 'bold'
                }
            }
        }
        },
    });
  }
</script> 



@endif

<!-- End of 'content' section -->
@endsection

