<style>
.pb-3, .py-3 {
    padding-bottom: 0.5rem!important;
}
.pt-3, .py-3 {
    padding-top: 0.5rem!important;
}
.static-badge {
    display: inline-block;
    width: 30px; /* Set the width */
    height: 30px; /* Set the height */
    line-height: 30px; /* Center the text vertically */
    text-align: center; /* Center the text horizontally */
    border-radius: 50%; /* Make it round */
    background-color: whitesmoke; /* Set the background color */
    color: black; /* Set the text color */
}

</style>

<aside class="w-full md:w-64  bg-gray-800  md:min-h-screen" x-data="{ isOpen: false  }">
    <div class="flex items-center justify-between bg-white  p-4 h-16">
        <a href="{{ route('home') }}" class="flex items-center">
	    <img src="https://yourpostman.in/accounting_portal/public/css/celebmedia_logo.png" alt="logo" class="logo" style="width: 167px;height: 65px;">
        </a>    
        
    </div>
    <div class="px-2 py-6 md:block" :class="isOpen? 'block': 'hidden'" style="width: 255px;">
        <ul>

<!-- Home page redirect -->

            <li class="px-2 py-3 hover:bg-red-900 {{ Request()->is('home') ? 'in_active' : '' }} rounded-lg">
                <a href={{ route('home') }}  class="flex items-center">
                    <svg class="w-6 text-white  " fill="none" stroke-linecap="round"
                         stroke-linejoin="round"
                         stroke-width="2"   
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="mx-2 text-white text-xl font-bold ">Dashboard</span>
                </a>    
            </li>


<!-- GSM Board List page redirect -->

<?php
use Illuminate\Support\Facades\DB;

$Ivr_Data = DB::table('prompt_masters')
->where('prompt_status', 'N')
->count();

$Camp_Data = DB::table('calls')
->where('call_status', 'C')
->count();

?>

@if (Auth::user()->user_master_id == 1)
                <!-- Conditionally add the "Credit Management" link for admin -->
                <li class="px-2 py-3 hover:bg-red-900 {{ request()->is('gsm_board') ? 'in_active' : '' }} rounded mt-2">
                    <a href="{{ route('gsm_board') }}" class="flex items-center">
                    <svg class="h-8 w-8 text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <rect x="8" y="8" width="8" height="8" rx="1" />  <line x1="3" y1="8" x2="4" y2="8" />  <line x1="3" y1="16" x2="4" y2="16" />  <line x1="8" y1="3" x2="8" y2="4" />  <line x1="16" y1="3" x2="16" y2="4" />  <line x1="20" y1="8" x2="21" y2="8" />  <line x1="20" y1="16" x2="21" y2="16" />  <line x1="8" y1="20" x2="8" y2="21" />  <line x1="16" y1="20" x2="16" y2="21" /></svg>
                        <span class="mx-2 text-white text-xl font-bold">GSM Board</span>
                    </a>
                </li>
            @endif


<!-- Credit Management page redirect -->

	    @if (Auth::user()->user_master_id == 1)
                <!-- Conditionally add the "Credit Management" link for admin -->
                <li class="px-2 py-3 hover:bg-red-900 {{ request()->is('credit_management') ? 'in_active' : '' }} rounded mt-2">
                    <a href="{{ route('credit_management') }}" class="flex items-center">
                        <svg class="w-6 text-white" fill="none" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                            stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="mx-2 text-white text-xl font-bold">Credit Management</span>
                    </a>
                </li>
            @endif




<!-- Context create page redirect -->

            <li class="px-2 py-3 hover:bg-red-900 {{ request()->is('context_create') ? 'in_active' : '' }} rounded mt-2">
                <a href="{{ route('context_create') }}" class="flex items-center">
		<svg class="h-8 w-8 text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M14 3v4a1 1 0 0 0 1 1h4" />  
			<path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />  <circle cx="11" cy="16" r="1" />  <polyline points="12 16 12 11 14 12" /></svg>
                    <span class="mx-2 text-white text-xl font-bold">Create Prompt</span>
                </a>

            </li> 


<!-- Context List page redirect -->

            <li class="px-2 py-3 hover:bg-red-900 {{ request()->is('context_list') ? 'in_active' : '' }} rounded mt-2">
                <a href="{{ route('context_list') }}" class="flex items-center">
			<svg class="h-8 w-8 text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> 
				 <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M3.5 5.5l1.5 1.5l2.5 -2.5" />  <path d="M3.5 11.5l1.5 1.5l2.5 -2.5" /> 
				 <path d="M3.5 17.5l1.5 1.5l2.5 -2.5" />  <line x1="11" y1="6" x2="20" y2="6" />  <line x1="11" y1="12" x2="20" y2="12" />  <line x1="11" y1="18" x2="20" y2="18" /></svg>
                    <span class="mx-2 text-white text-xl font-bold">Prompt List</span>
                </a>

            </li>


            @if (Auth::user()->user_master_id == 1)
                <!-- Conditionally add the "Credit Management" link for admin -->
                <li class="px-2 py-3 hover:bg-red-900 {{ request()->is('ivr_approve') ? 'in_active' : '' }} rounded mt-2">
                    <a href="{{ route('ivr_approve') }}" class="flex items-center">
                    <svg class="h-8 w-8 text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> 
				 <path stroke="none" d="M0 0h24v24H0z"/>  <circle cx="12" cy="12" r="9" /> 
			 <path d="M9 12l2 2l4 -4" /></svg>
                        <span class="mx-2 text-white text-xl font-bold">Ivr Approval</span>
                        @if($Ivr_Data != '')
                        <span class="static-badge" style="margin-left: 55px;">
                        {{ $Ivr_Data }}
                        </span>
                        @endif
                    </a>
                </li>
            @endif


<!-- Create campaign page redirect -->

	    <li class="px-2 py-3 hover:bg-red-900 {{ request()->is('createcampaign') ? 'in_active' : '' }} rounded mt-2">
                <a href="{{ route('createcampaign') }}" class="flex items-center">
		<svg class="h-8 w-8 text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
		  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />  <path d="M15 6h6m-3 -3v6" />
		</svg>
                    <span class="mx-2 text-white text-xl font-bold">Create Campaign</span>
                    
                </a>

            </li>



<!-- Campaign Approve page redirect -->

            @if (Auth::user()->user_master_id == 1)
                <!-- Conditionally add the "Credit Management" link for admin -->
                <li class="px-2 py-3 hover:bg-red-900 {{ request()->is('approve_campaign') ? 'in_active' : '' }} rounded mt-2">
                    <a href="{{ route('approve_campaign') }}" class="flex items-center">
			<svg class="h-8 w-8 text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> 
				 <path stroke="none" d="M0 0h24v24H0z"/>  <circle cx="12" cy="12" r="9" /> 
			 <path d="M9 12l2 2l4 -4" /></svg>
                        <span class="mx-2 text-white text-xl font-bold">Approve Campaign</span>
                        @if($Camp_Data != '')
                        <span class="static-badge">
                             {{ $Camp_Data }}
                        </span>
                        @endif
                    </a>
                </li>
            @endif



<!-- Campaign List page redirect -->

	    <li class="px-2 py-3 hover:bg-red-900 {{ request()->is('campaign_list') ? 'in_active' : '' }} rounded mt-2">
                <a href="{{ route('campaign_list') }}" class="flex items-center">
                    <svg class="w-6 text-white" fill="none" stroke-linecap="round"
                         stroke-linejoin="round"
                         stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span class="mx-2 text-white text-xl font-bold">Campaign List</span>
                </a>

            </li> 


<!-- CDR generation page redirect -->

            @if (Auth::user()->user_master_id == 1)
                <!-- Conditionally add the "Credit Management" link for admin -->
                <li class="px-2 py-3 hover:bg-red-900 {{ request()->is('cdr_generation') ? 'in_active' : '' }} rounded mt-2">
                    <a href="{{ route('cdr_generation') }}" class="flex items-center">
                        <svg class="h-8 w-8 text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z"/>  <circle cx="12" cy="12" r="9" />
                         <path d="M9 12l2 2l4 -4" /></svg>
                        <span class="mx-2 text-white text-xl font-bold">CDR Generation</span>
                    </a>
                </li>
            @endif

            

<!-- Summary Report page redirect -->

            <li class="px-2 py-3 hover:bg-red-900 {{ request()->is('summaryreport') ? 'in_active' : '' }} rounded mt-2">
                <a href="{{ route('summaryreport') }}" class="flex items-center">
                    <svg class="w-6 text-white" fill="none" stroke-linecap="round"
                         stroke-linejoin="round"
                         stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                       <!-- <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path> -->
			<path stroke="none" d="M0 0h24v24H0z"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><line x1="10" y1="16" x2="14" y2="16" />
                    </svg>
                   <div class="mx-2 text-white text-xl font-bold">Call Holding Time Report</div> 
                </a>

            </li>     


<!-- Summary Report page redirect -->

            <li class="px-2 py-3 hover:bg-red-900 {{ request()->is('summary_report') ? 'in_active' : '' }} rounded mt-2">
                <a href="{{ route('summary_report') }}" class="flex items-center">
                    <svg class="w-6 text-white" fill="none" stroke-linecap="round"
                         stroke-linejoin="round"
                         stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                       <!-- <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path> -->
                        <path stroke="none" d="M0 0h24v24H0z"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><line x1="10" y1="16" x2="14" y2="16" />

                    </svg>
                   <span class="mx-2 text-white text-xl font-bold">Summary Report</span>
                </a>

            </li>
	  

<!-- Detail Report page redirect -->

            <li class="px-2 py-3 hover:bg-red-900 {{ request()->is('detailreport') ? 'in_active' : '' }} rounded mt-2">
                <a href="{{ route('detailreport') }}" class="flex items-center">
                    <svg class="w-6 text-white" fill="none" stroke-linecap="round"
                         stroke-linejoin="round"
                         stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                       <!--  <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path> -->
				<path stroke="none" d="M0 0h24v24H0z"/><path d="M15 21h-9a3 3 0 0 1 -3 -3v-1h10v2a2 2 0 0 0 4 0v-14a2 2 0 1 1 2 2h-2m2 -4h-11a3 3 0 0 0 -3 3v11" /><line x1="9" y1="7" x2="13" y2="7" /><line x1="9" y1="11" x2="13" y2="11" />
                    </svg>
                    <span class="mx-2 text-white text-xl font-bold">Detail Report</span> 
                </a>

            </li>  
            
        </ul>
</div>
        
        
</aside>
