<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')

<!-- HTML section begins -->
<html>

<style>   <!-- CSS styles for media queries -->
.modal.fade .modal-dialog {
        transform: translate(0, -175%) !important;
}
.bg-gray-900{
    background-color: #00ee5a !important;
    color:black !important;
}

</style>

<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
        /* JavaScript function for form validation */
        function validateForm() {

	    var user = document.getElementById("user").value;
            var userError = document.getElementById("user_error");

	    var campaign = document.getElementById("campaign").value;
            var campaignError = document.getElementById("campaign_error");
 
            var flag = true;


            if (user === "" || campaign === "") {


		if (user === "") {
                    userError.textContent = "User is required.";
                    userError.style.color = "red";
                }


		 if (campaign === "") {
                    campaignError.textContent = "campaign is required.";
                    campaignError.style.color = "red";
                }


                flag = false;
		}            

            if (flag) {
                // Disable the button
                document.getElementById("submit_btn").disabled = true;
                document.getElementById("submit_btn").style.backgroundColor = "gray";
                document.getElementById("cancel_btn").disabled = true;
                document.getElementById("cancel_btn").style.backgroundColor = "gray";
                document.getElementById("clear_btn").disabled = true;
                document.getElementById("clear_btn").style.backgroundColor = "gray";

                // Display processing message
                var processingMsg = document.createElement("p");
                processingMsg.textContent = "Generating CDR... Please wait";
                processingMsg.style.fontSize = "18px";
                document.getElementById("submit_btn").parentNode.insertBefore(processingMsg, document.getElementById("submit_btn"));

                return true;
            } else {
                // Prevent form submission
                return false;
            }


        }

</script>



<script>
        $(document).ready(function () {
            // Get the location dropdown element
            var usernameDropdown = $('#user');

            usernameDropdown.on('change', function () {
                var selectedUsername = usernameDropdown.val();

                $.ajax({
                    url: "{{ route('get_user') }}",
                    type: 'GET',
                    success: function (response) {
                        if (response.users) {
                            // Clear existing options
                            usernameDropdown.empty();
                            
                            // Add the default option
                            usernameDropdown.append($('<option>', {
                                value: '',
                                text: 'Select a User',
                                disabled: 'disabled',
                                selected: 'selected'
                            }));
                            
                            // Add each location as an option
                            $.each(response.users, function (name, id) {
                                usernameDropdown.append($('<option>', {
                                    value: id, // Replace with the actual location identifier
                                    text: name, // Replace with the actual location name
                                }));
                            });

				usernameDropdown.val(selectedUsername);
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle the error here
                        console.error("************"+xhr.responseText);
                    }
                });
            });

            // Trigger the change event on page load (if needed)
            usernameDropdown.trigger('change');
        });
    </script>



<script>

	$(document).ready(function () {
        // Get references to the input fields
        var usernameDropdown = $('#user');
	var campaignDropdown = $('#campaign');

	function updateCampaign() 
	{
		// Get the selected user from the dropdown
            	var selectedUser = usernameDropdown.val();
	
		// Make an AJAX request to fetch campaign data based on the selected user
            $.ajax({
                url: "{{ route('get_campaigns') }}",
                type: 'GET',
                data: { user: selectedUser },
                success: function (response) {

	//	if(response.campaigns){

		if(response.formatted_campaigns && response.campaign_names){
                    // Clear existing options in the campaign dropdown
                    campaignDropdown.empty();

                    // Add the default option
                    campaignDropdown.append($('<option>', {
                        value: '',
                        text: 'Select a Campaign',
                        disabled: 'disabled',
                        selected: 'selected'
                    }));

		// Add each campaign as an option
                   // $.each(response.campaigns, function (index, campaign_name) {
			$.each(response.formatted_campaigns, function (index, formattedCampaign) {
			var campaignName = response.campaign_names[index];
                        campaignDropdown.append($('<option>', {
                            value: campaignName,
                            text: formattedCampaign
                        }));
                    });

			// Enable the campaign field
                    campaignDropdown.prop('disabled', false);
                }
            },
            error: function (xhr, status, error) {
                // Handle the error here
                console.error("Error:", error);
                console.error(xhr.responseText);
            }
        });
    }
        // Attach the updateCampaign function to the change event of the user dropdown
       // usernameDropdown.on('change', updateCampaign);
	// Attach the updateCampaign function to the change event of the user dropdown
    	usernameDropdown.on('change', function () 
	{
        	// Disable the campaign field when no user is selected
        	campaignDropdown.prop('disabled', !usernameDropdown.val());
        	updateCampaign();
    	});

    });

</script>


<style>
.card { border: 0px solid rgba(0,0,0,.125) !important; }
.card-body { padding: 0rem !important; }
</style>


@if($message = Session::get('success'))
<script>
/*function closeModal() {
    // $('#success_msg').modal('hide');
    window.location.reload();
}
// JavaScript to close the modal when clicking outside
$(document).on('click', function (e) {
    if ($(e.target).closest('.modal').length === 0) {
        // Redirect to a different page
        window.location.reload();
    }
});

$(document).on('keydown', function (e) {
    if (e.key === 'Escape') {
        // Redirect to a different page
	window.location.reload();
    }
});*/


    $(document).ready(function() {
        $('#success_msg').modal('show');
	$('#success_msg').css('display', 'block');

	function clearFormFields() {
           $('#user').val('');
           $('#campaign').val('');
        }

	
        // Add a click event listener to the document body
        $('body').on('click', function(e) {
            // Check if the click target is outside of the modal
            if (!$('#success_msg').is(e.target) && $('#success_msg').has(e.target).length === 0) {
                // Close the modal if the click is outside
                $('#success_msg').modal('hide');
		        // clearFormFields();
                // location.reload();
            }
        });

	$('#success_msg .btn-success').on('click', function(){
                $('#success_msg').modal('hide');
                //location.reload();
                // clearFormFields();
                // location.reload();
        });

	$('#success_msg button.close').on('click', function() {
                $('#success_msg').modal('hide');
                //location.reload();
                // clearFormFields();
                // location.reload();
        });
    });
</script>




<div class="modal fade bs-example-modal-md" id="success_msg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);overflow: visible;padding-right: 15px;width: 420px; display: block;z-index: 999999;opacity: inherit;" data-backdrop="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='mdl'>
            <button type="button" class="close" aria-label="Close" style="width: 40px; padding: 0px; border-radius: 5px; margin-left:350px;">
                <span aria-hidden="true">x</span>
            </button>
            <center>
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 512 512" style="fill:#28a745;">
                    <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" />
                </svg>
                <br>
                <h3 style="color:green; font-size:22px; margin-top:10px;"><b>SUCCESS</b></h3>
                <br>
                <p style="margin-top:15px;"><b>{!! $message !!}</b></p>
                <br>
            </center>
            <button type="button" class="btn btn-success" aria-label="Close" style="margin-top:40px;">Close</button>
        </div>
    </div>
</div>
@endif



@if($message = Session::get('error'))
<script>
/*function closeModal() {
    // $('#success_msg').modal('hide');
    window.location.reload();
}
// JavaScript to close the modal when clicking outside
$(document).on('click', function (e) {
    if ($(e.target).closest('.modal').length === 0) {
        // Redirect to a different page
        window.location.reload();
    }
});

$(document).on('keydown', function (e) {
    if (e.key === 'Escape') {
        // Redirect to a different page
	window.location.reload();
    }
});*/


    $(document).ready(function() {
        $('#error_msg').modal('show');
	$('#error_msg').css('display', 'block');

	function clearFormFields() {
           $('#user').val('');
           $('#campaign').val('');
        }

	
        // Add a click event listener to the document body
        $('body').on('click', function(e) {
            // Check if the click target is outside of the modal
            if (!$('#error_msg').is(e.target) && $('#error_msg').has(e.target).length === 0) {
                // Close the modal if the click is outside
                $('#error_msg').modal('hide');
		        // clearFormFields();
                // location.reload();
            }
        });

	$('#error_msg .btn-danger').on('click', function(){
                $('#error_msg').modal('hide');
                //location.reload();
                // clearFormFields();
                // location.reload();
        });

	$('#error_msg button.close').on('click', function() {
                $('#error_msg').modal('hide');
                //location.reload();
                // clearFormFields();
                // location.reload();
        });
    });
</script>


<div class="modal fade bs-example-modal-md" id="error_msg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);overflow: visible;padding-right: 15px;width: 400px;display: block;z-index: 999999;opacity: inherit;" data-backdrop="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='mdl'>
            <button type="button" class="close" aria-label="Close" style="width: 40px; padding: 0px; border-radius: 5px; margin-left:350px;">
                <span aria-hidden="true">x</span>
            </button>
            <center>
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 512 512" style="fill:#dc3545;">
                <circle cx="256" cy="256" r="240" style="fill:#dc3545;"/>
                <line x1="150" y1="150" x2="362" y2="362" style="stroke:#fff;stroke-width:40px;"/>
                <line x1="362" y1="150" x2="150" y2="362" style="stroke:#fff;stroke-width:40px;"/>
            </svg>
                <br>
                <h3 style="color:red; font-size:22px; margin-top:10px;"><b>FAILURE</b></h3>
                <br>
                <p style="margin-top:15px;"><b>{!! $message !!}</b></p>
                <br>
            </center>
            <button type="button" class="btn btn-danger" aria-label="Close" style="margin-top:40px;">Close</button>
        </div>
    </div>
</div>
@endif


<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col" style="border-radius:50px !important;">
    <div class="px-3" style="background-color: #FFF; text-align: center; color: black; height: 50px; padding-top: 8px; margin-bottom: 20px;">
        <h2 class="text-2xl font-medium" style="font-weight: bold; margin-right:300px;">Generate CDR</h2>
    </div>

    <form action="{{ route('cdrs_generation') }}" method="POST" name="campaign_form" id="campaign_form" onsubmit="return validateForm()" enctype="multipart/form-data">

        @csrf


	<!-- user -->
    <div class="- mx-4 flex mb-6" style="margin-right:400px !important;">
            <!-- user input -->
            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">
                    User Name<span data-toggle="tooltip" data-original-title="Enter User name">
            <label style="color: #FF0000;">*</label> [?]
        </span> 
                    </label>
                </label>
                </div>
                <div class="md:w-1/2 px-3 mb-6 md:mb-0">

                        <select name="user" id="user" class="w-full bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" style="width: 300px; float: left" autocomplete="off">
                                <option value="" disabled selected>Select a User</option>

                        </select>
                <div id="user_error" class="error-message" style = "clear: both;"></div> 
			
                </div>
            </div>


	<!-- campaign name -->
    <div class="- mx-4 flex mb-6" style="margin-right:400px !important;">
            <!-- campaign name input -->
            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">
                    Campaign Name<span data-toggle="tooltip" data-original-title="Campaign name">
            <label style="color: #FF0000;">*</label> [?]
        </span> 
                </label>
                </div>
                <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                        <select name="campaign" id="campaign" class="w-full bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" style="width: 300px; float: left" autocomplete="off">
                                <option value="" disabled selected>Select a Campaign Name</option> 

                        </select>
			<div id="campaign_error" class="error-message" style = "clear: both;"></div>
                </div>
            </div>

	
	

      <!-- Buttons (Clear, Submit, Cancel) -->
      <div class="mx-3 md:flex md:space-x-4 mb-6" style="width: 900px;margin-left: 400px !important;">
    <!-- Clear button -->
    <div class="md:w-1/2 px-3 mb-4 md:mb-0">
        <button type="button" id="clear_btn"
            style="background-color: #00ee5a !important;"
            class="w-full mt-7 text-black py-3 px-4 rounded-full shadow-md hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition ease-in-out duration-300">
            Clear
        </button>
    </div>
    <!-- Submit button -->
    <div class="md:w-1/2 px-3 mb-4 md:mb-0">
        <button type="submit" name="submit_btn" id="submit_btn" style="background-color: #00ee5a !important;"
            class="w-full mt-7 text-black py-3 px-4 rounded-full shadow-md hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition ease-in-out duration-300">
            Generate CDR
        </button>
    </div>
    <!-- Cancel button -->
    <div class="md:w-1/2 px-3 mb-4 md:mb-0">
        <button type="button" id="cancel_btn" style="background-color: #00ee5a !important;" 
            class="w-full mt-7 text-black py-3 px-4 rounded-full shadow-md hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition ease-in-out duration-300"
            onclick="window.location.reload();">
            Cancel
        </button>
    </div>
</div>





<!-- javascript function for clear button -->
   <script>
  	// Wait for the document to be ready
  	$(document).ready(function() {
  	// Attach a click event handler to the "Clear" button
  	$('#clear_btn').click(function() {
  	var user = document.getElementById('user');
      	var campaign = document.getElementById('campaign');
      	// Clear the input fields by setting their values to an empty string
      	user.value = '';
      	campaign.value = '';
	$('#user_error').text('');
	$('#campaign_error').text('');
    	});
  	});
   </script>




<!-- javascript function for clear the error message for user and campaign -->
	<script>
    		// Wait for the document to be ready
    		$(document).ready(function () {
        		// Attach an input event handler to the file input
        		$('#user').on('input', function () {
            			var userError = document.getElementById("user_error");
				var campaignError = document.getElementById("campaign_error");
            			userError.textContent = ''; // Clear the error message
				campaignError.textContent = '';

        		});

			$('#campaign').on('input', function () {
				var userError = document.getElementById("user_error");
                                var campaignError = document.getElementById("campaign_error");
                                userError.textContent = ''; // Clear the error message
                                campaignError.textContent = '';

        		});

		});

	</script>

 </form>
    
</div>

</html>
<script>
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
  });
</script>

@endsection
  







