<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')
<style>
    .shadow-lg {
        box-shadow: 10px 10px rgba(0, 0, 0, .175) !important;
    }
   .card-header{
    width:300%;
   }
   /* .font-bold {
    font-weight: 700;
} */
.flex-auto{
    background:white;
}
.chartjs-render-monitor{
    margin-top: 150px;
}
.card-body{
    overflow: hidden !important;
}
#barCharts {
            display: none; /* Hide the bar chart initially */
            width: 100%; 
            height: 400px; 
        }
        #pieChart {
            width: 100%; 
            height: 400px; 
        }

        #barChart {
    min-width: 310px;
    height: 400px;
    margin: 0 auto;
}
h2,h3{
    font-family: 'Nunito', 'Segoe UI', sans-serif !important;
}
.text.highcharts-title{
    font-family: 'Nunito', 'Segoe UI', sans-serif !important;
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

<!-- <div class="mx-auto w-full">

@if(Auth::user()->user_master_id != 1)
@php $displayContent = false; @endphp
    @foreach($adminData as $admin)
        @if(date('Y-m-d', strtotime($admin->call_entry_time)) == now()->toDateString())
            @php $displayContent = true; @endphp
            @break
        @endif
    @endforeach

@php
    $campaignCount = 0; // Initialize the variable before the loop
@endphp

    @if($displayContent)
    <div>
        <!-- Card starts -->
        <div class="card" style="background-color: #fff; margin-top: 0.2%;overflow:hidden !important;">
    <div class="px-3" style="background-color: #FFF; height: 30px; padding-top: 8px; margin-bottom: 10px; ">
        <h2 class="text-2xl font-bold" style="font-weight: 500; background-color: #fff;text-align:center !important;">Today's Campaign Summary</h2>
    </div>
    
    <!-- Small Div for Additional Information -->
    <div style="background-color: #f0f8ff; border-radius: 5px; padding: 10px; margin-top: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <p style="margin: 0; font-size: 14px; color: #333; text-align:center !important;">This is a brief summary of today's campaign performance. It includes key metrics and highlights.</p>
  


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
@php
    $campaignCount = $campaignCount+1; // Initialize the variable before the loop
@endphp
                    <div class="flex-auto p-4">
                        <div class="flex flex-wrap" style = "height: 100px;">
                            <div class="relative w-full">
                                <h5 class="text-black uppercase font-bold text-xl" >
                                Campaign - {{ $campaignCount }}
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
     <div class ="px-8 py-6">
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
</div>
</div>
</div>
</div>

    @endif


    <!-- Bar Chart -->
    @if(Auth::user()->user_master_id != 1)
    

    <div class="card" style="margin-top: 20px; background-color: #f9fafb; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);overflow: hidden;">
    <div class="card-header" style="padding: 20px; background-color: #4A90E2; border-bottom: 1px solid #e5e7eb; border-radius: 12px 12px 0 0;">
        <h2 class="text-3xl font-bold" style="margin: 0; color: #fff;">Campaign Overview</h2>
    </div>

    <div class="card-body" style="padding: 20px; display: flex; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
        
        <!-- Last 3 Days Campaign -->
        <div class="campaign-card" style="flex: 1; background: linear-gradient(135deg, #FFA07A, #FF6347); border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); padding: 20px; min-width: 300px; color: #fff;">
            <h3 class="text-xl font-semibold" style="margin-bottom: 10px;">Last 3 Days Campaign</h3>
            <!-- Content for last 3 days campaign goes here -->
            <p style="color: #fff;">Data for the last 3 days will be displayed here...</p>
        </div>

        <!-- Last One Week Campaign -->
        <div class="campaign-card" style="flex: 1; background: linear-gradient(135deg, #87CEFA, #4682B4); border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); padding: 20px; min-width: 300px; color: #fff;">
            <h3 class="text-xl font-semibold" style="margin-bottom: 10px; text-align: center;">Last One Week's Campaign</h3>
            <!-- Content for last one week's campaign goes here -->
            <p style="color: #fff; text-align: center;">Data for the last one week will be displayed here...</p>
        </div>
    </div>

    <div class="card-body" style="padding: 20px; display: flex; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
        
        <!-- Bar Chart -->
        <div class="chart-container" style="flex: 1; background-color: #FFDDC1; border-radius: 8px; padding: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); min-width: 400px;">
            <h3 class="font-semibold mb-2" style="color: #C65D3D;">Total Calls</h3>
            <div style="margin-top: 20px;">
            <div id="barChart" width="400" height="200"></div>
            <div id="timeDetailsContainer"></div>
            </div>
        </div>

        <div class="chart-container" style="flex: 1; background-color: #D1FAE5; border-radius: 8px; padding: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); max-width: 500px; ">
    <h3 class="font-semibold mb-2" style="color: #2F855A;">Campaign Distribution</h3>
    <canvas id="pieChart" width="600" height="600"></canvas>
    <canvas id="barCharts" style="height: 400px; width: 100%; display: none;"></canvas>
 

        </div>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js" integrity="sha512-vBmx0N/uQOXznm/Nbkp7h0P1RfLSj0HQrFSzV8m7rOGyj30fYAOKHYvCNez+yM8IrfnW0TCodDEjRqf6fodf/Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<!-- // JavaScript for Chart.js -->
<script>
const adminData = @json($adminData);
const adminDatas = @json($adminDatas);


// Filter the adminData array to include only elements where summary_report_status is 'Y'
const filteredData = adminData.filter(data => data.summary_report_status === 'Y');
const currentDate = new Date();
console.log(JSON.stringify(filteredData) + ' filteredData');

// Get the last three days data
const lastThreeDaysData = filteredData.filter(data => {
    const reportDate = new Date(data.report_date);
    const differenceInDays = Math.ceil((currentDate - reportDate) / (1000 * 60 * 60 * 24));
    return differenceInDays <= 3;
});

// Create an object to store aggregated data by date
const aggregatedData = {};

// Aggregate data by date
lastThreeDaysData.forEach(data => {
    const chartLabels = data.report_date;
    if (!aggregatedData[chartLabels]) {
        aggregatedData[chartLabels] = {
            total: 0,
            drilldownData: [], // Array for detailed drilldown data
        };
    }
    aggregatedData[chartLabels].total += parseInt(data.total_call);

    // Push detailed data for drilldown
    aggregatedData[chartLabels].drilldownData.push({
        name: 'Success',
        y: parseInt(data.total_success),
        drilldown: `${chartLabels}-success` // Unique id for drilldown
    });
    aggregatedData[chartLabels].drilldownData.push({
        name: 'Busy',
        y: parseInt(data.total_busy),
        drilldown: `${chartLabels}-busy` // Unique id for drilldown
    });
    aggregatedData[chartLabels].drilldownData.push({
        name: 'No Answer',
        y: parseInt(data.total_no_answer),
        drilldown: `${chartLabels}-no-answer` // Unique id for drilldown
    });
});

// Extract aggregated data into arrays for chart rendering
const dates = Object.keys(aggregatedData);
const totalData = dates.map(date => aggregatedData[date].total);
console.log(filteredData + ' filteredDataaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');

// Prepare drilldown series
const drilldownSeries = [];

// Create drilldown series for each date
dates.forEach(date => {
    drilldownSeries.push({
        name: date,
        id: date,
        data: aggregatedData[date].drilldownData // Use the detailed data
    });

    // Create detailed drilldown for success, busy, and no answer
    drilldownSeries.push({
        name: `${date} - Success`,
        id: `${date}-success`,
        data: [[`Successful Calls`, aggregatedData[date].drilldownData.find(item => item.name === 'Success').y]]
    });
    drilldownSeries.push({
        name: `${date} - Busy`,
        id: `${date}-busy`,
        data: [[`Busy Calls`, aggregatedData[date].drilldownData.find(item => item.name === 'Busy').y]]
    });
    drilldownSeries.push({
        name: `${date} - No Answer`,
        id: `${date}-no-answer`,
        data: [[`No Answer Calls`, aggregatedData[date].drilldownData.find(item => item.name === 'No Answer').y]]
    });
});

// Create the chart
Highcharts.chart('barChart', {
    chart: {
        type: 'column',
        events: {
            drilldown: function (e) {
                if (!e.seriesOptions) {
                    const chart = this;

                    // Show the loading label
                    chart.showLoading('Simulating Ajax ...');

                    setTimeout(function () {
                        chart.hideLoading();
                        const drilldownData = drilldownSeries.find(series => series.id === e.point.drilldown);
                        if (drilldownData) {
                            chart.addSeriesAsDrilldown(e.point, drilldownData);
                        }
                    }, 1000);
                }
            }
        }
    },
    title: {
        text: 'Summary Details'
    },
    xAxis: {
        type: 'category'
    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true
            }
        }
    },
    series: [{
        name: 'Total Calls',
        colorByPoint: true,
        data: dates.map(date => ({
            name: date,
            y: aggregatedData[date].total,
            drilldown: date // Set drilldown id to the date
        }))
    }],
    drilldown: {
        series: drilldownSeries // Add the drilldown series here
    }
});


// Function to load detailed breakdown (success, busy, no answer)
function loadDetailedBreakdown(selectedDate) {
    // Filter adminDatas based on the selectedDate
    const detailedData = adminDatas.filter(data => {
        const reportDate = new Date(data.call_entry_time);
        return reportDate.toDateString() === new Date(selectedDate).toDateString();
    }); 
console.log('DetailedData'+detailedData)
    if (detailedData.length > 0) {
        const breakdownData = {
            success: 0,
            busy: 0,
            noAnswer: 0
        };

        detailedData.forEach(data2 => {
            if (data2.call_status === 'O') { // Success
                breakdownData.success += data2.no_of_mobile_numbers;
            } else if (data2.call_status === 'C') { // Busy
                breakdownData.busy += data2.no_of_mobile_numbers;
            } else { // No Answer
                breakdownData.noAnswer += data2.no_of_mobile_numbers;
            }
        });

        // Display breakdown chart
        displayBreakdownChart(breakdownData, selectedDate);
    } else {
        console.log("No detailed data available for this date.");
    }
}

// Function to display breakdown chart
function displayBreakdownChart(breakdownData, date) {
    // Clear the previous chart if it exists
    const existingChart = Chart.getChart("barChart");
    if (existingChart) {
        existingChart.destroy();
    }

    const ctxBreakdown = document.getElementById('barChart').getContext('2d');
    const breakdownChart = new Chart(ctxBreakdown, {
        type: 'bar',
        data: {
            labels: ['Success', 'Busy', 'No Answer'],
            datasets: [{
                label: `Call Breakdown on ${date}`,
                data: [breakdownData.success, breakdownData.busy, breakdownData.noAnswer],
                backgroundColor: [
                    'rgba(0, 179, 60, 0.5)', // Success
                    'rgba(255, 214, 51, 0.5)', // Busy
                    'rgba(255, 51, 51, 0.5)', // No Answer
                ],
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                },
            }
        }
    });
}


    // Pie chart logic remains the same
    let totalCallsCount = 0;
    let totalSuccessCount = 0;
    let totalFailureCount = 0;
    let totalBusyCount = 0;
    let totalNoanswerCount = 0;

    filteredData.forEach(data => {
        totalCallsCount += parseInt(data.total_call);
        totalSuccessCount += parseInt(data.total_success);
        totalFailureCount += parseInt(data.total_failure);
        totalBusyCount += parseInt(data.total_busy);
        totalNoanswerCount += parseInt(data.total_no_answer);
    });

    const totalCalls = totalCallsCount;
    const successPercentage = ((totalSuccessCount / totalCalls) * 100).toFixed(2);
    const failurePercentage = ((totalFailureCount / totalCalls) * 100).toFixed(2);
    const busyPercentage = ((totalBusyCount / totalCalls) * 100).toFixed(2);
    const noanswerPercentage = ((totalNoanswerCount / totalCalls) * 100).toFixed(2);

    if (totalCallsCount !== 0) {
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Success', 'Failure', 'Busy', 'No Answer'],
                datasets: [{
                    label: 'Call Status',
                    data: [totalSuccessCount, totalFailureCount, totalBusyCount, totalNoanswerCount],
                    backgroundColor: [
                        'rgba(0, 179, 60, 0.5)',
                        'rgba(255, 51, 51, 0.5)',
                        'rgba(255, 214, 51, 0.5)',
                        'rgba(77, 121, 255, 0.5)',
                    ],
                }],
            },
            options: {
                responsive: true,
            },
        });
    } else {
        console.log("Total calls count is zero. Pie chart not displayed.");
    }
</script>

</div>

@endif

<!-- End of 'content' section -->
@endsection

