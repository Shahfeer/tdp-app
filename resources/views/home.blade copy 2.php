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
   .font-bold {
    font-weight: 700;
}
.flex-auto{
    background:white;
}
.chartjs-render-monitor{
    margin-top: 150px;
}
.card-body{
    overflow: hidden !important;
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
    </div>
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
            <canvas id="barChart" width="400" height="200"></canvas>
<canvas id="drilldownChart" width="400" height="200" style="display: none;"></canvas>
 <button id="backButton" style="display: none;">Back to Main Chart</button>

            </div>
        </div>

        <div class="chart-container" style="flex: 1; background-color: #D1FAE5; border-radius: 8px; padding: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); max-width: 500px; ">
    <h3 class="font-semibold mb-2" style="color: #2F855A;">Campaign Distribution</h3>
    <canvas id="pieChart" width="600" height="600"></canvas>

</div>

    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js" integrity="sha512-vBmx0N/uQOXznm/Nbkp7h0P1RfLSj0HQrFSzV8m7rOGyj30fYAOKHYvCNez+yM8IrfnW0TCodDEjRqf6fodf/Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
    <script>
	// JavaScript for Chart.js
        const cdrData1 = @json($adminData);
        const cdrData2 = @json($adminDatas);
        
	    console.log(JSON.stringify(cdrData1) +"cdrData1")
        console.log(JSON.stringify(cdrData2) +"cdrData2")
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
        const dates = [];
        const totalData = [];
        const successData = [];
        const failureData = [];

        // Get the last three dates
        const lastThreeDates = Array.from({ length: 3 }, (_, i) => {
            const date = new Date();
            date.setDate(currentDate.getDate() - i);
            return date.toISOString().split('T')[0];
        }).reverse();

        // Populate data arrays with aggregated data
        lastThreeDates.forEach(date => {
            dates.push(date);
            if (aggregatedData[date]) {
                totalData.push(aggregatedData[date].total);
                successData.push(aggregatedData[date].success);
                failureData.push(aggregatedData[date].failure);
            } else {
                // If no data available for the date, push 0 to all data arrays
                totalData.push(0);
                successData.push(0);
                failureData.push(0);
            }
        });

        // const ctx = document.getElementById('barChart').getContext('2d');
        // const chart = new Chart(ctx, {

        const ctx = document.getElementById('barChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: dates, // use your dynamically generated dates
        datasets: [{
            label: 'Total Calls',
            data: totalData, // use your totalData array
            backgroundColor: 'rgba(77, 121, 255, 0.5)',
        }],
    },
    options: {
        responsive: true,
        onClick: function(evt) {
            const activePoints = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);
            if (activePoints.length) {
                const index = activePoints[0].index;
                const selectedLabel = this.data.labels[index];
                showDrilldownChart(selectedLabel); // Call function to show drilldown chart
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                    }
                }
            }
        }
    }
});

        


        // Function to update the dropdown menu
        function updateDropdown(selectedValue) {
            const yAxisScaleSelect = document.getElementById('yAxisScale');
            yAxisScaleSelect.innerHTML = ''; // Clear existing options

            const scales = [20000, 200000, 400000, 500000]; // Available scales
            scales.forEach(scale => {
                const option = document.createElement('option');
                option.value = scale;
                option.textContent = scale.toLocaleString(); // Format scale with commas
                if (scale === selectedValue) {
                    option.selected = true; // Select the determined scale
                }
                yAxisScaleSelect.appendChild(option);
            });
        }

        chart = new Chart(ctx, {
    type: 'bar',  // Change 'line' to 'bar' for column chart
    data: {
        labels: dates,
        datasets: [{
            label: "Total Calls",
            backgroundColor: 'rgba(77, 121, 255, 0.5)', // Bar fill color
            borderColor: 'rgba(77, 121, 255)', // Bar border color
            borderWidth: 2, // Bar border width
            data: totalData, // Data for Total Calls
        },
        {
            label: "Success Calls",
            backgroundColor: 'rgba(60, 179, 113, 0.5)', // Bar fill color for Success Calls
            borderColor: 'rgba(60, 179, 113)', // Bar border color for Success Calls
            borderWidth: 2, // Bar border width for Success Calls
            data: successData, // Data for Success Calls
        },
        {
            label: "Failure Calls (Busy + No Answer + Failed)",
            backgroundColor: 'rgba(255, 0, 0, 0.5)', // Bar fill color for Failure Calls
            borderColor: 'rgba(255, 0, 0)', // Bar border color for Failure Calls
            borderWidth: 2, // Bar border width for Failure Calls
            data: failureData, // Data for Failure Calls
        }],
    },
    options: {
        plugins: {
            datalabels: {
                formatter: function(value, context) {
                    return value === 0 ? '' : value;
                },
                anchor: 'end',
                align: 'top',
                color: 'black',
                font: {
                    weight: 'bold',
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true, // Ensure Y-axis starts from 0
                min: 400000, // Set minimum value for Y-axis
                grid: {
                    drawBorder: true, // Draw Y-axis border line
                    drawOnChartArea: false, // Do not draw grid lines in the chart area
                },
            },
            x: {
                categoryPercentage: 0.9, // Controls the space between categories
                barPercentage: 1.0, // Controls the width of the bars
                grid: {
                    drawBorder: true, // Draw X-axis border line
                    drawOnChartArea: false, // Do not draw grid lines in the chart area
                },
            },
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
//             const datapoints = ctx.chart.data.datasets[0].data
//                 const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
//                 const percentage = value / total * 100
//                 return percentage.toFixed(2) + "%";
		console.log(value)
		if(value != 0.00){ 
                   return value + '%';
                } 
		else{
			return "";
		}

		},
                 color: '#fff',
                 font: {
                     size: '11',
                     weight: 'bold'
                 },
	   rotation: (ctx) => {
		var percentage = ctx.dataset.data[ctx.dataIndex];
                return (percentage > 10) ? 0 : -90; 
            },
      //rotation: -90,
            offset: 15, // Adjust the offset to prevent overlapping
            anchor: 'end', // Align labels to the end of the slice
            align: (ctx) => {
                // Dynamically set alignment based on percentage
                let value = ctx.dataset.data[ctx.dataIndex];
                let sum = ctx.dataset.data.reduce((a, b) => a + b, 0);
                let percentage = (value / sum) * 100;
                return (percentage > 10) ? 'start' : 'start'; // Adjust threshold as needed
            },
             }
         }
        },
    });
  }
</script> 
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>

<script>
    // Assuming cdrData2 is defined here
    const filteredData2 = cdrData2.filter(data => data.summary_report_status === 'Y');

    // Initialize variables to store total counts for cdrData2
    let totalCallsCount2 = 0;
    let totalSuccessCount2 = 0;
    let totalFailureCount2 = 0;
    let totalBusyCount2 = 0;
    let totalNoanswerCount2 = 0;

    // Iterate over the filtered data to accumulate counts for cdrData2
    filteredData2.forEach(data => {
        totalCallsCount2 += parseInt(data.total_call) || 0;
        totalSuccessCount2 += parseInt(data.total_success) || 0;
        totalFailureCount2 += parseInt(data.total_failure) || 0;
        totalBusyCount2 += parseInt(data.total_busy) || 0;
        totalNoanswerCount2 += parseInt(data.total_no_answer) || 0;
    });

    // Calculate percentages
    const totalCalls2 = totalCallsCount2; 
    const successPercentage2 = totalCalls2 ? ((totalSuccessCount2 / totalCalls2) * 100).toFixed(2) : 0;
    const failurePercentage2 = totalCalls2 ? ((totalFailureCount2 / totalCalls2) * 100).toFixed(2) : 0;
    const busyPercentage2 = totalCalls2 ? ((totalBusyCount2 / totalCalls2) * 100).toFixed(2) : 0;
    const noanswerPercentage2 = totalCalls2 ? ((totalNoanswerCount2 / totalCalls2) * 100).toFixed(2) : 0;

    // Create 3D donut chart only if there's data
    if (totalCallsCount2 > 0) {
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'doughnut', // Keep type as doughnut
            data: {
                labels: ['Success', 'Failure', 'Busy', 'No Answer'],
                datasets: [
                    {
                        label: 'Campaign Call Status',
                        data: [successPercentage2, failurePercentage2, busyPercentage2, noanswerPercentage2],
                        backgroundColor: [
                            'rgba(255, 99, 132, 1)', // Darker Bright Pink (Success)
                            'rgba(54, 162, 235, 1)', // Darker Bright Blue (Failure)
                            'rgba(255, 206, 86, 1)', // Darker Bright Yellow (Busy)
                            'rgba(75, 192, 192, 1)', // Darker Bright Teal (No Answer)
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)', // Border color for Success
                            'rgba(54, 162, 235, 1)', // Border color for Failure
                            'rgba(255, 206, 86, 1)', // Border color for Busy
                            'rgba(75, 192, 192, 1)', // Border color for No Answer
                        ],
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true, // Keep this true to maintain aspect ratio
                cutout: '70%', // This determines the size of the donut hole
                plugins: {
                    doughnut3d: {
                        enabled: true,
                        depth: 30, // Set depth of the 3D effect
                        angle: 15, // Adjust the angle of the 3D effect
                    },
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 30, // Font size for legend
                            weight: 'bold' // Bold font
                        }
                    }
                }
            },
        });
    }
    // Function to transform adminData into drilldown format
// Function to transform adminData into drilldown format
function transformAdminData(data) {
    const drilldownData = {};

    data.forEach(entry => {
        const campaign = entry.campaign_name;

        // Create an entry for the campaign if it doesn't exist
        if (!drilldownData[campaign]) {
            drilldownData[campaign] = {
                total_calls: parseInt(entry.total_call),
                total_success: parseInt(entry.total_success),
                total_failure: parseInt(entry.total_failure),
                total_busy: parseInt(entry.total_busy),
                total_no_answer: parseInt(entry.total_no_answer)
            };
        } else {
            // If campaign already exists, sum the values
            drilldownData[campaign].total_calls += parseInt(entry.total_call);
            drilldownData[campaign].total_success += parseInt(entry.total_success);
            drilldownData[campaign].total_failure += parseInt(entry.total_failure);
            drilldownData[campaign].total_busy += parseInt(entry.total_busy);
            drilldownData[campaign].total_no_answer += parseInt(entry.total_no_answer);
        }
    });

    return drilldownData;
}

// Transform the adminData into drilldown format
// const drilldownData = transformAdminData(adminData);


// Transform the adminData into drilldown format
const drilldownData = transformAdminData(adminData);

function showDrilldownChart(selectedLabel) {
    // Retrieve the drilldown values for the selected campaign
    const values = drilldownData[selectedLabel] || { success: 0, failure: 0, busy: 0, no_answer: 0 };
    const drilldownValues = [
        values.success,
        values.failure,
        values.busy,
        values.no_answer
    ]; // Extract values for the drilldown chart

    const drilldownLabels = ['Success', 'Failure', 'Busy', 'No Answer']; // Subcategories

    // Hide the main chart and show the drilldown chart
    document.getElementById('barChart').style.display = 'none';
    document.getElementById('drilldownChart').style.display = 'block';

    const drilldownCtx = document.getElementById('drilldownChart').getContext('2d');

    // Create drilldown chart
    const drilldownChart = new Chart(drilldownCtx, {
        type: 'bar',
        data: {
            labels: drilldownLabels,
            datasets: [{
                label: selectedLabel + ' Breakdown',
                data: drilldownValues,
                backgroundColor: 'rgba(60, 179, 113, 0.5)',
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
}

</script>


@endif

<!-- End of 'content' section -->
@endsection

