<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')
 <style>
    .shadow-lg {
        box-shadow: 10px 10px rgba(0, 0, 0, .175) !important;
    }
    .image-container {
        overflow: hidden; /* Hide any content that overflows the container */
    }
    .image-container img {
        max-width: 100%; /* Ensure the image doesn't exceed the container's width */
        height: auto; /* Maintain aspect ratio */
    }

    .content-container {
        background-color: #FFF; /* Set your desired background color */
        padding: 10px; /* Add padding to the content area */
    }

.faq-section {
background: none;
/* min-height: 100vh; */
padding: 0 0 0;
}
.faq-title h2 {
position: relative;
margin-bottom:20px;
display: inline-block;
font-weight: 600;
line-height: 1;
}
.faq-title h2::before {
content: "";
position: absolute;
left: 50%;
width: 60px;
height: 2px;
background: #E91E63;
bottom: -15px;
margin-left: -30px;
}
.faq-title p {
padding: 0 190px;
margin-bottom: 10px;
}

.faq {
background: #FFFFFF;
box-shadow: 0 2px 48px 0 rgba(0, 0, 0, 0.06);
border-radius: 4px;
}

.faq .card {
border: none;
background: none;
border-bottom: 1px dashed #CEE1F8;
}

.faq .card .card-header {
padding: 0px;
border: none;
background: none;
-webkit-transition: all 0.3s ease 0s;
-moz-transition: all 0.3s ease 0s;
-o-transition: all 0.3s ease 0s;
transition: all 0.3s ease 0s;
}

.faq .card .card-header:hover {
background: rgba(233, 30, 99, 0.1);
padding-left: 10px;
}
.faq .card .card-header .faq-title {
width: 100%;
text-align: left;
padding: 0px;
padding-left: 30px;
padding-right: 30px;
font-weight: 400;
font-size: 15px;
letter-spacing: 1px;
color: #3B566E;
text-decoration: none !important;
-webkit-transition: all 0.3s ease 0s;
-moz-transition: all 0.3s ease 0s;
-o-transition: all 0.3s ease 0s;
transition: all 0.3s ease 0s;
cursor: pointer;
padding-top: 15px;
padding-bottom: 5px;
}

.faq .card .card-header .faq-title .badge {
display: inline-block;
width: 20px;
height: 20px;
line-height: 14px;
float: left;
-webkit-border-radius: 100px;
-moz-border-radius: 100px;
border-radius: 100px;
text-align: center;
background: #E91E63;
color: #fff;
font-size: 12px;
margin-right: 20px;
}

.faq .card .card-body {
padding: 30px;
padding-left: 35px;
padding-bottom: 16px;
font-weight: 400;
font-size: 16px;
color: #6F8BA4;
line-height: 28px;
letter-spacing: 1px;
border-top: 1px solid #F3F8FF;
}

.faq .card .card-body p {
margin-bottom: 14px;
}

@media (max-width: 991px) {
.faq {
margin-bottom: 30px;
}
.faq .card .card-header .faq-title {
line-height: 26px;
margin-top: 10px;
}
}
</style>

<div class="content-container bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col md:flex-row" style="min-height: 700px; height: auto;">

    <div class="content-container px-8 md:w-full" style="background-color: #fff;"> 
        <h2 class="text-2xl font-medium" style="font-weight: bold; margin-bottom:0px; text-align: center;"><img src="https://yourpostman.in/accounting_portal/public/css/celebmedia_logo.png" alt="logo" class="logo" style="width: 300px; text-align: center;"></h2>

        <!-- <p class="text-lg font-normal text-gray-800" style="text-align: justify; color: #000;"></p> -->
	<h2 style="text-align:center;  margin-bottom:0px;"><b>"Celeb Media- Your Infinity Partner"</b></h2>
           <b> About Outbound Services</b><br>
	#Inbound #Outbound #CTASMS #APIintegration #Callpatch #Thirdparty #CC #Customercare #Agents #Telecallingteam #Call conferencing #NumberMasking #tollfree #Reports #Analytics #Campaign #CLM #Customerlifecyclemanagement #BulkOBD
	<br><br>

Our robust Interactive Voice Recognition (IVR) cloud service supports Inbound & Out bound call features with call conferencing to third party or agent routing facility supported with Call to action (CTA) SMS. Also comes with advanced features like number masking, Call recording feature help businesses to integrate & launch the campaign in simple 5 Steps. We support customization, white labeling of portal with inbuilt real time detailed analytics, customer life management & Reports. Further supports access to toll free, mobile, landline virtual nos for voice & Messaging solutions.<br><br>

<section class="faq-section">
  <div class="container">
    <div class="row">
      <!-- ***** FAQ Start ***** -->
      <div class="col-md-12">

        <div class="faq-title text-center pb-3">
          <h2>FAQ</h2>
        </div>
      </div>
      <div class="col-md-12">
        <div class="faq" id="accordion">
          <div class="card">
            <div class="card-header" id="faqHeading-1">
              <div class="mb-0">
                <h5 class="faq-title" data-toggle="collapse" data-target="#faqCollapse-1" data-aria-expanded="true"
                  data-aria-controls="faqCollapse-1">
                  <span class="badge">1</span> Can we run the voice call campaigns from our end?
                </h5>
              </div>
            </div>
            <div id="faqCollapse-1" class="collapse" aria-labelledby="faqHeading-1" data-parent="#accordion">
              <div class="card-body">
                <p>Yes, you can run the campaign. To begin with raise a request for user creation & load credits</p>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="faqHeading-2">
              <div class="mb-0">
                <h5 class="faq-title" data-toggle="collapse" data-target="#faqCollapse-2" data-aria-expanded="false"
                  data-aria-controls="faqCollapse-2">
                  <span class="badge">2</span> What are the pre-requisites to run the voice call campaigns?
                </h5>
              </div>
            </div>
            <div id="faqCollapse-2" class="collapse" aria-labelledby="faqHeading-2" data-parent="#accordion">
              <div class="card-body">
                <p>1.	Select create prompt from menu> upload the voice prompt for approval. Also fill all required details & submit the prompt creation.<br>
                  2.	Once prompt is approved, it will be visible in Prompt list menu.</p>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="faqHeading-3">
              <div class="mb-0">
                <h5 class="faq-title" data-toggle="collapse" data-target="#faqCollapse-3" data-aria-expanded="false"
                  data-aria-controls="faqCollapse-3">
                  <span class="badge">3</span> What is the size of the prompt? Any specific format needs to be uploaded for approval?
                </h5>
              </div>
            </div>
            <div id="faqCollapse-3" class="collapse" aria-labelledby="faqHeading-3" data-parent="#accordion">
              <div class="card-body">
                <p>1.	Yes, you are requested to upload the voice file in below format.<br>
                  2.	The prompt duration should not exceed more than 27 Secs which called 1 pulse.<br>
                  3.	After every 30 Secs consumed will be considered as subsequent pulse.</p>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="faqHeading-4">
              <div class="mb-0">
                <h5 class="faq-title" data-toggle="collapse" data-target="#faqCollapse-4" data-aria-expanded="false"
                  data-aria-controls="faqCollapse-4">
                  <span class="badge">4</span> How will I be charged per call?
                </h5>
              </div>
            </div>
            <div id="faqCollapse-4" class="collapse" aria-labelledby="faqHeading-4" data-parent="#accordion">
              <div class="card-body">
                <p>You will be charged for every successful call which is answered by the customers.<br>
                  For commercials, pls reach our sales team</p>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="faqHeading-5">
              <div class="mb-0">
                <h5 class="faq-title" data-toggle="collapse" data-target="#faqCollapse-5" data-aria-expanded="false"
                  data-aria-controls="faqCollapse-5">
                  <span class="badge">5</span> How unanswered calls will be dialled?
                </h5>
              </div>
            </div>
            <div id="faqCollapse-5" class="collapse" aria-labelledby="faqHeading-5" data-parent="#accordion">
              <div class="card-body">
                <p>We actual have a auto mechanism of retrying customers with different retries. <br>
                  You can set timer (Frequency/ range of time) & no of retry attempts (Retry calls to attempt)                  
                </p>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="faqHeading-6">
              <div class="mb-0">
                <h5 class="faq-title" data-toggle="collapse" data-target="#faqCollapse-6" data-aria-expanded="false"
                  data-aria-controls="faqCollapse-6">
                  <span class="badge">6</span> Can I run survey calls or opinion calls from this portal?
                </h5>
              </div>
            </div>
            <div id="faqCollapse-6" class="collapse" aria-labelledby="faqHeading-6" data-parent="#accordion">
              <div class="card-body">
                <p>No, we cannot run Survey calls from the given portal.<br> 
                  We use a different portal for survey or opinion polls.</p>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="faqHeading-7">
              <div class="mb-0">
                <h5 class="faq-title" data-toggle="collapse" data-target="#faqCollapse-7" data-aria-expanded="false"
                  data-aria-controls="faqCollapse-7">
                  <span class="badge">7</span> What type of reports & insights do you provide?
                </h5>
              </div>
            </div>
            <div id="faqCollapse-7" class="collapse" aria-labelledby="faqHeading-7" data-parent="#accordion">
              <div class="card-body">
                <p>Once the campaign is loaded, Summary reports will be updated by EOD. All mobile wise call detailed report & call holding report will be updated next day by 12PM.<br></p>
                <p>
                  1.	Call holding time report- Helps you to analysis the how much the caller listened to the call.<br>
                  2.	Summary report- Helps you to analysis the campaign success ratio or Failure ratio.<br>
                  3.	Call Detailed report- Helps you to analysis the mobile no wise call report
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Can we run the voice call campaigns from our end?<br>
Yes, you can run the campaign. To begin with raise a request for user creation & load credits<br><br>

What are the pre-requisites to run the voice call campaigns?<br>
1.	Select create prompt from menu> upload the voice prompt for approval. Also fill all required details & submit the prompt creation.<br>
2.	Once prompt is approved, it will be visible in Prompt list menu.<br><br>

What is the size of the prompt? Any specific format needs to be uploaded for approval?<br>
1.	Yes, you are requested to upload the voice file in below format.<br>
2.	The prompt duration should not exceed more than 27 Secs which called 1 pulse. <br>
3.	After every 30 Secs consumed will be considered as subsequent pulse.<br><br>

How will I be charged per call?<br>
You will be charged for every successful call which is answered by the customers.<br>
For commercials, pls reach our sales team<br><br>

How unanswered calls will be dialled?<br>
We actual have a auto mechanism of retrying customers with different retries. <br>
You can set timer (Frequency/ range of time) & no of retry attempts (Retry calls to attempt)<br><br>

Can I run survey calls or opinion calls from this portal?<br>
No, we cannot run Survey calls from the given portal. <br>
We use a different portal for survey or opinion polls.<br><br>

What type of reports & insights do you provide?<br>
Once the campaign is loaded, Summary reports will be updated by EOD. All mobile wise call detailed report & call holding report will be updated next day by 12PM.<br><br>

<ol>
<li>1.	Call holding time report- Helps you to analysis the how much the caller listened to the call.</li>
<li>2.	Summary report- Helps you to analysis the campaign success ratio or Failure ratio.</li>
<li>3.	Call Detailed report- Helps you to analysis the mobile no wise call report.</li></ol> -->
        </p>
    </div> 

<!--	 <div class="px-3 md:w-1/2">
        <img src="resources/image/icons/tdp_2.jpeg" alt="TDP Image">
    </div>  -->
</div>
<!-- End of 'content' section -->

@endsection

