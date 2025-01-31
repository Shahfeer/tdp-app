<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')
<div id='loader' style="display: none;"></div>


<style>
/* Styling for confirmation dialogue yes/no buttons */ 
    .button 
    {
        border: none;
        color: white;
        padding: 30px 40px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
    }

    .red-text 
    {
        color: red;
    }

    .serverId 
    {
        color: blue;
    }

    .channelCount 
    {
        color: red;
    }

    .button1 
    {
        background-color: green;
        color: white;
        width: 70px;
        height: 40px;
    }

    .button2 
    {
        background-color: green;
        color: white;
        width: 70px;
        height: 40px;
    }


    .button3 
    {
        background-color: red;
        color: white;
        width: 70px;
        height: 40px;
    }


    .modal-header
    {
        background-color: #5cb85c;
        color: white;
        text-align: center; /* Center-align text */
        padding: 10px 30px;

    }

    .modal-footer 
    {
        padding: 8px; /* Reduce the padding inside the footer */
        margin: 10px 0; /* Reduce the margin above and below the footer */
        display: flex; justify-content: flex-end;
    }

    .modal-title 
    {
        font-weight: bold; /* Make the title text bold */
        text-align: center;
    }

    .close 
    {
        color: white;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus 
    {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    .button4 
    {
        background-color: blue;
        border-radius: 4px;
        color: white;
        padding: 10px 20px;
        text-align: center;
        font-size: 16px;
        margin: 4px 2px;
        opacity: 0.6;
        transition: 0.3s;
        display: inline-block;
        text-decoration: none;
        cursor: pointer;
    }

    .button4:hover {opacity: 1}

    input[type=number]:focus 
    {
        border: 3px solid #555;
    }


    .preloader-wrapper 
    {
        display: flex;
        justify-content: center;
/*      background: rgba(22, 22, 22, 0.3); */
        width: 100%;
        height: 80%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 99999;
        align-items: center;
    }

    .preloader-wrapper>.preloader 
    {
        /* background: transparent url("/obd_call/public/Loader/ajaxloader.webp") no-repeat center top;*/
        min-width: 128px;
        min-height: 128px;
        z-index: 99999;
        /* background-color:#f27878; */
        position: fixed;
    }

    .modal-title 
    {
        font-weight: bold; /* Make the title text bold */
        text-align: center;
    }

    .card { border: 0px solid rgba(0,0,0,.125) !important; }
    .card-body { padding: 0rem !important; }

    /* Style for the button when disabled */
    #sender_approve:disabled 
    {
        background-color: grey; /* Change the background color to grey */
        /* Other styles you may want to apply */
        cursor: not-allowed; /* Change cursor to indicate not allowed */
        /* pointer-events: none; Disable pointer events */
    }
    .bg-gray-300 {
    /* background-color: #04ff04 !important;  */
    background-color: #91a0fc !important;
    color: black !important;
}

.bg-gray-400 {
    background-color: #43fa43 !important; /* Darker than lawngreen */
    color: black !important;
}

.bg-gray-500 {
    background-color: #f08787 !important; /* Lawngreen */
    color: black !important;
}
.bg-gray-900{
    background-color: #00ee5a !important;
    color:black !important;
}
.bg-gray-800{
  background-color:lawngreen !important;
  color:black !important;

}
.bg-gray-1000{
background-color: #fa8072 !important;
}


</style>


<meta name="csrf-token" content="{{ csrf_token() }}">

 <form>
        @csrf 

            <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">               
                <div class="px-3" style="text-align: center; color: black; height: 50px; padding-top: 8px;">
                    <h2 class="text-2xl font-medium" style="font-weight: bold;"> Approve Campaign List</h2>
                </div>
	

                <!-- Campaign List Table -->    
                <div class="card mt-4">
                    <div class="card">
                        <div class="col card-body table-responsive">
                            <table class="approve_campaign_list-table hover stripe" id="approve_campaign_list-table"   style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
	                                    <th>User Name</th>
                                        <th>Campaign Name</th>
                                        <th>Context</th>
                                        <th>No of Mobile Numbers</th>
                                        <th>Entry time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
 </form> 


<script>

/*function download_function(get_mobilenos){
try {
    const phoneNumbersArray = get_mobilenos.split(',');
    const csvData = phoneNumbersArray.join('\n');
    const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'approve_campaign.csv';
    a.textContent = 'Download Receiver Numbers CSV';
    a.style.display = 'none';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
} catch (error) {
    console.error('Error: ' + error.message);
}
}*/

function download_function(get_mobilenos, audio_url) {
    try {
        // Split the comma-separated string of mobile numbers into an array
        const phoneNumbersArray = get_mobilenos.split(',');
        let outputData = '';

        // Check if audio_url is present
        if (audio_url === '-' || audio_url === '') {
            // If audio_url is '-', print only the numbers
            outputData = phoneNumbersArray.join('\n');
        } else {
            // If audio_url is not '-', combine numbers and audio URLs
            // Assuming both arrays are of the same length
            const audioUrlsArray = audio_url.split(',');

            // Combine numbers and audio URLs into CSV format
            for (let i = 0; i < phoneNumbersArray.length; i++) {
                outputData += phoneNumbersArray[i] + ',' + audioUrlsArray[i] + '\n';
            }
        }

        // Create a Blob containing the CSV data
        const blob = new Blob([outputData], { type: 'text/csv;charset=utf-8;' });

        // Create a URL for the Blob
        const url = window.URL.createObjectURL(blob);

        // Create a link element to trigger the download
        const a = document.createElement('a');
        a.href = url;
        a.download = 'approve_campaign.csv'; // Set the filename for the downloaded file
        a.textContent = 'Download Receiver Numbers CSV'; // Optional: Set text content for the link
        a.style.display = 'none'; // Hide the link
        document.body.appendChild(a); // Append the link to the document body
        a.click(); // Programmatically trigger the click event on the link to initiate download

        // Revoke the URL to release the resources
        window.URL.revokeObjectURL(url);
    } catch (error) {
        console.error('Error: ' + error.message);
    }
}




function campaign_approve(campaign_name, no_mob_no, user_id, user_name, context) 
{

    $("#approve_campaign_name").text(campaign_name);
    $('#hidden_campaign_name').val(campaign_name);
	$('#approve_user_name').text(user_name);
    $('#approve_context').text(context);
    $('#approve_total_numbers').text(no_mob_no);

	$('#approve-Modal').modal('show');
}


$(document).on('click', '#approveButton', function () 
{
    $('#approve-Modal').modal('hide');

    $("#loader").show();
    $(".preloader-wrapper").show();

    console.log("approve campaign send function");
    var campaignName = $('#hidden_campaign_name').val();
    console.log(campaignName);

    $.ajax({
        url: 'approve_campaign_send',
        method: 'POST',
        data: { campaign_name: campaignName },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) 
        {
            $("#loader").hide();
            $(".preloader-wrapper").hide();

		    console.log(response);
		    console.log(response.message);            

            var table = $('#approve_campaign_list-table').DataTable();
            table.draw();

            // Update the modal content with the response message
            $('#responseMessage').text(response.message);

            // Display the modal
            $('#responseModal').modal('show');            

            $('body').on('click', function (e) 
            {
                // Check if the click target is outside of the modal
                if (!$('#responseModal').is(e.target) && $('#responseModal').has(e.target).length === 0) 
                {
                    // Close the modal if the click is outside
                    $('#responseModal').modal('hide');
                }
            });
        },
        error: function (error) 
        {
            $("#loader").hide();
            $(".preloader-wrapper").hide();
            // Handle errors
            $('#responseMessage').text('An error occurred: ' + error.statusText);
            $('#responseModal').modal('show');

            $('body').on('click', function (e) 
            {
                // Check if the click target is outside of the modal
                if (!$('#responseModal').is(e.target) && $('#responseModal').has(e.target).length === 0) 
                {
                    // Close the modal if the click is outside
                    $('#responseModal').modal('hide');
                }
            });
        }
    });
});


function campaign_decline(campaign_name, no_mob_no, user_id, user_name, context) 
{
        console.log("cmapiagn_approve function");
        campaignName = campaign_name;
        mobileCount = no_mob_no;
        userId = user_id;
        userName = user_name;
        contextName = context;

	    $("#hidd_user_id").val(user_id);
        $('#user_name').text(user_name);
	    $('#userName').val(user_name);
        $('#context').text(context);
	    $('#contextName').val(context);
        $('#hidd_campaign_name').val(campaign_name);
        $('#no_mob_no').text(no_mob_no);
	    $('#mobile_count').val(no_mob_no);
        $('#remarks').val(''); // Clear input field
	    $('#remarksError').text('');

	    $('#decline-Modal').modal('show');	

}


	
$(document).on('click', '#declineBtn', function () 
{

    var remarks_text = $('#remarks').val();
    var userId = $('#hidd_user_id').val();
    var user_name = $('#userName').val();
    var context = $('#contextName').val();
    var campaign_name = $('#hidd_campaign_name').val();
    var mob_no = $('#mobile_count').val();


    //console.log("cmapiagn_approve function");
    campaignName = campaign_name;
	mobileCount = mob_no;
	user_id = userId;
	userName = user_name;
	contextName = context;
	remarks = remarks_text;

    //	Check if the creditAmount is empty
    if (remarks.trim() === '') 
	{
        	$('#remarksError').text('Remark is required.'); // Display validation message
        	return; // Prevent form submission
    } 
	else 
	{
		$('#remarksError').text(''); // Clear validation message
    }

	// Check if the remarks length is between 5 and 30 characters
	if (remarks.trim().length < 5 || remarks.trim().length > 30) 
	{
    		$('#remarksError').text('Remarks should be 5 and 30 characters.'); // Display validation message
    		return; // Prevent further processing or form submission
	} 
	else 
	{
    		$('#remarksError').text(''); // Clear validation message
	}
	
    $.ajax({
    url: '{{ route('decline_campaign') }}',
    method: 'POST',
	data: { campaign_name: campaignName , no_mob_no: mobileCount, user_id : userId, user_name: userName, context: contextName, remarks: remarks},
    headers: 
    {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response) 
    {
		$('#decline-Modal').modal('hide');        
	
		var message = response.message;
		// Assuming response.data is a JSON-encoded string

		// Update your modal or display element with the message
       	$('#decline_message').text(message);

      	// Show the modal or display element
       	$('#decline_modal').modal('show');

        var table = $('#approve_campaign_list-table').DataTable();
        table.draw();

	    $('body').on('click', function(e) 
	    {
          		// 	Check if the click target is outside of the modal
          		if (!$('#decline_modal').is(e.target) && $('#decline_modal').has(e.target).length === 0) 
		        {
               		// Close the modal if the click is outside
               		$('#decline_modal').modal('hide');
			
           		}
        });
		// Listen for the Escape key press
        $(document).keydown(function(e)
        {
                if (e.key === "Escape" || e.key === "Esc")
                {
                        // Close the modal and reload the page when the Escape key is pressed
                        $('#decline_modal').modal('hide');
                }
        });

        $('#okButton').on('click', function() 
        {
                // Close the modal using Bootstrap's modal function
                $('#decline_modal').modal('hide');
        });
		
	},
	error: function(error) 
    {
    
    }
    });

});

</script>


<script>
    $(document).ready(function () 
    {

        $('#remarks').on('input', function () 
        {
            $('#remarksError').text(''); // Clear validation message
        }); 

    });
</script> 


<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<!-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> -->


<!-- sender id model if the status is active -->
<div class="modal fade bs-example-modal-md" id="approve-Modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="confirmationModal" aria-hidden="true" data-backdrop="false" style=" position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 510px;">
    <div class="modal-dialog">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-header" style="border-top: 0px inset green; text-align: center;">
                <h5 class="modal-title">Approve Campaign</h5>
            </div>
            <div class="modal-body">
                <p><strong>Campaign Name:</strong> <span id="approve_campaign_name" class="approve_campaign_name"></span></p>
                <p><strong>User Name:</strong> <span id="approve_user_name" class="approve_user_name"></span></p>
                <p><strong>Context:</strong> <span id="approve_context" class="approve_context"></span></p>
                <p><strong>Total Calls:</strong> <span id="approve_total_numbers" class="approve_total_numbers"></span></p>   
           
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa;">
                <input type="hidden" name="hidden_campaign_name" id="hidden_campaign_name" class="form-control" value="">
                <button type="button"  class=" bg-gray-1000 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal">Cancel</button>
                <button type="button" class=" bg-gray-800 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="approveButton">Approve</button>
            </div>
        </div>
    </div>
</div>


<!-- Approve response modal -->
<div class="modal fade bs-example-modal-md" id="responseModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="noChannelsModal" aria-hidden="true" data-backdrop="false" style=" position:fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
    <div class="modal-dialog">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-header" style="border-top: 4px inset red; text-align: center;">
                <h5 class="modal-title">Approval Status</h5>
            </div>
            <div class="modal-body">
                <p id="responseMessage"></p>
            </div>
		<div class="modal-footer">
                <button type="button" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="ok_Button" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>


<!-- decline request Modal -->
<div class="modal" tabindex="-1" role="dialog" id="decline-Modal">
    <div class="modal-dialog" >
        <div class="modal-content" style="width:80%;">

            <div class="modal-header">
                <h5 class="modal-title" id="addCreditModalLabel">Decline Campaign</h5>
        <!--        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>  -->
            </div>
            <div class="modal-body">
                <p>User Name: <span id="user_name" class="credit-value"></span></p>
                <p>Context: <span id="context" class="credit-value"></span></p>
                <p>Mobile Number Count: <span id="no_mob_no" class="credit-value"></span></p>
                <div class="form-group">
                    <label for="remarks">Remarks:</label>
                    <input type="text" autofocus class="form-control" id="remarks" placeholder="min:5 & max:30" required minlength = "5" maxlength="30" autocomplete="off">
                    <span id="remarksError" class="text-danger"></span>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="hidd_user_id" id="hidd_user_id" class="form-control" value="">
                <input type="hidden" name="hidd_campaign_name" id="hidd_campaign_name" class="form-control" value="">
                <input type="hidden" name="userName" id="userName" class="form-control" value="your_user_name">
                <input type="hidden" name="contextName" id="contextName" class="form-control" value="your_context">
                <input type="hidden" name="mobile_count" id="mobile_count" class="form-control" value="your_context">

		        <button type="button" class=" bg-gray-1000 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal">Cancel</button>
                <button type="button" class=" bg-gray-800  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="declineBtn">Decline</button>
            </div>
        </div>
    </div>
</div>



<!-- decline response modal -->
<div class="modal fade bs-example-modal-md" id="decline_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="noChannelsModal" aria-hidden="true" data-backdrop="false" style=" position:fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
    <div class="modal-dialog">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-header" style="border-top: 4px inset red; text-align: center;">
                <h5 class="modal-title">Decline Status</h5>
            </div>
            <div class="modal-body">
                <p id="decline_message"></p>
            </div>
		<div class="modal-footer">
                <button type="button" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="okButton"  style="width: 100px;">OK</button>
            </div>
        </div>
    </div>
</div>



<div class="preloader-wrapper" style="display:none;">
      <div class="preloader">
      </div>
      <div class="text" style="color: white; background-color:#f27878; padding: 10px; margin-left:600px;">
            <b>Starting the campaign can take some time<br/> Please wait.</b> 
      </div>
</div>


@endsection
