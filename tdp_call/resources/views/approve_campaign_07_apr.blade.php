<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')
<div id='loader' style="display: none;"></div>


<style>
/* Styling for confirmation dialogue yes/no buttons */ 
.button {
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

.red-text {
    color: red;
}

.serverId {
    color: blue;
}

.channelCount {
    color: red;
}

.button1 {
 background-color: green;
color: white;
width: 70px;
height: 40px;
}

.button2 {
 background-color: green;
color: white;
width: 70px;
height: 40px;
}


.button3 {
background-color: red;
color: white;
width: 70px;
height: 40px;
}


.modal-header{
     background-color: #5cb85c;
     color: white;
     text-align: center; /* Center-align text */
     padding: 10px 30px;

}

.modal-footer {
    padding: 8px; /* Reduce the padding inside the footer */
    margin: 10px 0; /* Reduce the margin above and below the footer */
    display: flex; justify-content: flex-end;
}

.modal-title {
    font-weight: bold; /* Make the title text bold */
    text-align: center;
}



.close {
  color: white;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.button4 {
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

input[type=number]:focus {
    border: 3px solid #555;
}


.preloader-wrapper {
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

    .preloader-wrapper>.preloader {
     /* background: transparent url("/obd_call/public/Loader/ajaxloader.webp") no-repeat center top;*/
      min-width: 128px;
      min-height: 128px;
       z-index: 99999;
       /* background-color:#f27878; */
       position: fixed;
    }

.modal-title {
    font-weight: bold; /* Make the title text bold */
    text-align: center;
}

.card { border: 0px solid rgba(0,0,0,.125) !important; }
.card-body { padding: 0rem !important; }

/* Style for the button when disabled */
#sender_approve:disabled {
    background-color: grey; /* Change the background color to grey */
    /* Other styles you may want to apply */
    cursor: not-allowed; /* Change cursor to indicate not allowed */
    /* pointer-events: none; Disable pointer events */
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
            <table class="approve_campaign_list-table hover stripe" id="approve_campaign_list-table" style="width:100%">
                <thead>
                    <tr>
                    <th>No.</th>
	                <th>User Name</th>
                    <th>Campaign Name</th>
                    <th>Ivr Id</th>
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

function download_function(get_mobilenos){
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
}

function select_sender(campaign_name, mobile_numbers)
{
    campaignName = campaign_name;
    total_calls = mobile_numbers;


    // Function to handle checkbox change
    function handleCheckboxChange() 
    {

        // alert('haiii');

        var isChecked = $(this).prop('checked');

        if (!isChecked) {
            // alert('unchecked');
        // If "Select All" checkbox is unchecked, remove input fields and error message
        $('#CamPercentage').empty();
        $('#length_error').html('');
        $('#sender_approve').prop('disabled', true);
        }else{

        const isSingleValue = $('.senderCheckbox:checked').length === 1;
        let defaultValue = 0;

        // alert(($('#sender_label').length));

        // alert($('.senderCheckbox:checked').length);

        if (isSingleValue) {
            // alert('haii');
            defaultValue = 100;
        }
        // else{
        //     defaultValue = 0;
        // }

        const input = $('<input type="text" name="cam_percentage[]" id="cam_percentage" class="cam_percentage" maxlength="3" minlength="1" value="">').val(defaultValue);

        const container = $('<div>');

        // alert('container');

        input.css({
        'background-color': '#E6E6E6',
        'border': '1px solid black',
        'border-radius': '5px'
        });

        container.append(input);

        $('#CamPercentage').append(container);

        // let value = $(this).val();

        //     alert("input Values : " + value);

        //     let InputValue = $('.cam_percentage').val();

        //     alert('Input Values : ' + InputValue);

        //     let count = InputValue.length;

        //     alert("count of inputs : " + count);

        // alert('After append container');

         const numberRegex = /^[0-9]*$/;

     // Add event listener to input field
        input.on('input', function() {
            let total = 0;
            let hasZeroInput = false; // Flag to check for zero or null input
            $('.cam_percentage').each(function() {
                const val = parseInt($(this).val()) || 0;
                if (val === 0) {
                    hasZeroInput = true;
                }
                total += val;
            });

                // Check for non-numeric characters
            if (!numberRegex.test($(this).val())) {
                $('#length_error').html('Characters not allowed. Please enter numbers only.');
                return; // Exit the function early if non-numeric characters are detected
            }

            if (hasZeroInput) {
            $('#length_error').html('Input is Zero. Please enter a valid number.');
            return; // Exit the function early if there's a zero input
            }

        if (!numberRegex.test($(this).val())) {
            // If the input value does not match the number regex, clear the input field
            $(this).val('');
            $('#length_error').html('Invalid input. Please enter numbers only.');
            console.log("Invalid input. Please enter numbers only.");
        } else if (total > 100) {
            // If sum exceeds 100, revert the input value and log a message
            $(this).val('');
            $('#length_error').html('Total exceeds 100. Please enter a valid number.');
            console.log("Total exceeds 100. Please enter a valid number.");
        } else if (total < 100) {
            // If sum is less than 100, display an error message
            $('#length_error').html('Total is less than 100. Please enter a valid number.');
            console.log("Total is less than 100. Please enter a valid number.");
        } else {
            console.log("Valid input:", $(this).val());
            // You can do further processing with the input value here
        }
    });


        const anyChecked = $('.senderCheckbox:checked').length > 0;

        if ($('.senderCheckbox:checked').length > total_calls) {
            // Display error message and disable the button
            $('#length_error').html('Sender ID count exceeds ' + total_calls);
            $('#sender_approve').prop('disabled', true);
        } else {
            // Enable the button
            $('#length_error').html('');
            $('#sender_approve').prop('disabled', !anyChecked);
            $('#selectAllSenders').prop('checked', false);
        }
    }
    }

    // Add event listener for checkbox change
    $(document).on('change', '.senderCheckbox', handleCheckboxChange);

    // Initially disable the Approve button
    $('#sender_approve').prop('disabled', true)

    // Function to handle Approve button click
    $('#sender_approve').on('click', function() 
    {
        window.server_ids = $('.senderCheckbox:checked').map(function() 
        {
            return $(this).val();
        }).get();

        
        console.log(server_ids);

        // Call campaign_approve function with selected IDs
        campaign_approve(server_ids);
        //approve_send(server_ids);
    });

    // window.cam_percentage = $('#cam_percentage').map(function() 
    //     {
    //         return $(this).val();
    //     }).get();

    $.ajax({
    url: '{{ route('get_sender_id') }}',
    method: 'GET',
    success: function(response) 
    {
        if (response.success && response.sender_id && response.sender_id.length > 0) {
    const senderIds = response.sender_id;

    // Clear any existing checkboxes before populating
    $('#senderIds').empty();

    const length = senderIds.length;

    // alert("length checked" + length);
    // alert("total calls" + total_calls);

// Loop through sender_ids to create checkboxes dynamically
senderIds.forEach((sender, index) => {
    const senderId = sender.server_id; // Assuming 'server_id' is the ID to be displayed
    // const isSingleValue = senderIds.length === 1;
    // let defaultValue = 0;

    // if (isSingleValue) {
    //     defaultValue = 100;
    // }

    // Create the checkbox and label elements
    const checkbox = $('<input type="checkbox" class="senderCheckbox">').attr('value', senderId);
    const label = $('<label id="sender_label">').text(senderId);
    // const input = $('<input type="text" name="cam_percentage" class="cam_percentage" maxlength="3" minlength="1">').val(defaultValue);

    // Create a container div for checkbox, label, and input
    const container = $('<div>');

    // Apply CSS to add space between checkbox and label
    checkbox.css('margin-right', '8px'); // Adjust the value to set the desired spacing
    label.css('margin-right', '8px');
    // input.css({
    //     'background-color': '#E6E6E6',
    //     'border': '1px solid black',
    //     'border-radius': '5px'
    // });

    // Append checkbox, label, and input to the container
    container.append(checkbox, label);

    // Append the container to the parent element '#senderIds'
    $('#senderIds').append(container);

    // Regular expression to match only numbers
    // const numberRegex = /^[0-9]*$/;

    // Add event listener to input field
    // input.on('input', function() {
    //     let total = 0;
    //     let hasZeroInput = false; // Flag to check for zero or null input
    //     $('.cam_percentage').each(function() {
    //         const val = parseInt($(this).val()) || 0;
    //         if (val === 0) {
    //             hasZeroInput = true;
    //         }
    //         total += val;
    //     });

        // if (hasZeroInput) {
        //     $('#length_error').html('Input is Zero. Please enter a valid number.');
        //     return; // Exit the function early if there's a zero input
        // }

        $('#length_error').html('');

        // if (!numberRegex.test($(this).val())) {
        //     // If the input value does not match the number regex, clear the input field
        //     $(this).val('');
        //     $('#length_error').html('Invalid input. Please enter numbers only.');
        //     console.log("Invalid input. Please enter numbers only.");
        // } else if (total > 100) {
        //     // If sum exceeds 100, revert the input value and log a message
        //     $(this).val('');
        //     $('#length_error').html('Total exceeds 100. Please enter a valid number.');
        //     console.log("Total exceeds 100. Please enter a valid number.");
        // } else if (total < 100) {
        //     // If sum is less than 100, display an error message
        //     $('#length_error').html('Total is less than 100. Please enter a valid number.');
        //     console.log("Total is less than 100. Please enter a valid number.");
        // } else {
        //     console.log("Valid input:", $(this).val());
        //     // You can do further processing with the input value here
        // }
    // });
});


        $('#selectAllSenders').on('change', function () {

        var isChecked = $(this).prop('checked');
        // Set all checkboxes state based on "Select All" checkbox
        var checkedData = $('.senderCheckbox').prop('checked', $(this).prop('checked'));

        let defaultValue = 0;

        // alert((checkedData.length));

        if (!isChecked) {
        // If "Select All" checkbox is unchecked, remove input fields and error message
        $('#CamPercentage').empty();
        $('#length_error').html('');
        $('#sender_approve').prop('disabled', true);
        }else{

            if (checkedData) {
                defaultValue = 100 / checkedData.length; // Distribute the default value equally
                defaultValue = Math.round(defaultValue); // Round the default value
            }

        // alert(defaultValue);


        checkedData.each(function() {

            const input = $('<input type="text" name="cam_percentage[]" id="cam_percentage" class="cam_percentage" maxlength="3" minlength="1" value="">').val(defaultValue);

            const container = $('<div>'); // Assuming you want each input to take 1/4th of the row width

            input.css({
                'background-color': '#E6E6E6',
                'border': '1px solid black',
                'border-radius': '5px'
            });

            container.append(input);
            $('#CamPercentage').append(container);

            // let value = $(this).val();
            // alert("input Values : " + value);

            // let InputValue = $('.cam_percentage').val();

            // alert('Input Values : ' + InputValue)

            // let count = InputValue.length;

            // alert("count of inputs : " + count);
          

        // alert('After append container');

         const numberRegex = /^[0-9]*$/;

        //  alert('regex');

     // Add event listener to input field
        input.on('input', function() {
            let total = 0;
            let hasZeroInput = false; // Flag to check for zero or null input
            $('.cam_percentage').each(function() {
                const val = parseInt($(this).val()) || 0;
                if (val === 0) {
                    hasZeroInput = true;
                }
                total += val;
            });

            // alert('after regex');
                // Check for non-numeric characters
            if (!numberRegex.test($(this).val())) {
                $('#length_error').html('Characters not allowed. Please enter numbers only.');
                return; // Exit the function early if non-numeric characters are detected
            }

            if (hasZeroInput) {
            $('#length_error').html('Input is Zero. Please enter a valid number.');
            return; // Exit the function early if there's a zero input
            }

        if (!numberRegex.test($(this).val())) {
            // If the input value does not match the number regex, clear the input field
            $(this).val('');
            $('#length_error').html('Invalid input. Please enter numbers only.');
            console.log("Invalid input. Please enter numbers only.");
        } else if (total > 100) {
            // If sum exceeds 100, revert the input value and log a message
            $(this).val('');
            $('#length_error').html('Total exceeds 100. Please enter a valid number.');
            console.log("Total exceeds 100. Please enter a valid number.");
        } else if (total < 100) {
            // If sum is less than 100, display an error message
            $('#length_error').html('Total is less than 100. Please enter a valid number.');
            console.log("Total is less than 100. Please enter a valid number.");
        } else {
            console.log("Valid input:", $(this).val());
            // You can do further processing with the input value here
        }
    });
    });
}

        if (!$(this).prop('checked')) {
            // If "Select All" checkbox is unchecked, remove error message and enable the button
            $('#length_error').html('');
            // $('#sender_approve').prop('disabled', false);
            $('#sender_approve').prop('disabled', true);
        } else if (checkedData.length > total_calls) {
            // Display error message and disable the button
            $('#length_error').html('Sender ID count exceeds ' + total_calls);
            $('#sender_approve').prop('disabled', true);
        } else {
            // Enable the button
            $('#length_error').html('');
            $('#sender_approve').prop('disabled', false);
        }

        });
    


    $('#campaign_name').text(campaignName);
    $('#total_numbers').text(total_calls);
    $('#senderModal').modal('show');

    $('body').on('click', function (e) {
        // Check if the click target is outside of the modal
        if (!$('#senderModal').is(e.target) && $('#senderModal').has(e.target).length === 0) {
            // Close the modal if the click is outside
            $('#senderModal').modal('hide');
        }
    });

    // Listen for the Escape key press
    $(document).keydown(function (e) {
        if (e.key === "Escape" || e.key === "Esc") {
            // Close the modal and reload the page when the Escape key is pressed
            $('#senderModal').modal('hide');
        }
    });


}

        else 
        {
            // Handle errors here
            console.error(response.error);
		    // Display a different modal when simCount is 0
            $('#noSenderModal').modal('show');

		    $('body').on('click', function(e) 
            {
                // Check if the click target is outside of the modal
                if (!$('#noSenderModal').is(e.target) && $('#noSenderModal').has(e.target).length === 0) 
                {
                    // Close the modal if the click is outside
                    $('#noSenderModal').modal('hide');
                }
	        });
        }
    },
    error: function(error) 
    {
        // Handle AJAX errors
        console.error(error);
    }
    });
}
</script>


<script>

function campaign_approve(server_ids) 
{	
	$.ajax({
        url: '{{ route('get_sender_count') }}',
        method: 'GET',
        data: 
        {
            server_ids: server_ids // Sending server_ids array as data
        },
        success: function(response) 
        {
            if (response.success) 
            {
                var modalContent = '';
               // var noChannels = false;
               var noChannels = [];
                var zeroChannelServerId = '';

                response.server_counts.forEach(function(serverCount) 
                {
                    var serverId = serverCount.server_id;
                    var freeChannels = serverCount.free_channels;

                    if (freeChannels > 0) 
                    {
                        modalContent += `<tr><td><span class="serverId">${serverId}</span></td><td><span class="channelCount">${freeChannels}</span></td></tr>`;
                    } 
                    else 
                    {
                        // noChannels = true;
                        // zeroChannelServerId = serverId;
                        noChannels.push(serverId);
                    }
                });

                if (noChannels.length > 0) 
                {
                    var noChannelsList = noChannels.join(', ');
                    $('#zeroChannelServer').text(noChannelsList)
                    $('#noChannelsModal').modal('show');                
                } 
                else 
                {
                    $('#modalBody tbody').html(modalContent);
                    $('#approveModal').modal('show');
                }

                $('#noChannelsModal').on('click', '#backButton', function() 
                {
                    redirectToApproveModal();
                });
                function redirectToApproveModal() 
                {
                    $('#noChannelsModal').modal('hide');  
                    $('#senderModal').modal('show');
                }

                $('body').on('click', function(e) 
                {
                            // Check if the click target is outside of the modal
                            if (!$('#approveModal').is(e.target) && $('#approveModal').has(e.target).length === 0) 
                            {
                                // Close the modal if the click is outside
                                $('#approveModal').modal('hide');
                            }
                });

                // Listen for the Escape key press
                $(document).keydown(function(e) 
                {
                        if (e.key === "Escape" || e.key === "Esc") 
                        {
                            // Close the modal and reload the page when the Escape key is pressed
                            $('#approveModal').modal('hide');
                        }
                });

            } 
            else 
            {
                // Handle errors here
                console.error(response.error);
		        
		        // Display a different modal when simCount is 0
                $('#noChannelsModal').modal('show');

		        $('body').on('click', function(e) 
                {
                    // Check if the click target is outside of the modal
                    if (!$('#noChannelsModal').is(e.target) && $('#noChannelsModal').has(e.target).length === 0) 
                    {
                        // Close the modal if the click is outside
                        $('#noChannelsModal').modal('hide');

                    }
	            });
	
            }
        },
        error: function(error) 
        {
            // Handle AJAX errors
            console.error(error);
        }
    });

}


//function approve_send(server_ids)
// Define the named function
function handleApproveButtonClick() {
    $("#loader").show();
    $(".preloader-wrapper").show();
    // var cam_percentage = $(".cam_percentage").val();

    // alert(cam_percentage.length);

    window.Inputs = $('.cam_percentage').map(function() 
        {
            return $(this).val();
        }).get();

    // alert(window.Inputs);

    console.log("approve campaign send function");
    console.log(campaignName);
    console.log(window.server_ids);

    $.ajax({
        url: 'approve_campaign_send',
        method: 'POST',
        data: { campaign_name: campaignName, server_ids: window.server_ids, cam_percentage: window.Inputs },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            console.log("success");
            console.log(response);
                console.log(response.data);
                console.log(typeof(response.data));

            // Assuming response.data is a JSON-encoded string
            const data = JSON.parse(response.data);
            if(data.response_status === 202){
         const neron_ids = JSON.parse(response.neron_ids);
}
                console.log(data);
                console.log(data.response_code);

            // Check if the API call was successful
            if (data.response_code === 1) {
                // Update the modal content with the response message
                $('#responseMessage').text(data.response_msg);

                var table = $('#approve_campaign_list-table').DataTable();
                table.draw();
            } else if (data.response_code === 1 && data.response_status === 202) {
                $('#neronResponseMessage').text(data.response_msg);
                $('#neronResponseModal').modal('show');

                $(document).on('click', '#yes_Button', handleApproveButtonClick);

                // No button click event
                $('#no_Button').on('click', function () {

                    // Optionally, you can redraw the DataTable
                    $('#yourDataTable').DataTable().draw();

                });

            } else {
                // Handle the case where the API call was not successful
                $('#responseMessage').text('API Error: ' + data.response_msg);
            }

            $("#loader").hide();
            $(".preloader-wrapper").hide();

            // Display the modal
            $('#responseModal').modal('show');

            $('body').on('click', function (e) {
                // Check if the click target is outside of the modal
                if (!$('#responseModal').is(e.target) && $('#responseModal').has(e.target).length === 0) {
                    // Close the modal if the click is outside
                    $('#responseModal').modal('hide');
                }
            });

        },
        error: function (error) {
            $("#loader").hide();
            $(".preloader-wrapper").hide();
            // Handle errors
            $('#responseMessage').text('An error occurred: ' + error.statusText);
            $('#responseModal').modal('show');

            $('body').on('click', function (e) {
                // Check if the click target is outside of the modal
                if (!$('#responseModal').is(e.target) && $('#responseModal').has(e.target).length === 0) {
                    // Close the modal if the click is outside
                    $('#responseModal').modal('hide');
                }
            });
        }
    });
}

// Attach the named function to the click event
$(document).on('click', '#approveButton', handleApproveButtonClick);


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


<!-- Channel count model if the free channels available -->
<div class="modal fade bs-example-modal-md" id="approveModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="confirmationModal" aria-hidden="true" data-backdrop="false" style=" position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Campaign</h5>
            </div>
            <div class="modal-body" id="modalBody">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Server ID</th>
                            <th>Free Channels</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table rows will be dynamically populated here -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            <button type="button" class="bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal">Cancel</button>
                <button type="button"  class="bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal" id="approveButton">Start</button>
            </div>
        </div>
    </div>
</div>



<!-- sender id model if the status is active -->
<div class="modal fade bs-example-modal-md" id="senderModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="confirmationModal" aria-hidden="true" data-backdrop="false" style=" position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 510px;">
    <div class="modal-dialog">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-header" style="border-top: 0px inset green; text-align: center;">
                <h5 class="modal-title">Approve Campaign</h5>
            </div>
            <div class="modal-body">
                <p><strong>Campaign Name:</strong> <span id="campaign_name" class="campaign_name"></span></p>
                <p><strong>Total Calls:</strong> <span id="total_numbers" class="total_numbers"></span></p>
                <p><strong>Sender Id:</strong> <input type="checkbox" id="selectAllSenders"> Select All</p>
                <div class="row">
                <div id="senderIds" class="col">
                <!-- <div class="row" id="senderIds_row">
                    <div class="col-3" id="senderIds_col"></div>
                </div> -->
                </div>
                <div id="CamPercentage" class="col"></div></div>
                <!-- <input type="text" name="cam_percentage" id="cam_percentage"> -->
            <div id="length_error" style="color: red;"></div>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa;">
                <button type="button"  class=" bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal">Cancel</button>
                <button type="button" id = "sender_approve" class=" bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal" id="approveButton">Approve</button>
            </div>
        </div>
    </div>
</div>


<!-- channel count model if the free channel is not available -->
<div class="modal fade bs-example-modal-md" id="noSenderModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="noSenderModal" aria-hidden="true" data-backdrop="false" style=" position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
    <div class="modal-dialog">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-header" style="border-top: 4px inset red; text-align: center;">
                <h5 class="modal-title">Approve Campaign</h5>
            </div>
            <div class="modal-body">
                <p>There are no Sender ID's Active to start the campaign. Please Restart the GSM Board</p>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa;">
                <button type="button" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>


<!-- channel count model if the free channel is not available -->
<div class="modal fade bs-example-modal-md" id="noChannelsModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="noChannelsModal" aria-hidden="true" data-backdrop="false" style=" position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
    <div class="modal-dialog">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-header" style="border-top: 4px inset red; text-align: center;">
                <h5 class="modal-title">Approve Campaign</h5>
            </div>
            <div class="modal-body">
            <p>There are no free channels available in <span id="zeroChannelServer" class="red-text"></span> to start the campaign.</p>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa;">
                <button type="button" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="backButton">Back</button>
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

<!-- Approve response modal -->
<div class="modal fade bs-example-modal-md" id="neronResponseModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="noChannelsModal" aria-hidden="true" data-backdrop="false" style=" position:fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
    <div class="modal-dialog">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-header" style="border-top: 4px inset red; text-align: center;">
                <h5 class="modal-title">Confirmation</h5>
            </div>
            <div class="modal-body">
                <p id="neronResponseMessage"></p>
                <p>Are you sure want to continue with the remaining neron boards</p>
            </div>
		<div class="modal-footer">
                <button type="button" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="yes_Button" data-dismiss="modal">Yes</button>
                <button type="button" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="no_Button" data-dismiss="modal">No</button>
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

		<button type="button" class=" bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal">Cancel</button>
                <button type="button" class=" bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="declineBtn">Decline</button>
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

