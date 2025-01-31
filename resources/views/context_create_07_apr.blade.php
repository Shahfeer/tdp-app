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
</style>

<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
        /* JavaScript function for form validation */
        function validateForm() {
            var fileInput = document.getElementById("upload_file");
            var fileError = document.getElementById("file_error");

		var fileTypeError = document.getElementById("file_type_error");

//            var context = document.getElementById("context_value").value;
  //          var contextError = document.getElementById("context_error");

	    var company = document.getElementById("company_name").value;
            var companyError = document.getElementById("company_name_error");

	    var location = document.getElementById("location").value;
            var locationError = document.getElementById("location_error");

	    var language = document.getElementById("language_code").value;
            var languageError = document.getElementById("language_code_error");
 
	    var type = document.getElementById("type").value;
            var typeError = document.getElementById("type_error");

	    var remarks = document.getElementById("remarks").value;
	   var remarksError =  document.getElementById("remarks_error");

            var flag = true;


	    if( company.length < 3)
                {
                        companyError.textContent = "Company name must be at least 3 characters long.";
                        companyError.style.color = "red";
                         flag = false; // Set flag to false to prevent form submission
                }

	
	    if( remarks.length < 5)
                {
                        remarksError.textContent = "Remarks must be at least 5 characters long.";
                        remarksError.style.color = "red";
                         flag = false; // Set flag to false to prevent form submission
                }


        if (fileInput.files.length === 0 || remarks === "" || company === "" || location === "" || language === "" || type === "") {

		
        if (fileInput.files.length === 0) {
                    fileError.textContent = "WAV File is required.";
                    fileError.style.color = "red";
		            file_type_error.textContent = "";
                }

		if (remarks === "") {
                    remarksError.textContent = "Remarks is required.";
                    remarksError.style.color = "red";
                }

		if (company === "") {
                    companyError.textContent = "Company name is required.";
                    companyError.style.color = "red";
                }


		if (location === "") {
                    locationError.textContent = "State is required.";
                    locationError.style.color = "red";
                }


		 if (language === "") {
                    languageError.textContent = "language is required.";
                    languageError.style.color = "red";
                }


		if (type === "") {
                    typeError.textContent = "Type is required.";
                    typeError.style.color = "red";
                }


                flag = false;
		}            
	    //alert("==="+flag)

		else if( company.length	< 3)
                {
                        companyError.textContent = "Company name must be at least 3 characters long.";
                        companyError.style.color = "red";
                         flag = false; // Set flag to false to prevent form submission
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
                processingMsg.textContent = "Prompt Upload Processing... Please wait";
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

// Get a reference to the "Company Name" input field
var companyInput = $('#company_name');

// Attach an input event listener to convert input to uppercase
companyInput.on('input', function () {
    var inputText = companyInput.val();
    companyInput.val(inputText.toUpperCase());
});
});

</script>


    <script>
        $(document).ready(function () {
            // Get the location dropdown element
            var locationDropdown = $('#location');

            locationDropdown.on('change', function () {
                var selectedLocation = locationDropdown.val();

                $.ajax({
                    url: "{{ route('get_location') }}",
                    type: 'GET',
                    success: function (locations) {
                        if (locations.length > 0) {
                            // Clear existing options
                            locationDropdown.empty();
                            
                            // Add the default option
                            locationDropdown.append($('<option>', {
                                value: '',
                                text: 'Select a Location',
                                disabled: 'disabled',
                                selected: 'selected'
                            }));
                            
                            // Add each location as an option
                            $.each(locations, function (index, location) {
                                locationDropdown.append($('<option>', {
                                    value: location.state_short_name, // Replace with the actual location identifier
                                    text: location.name, // Replace with the actual location name
                                }));
                            });

				locationDropdown.val(selectedLocation);
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle the error here
                        console.error(xhr.responseText);
                    }
                });
            });

            // Trigger the change event on page load (if needed)
            locationDropdown.trigger('change');
        });
    </script>



    <script>
        $(document).ready(function () {
            // Get the location dropdown element
            var languageDropdown = $('#language_code');

            languageDropdown.on('change', function () {
                var selectedLanguage = languageDropdown.val();

                $.ajax({
                    url: "{{ route('get_language') }}",
                    type: 'GET',
                    success: function (languages) {
                        if (languages.length > 0) {
                            // Clear existing options
                            languageDropdown.empty();


                            // Add the default option
                            languageDropdown.append($('<option>', {
				value: '',
                                text: 'Select a Language',
                                disabled: 'disabled',
                                selected: 'selected'

                            }));

                            // Add each location as an option
                            $.each(languages, function (index, language) {
                                languageDropdown.append($('<option>', {
                                    value: language.language_code, // Replace with the actual location identifier
                                    text: language.language_name, // Replace with the actual location name
                                }));
                            });

                               	languageDropdown.val(selectedLanguage);
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle the error here
                        console.error(xhr.responseText);
                    }
                });
            });

            // Trigger the change event on page load (if needed)
            languageDropdown.trigger('change');
        });
    </script>




<script>
    $(document).ready(function () {
        // Get references to the input fields
        var companyInput = $('#company_name');
        var locationDropdown = $('#location');
        var languageDropdown = $('#language_code');
        var typeDropdown = $('#type');
        var contextInput = $('#context');
	var context_input = $('#context_value');


        // Function to update the context based on selected values
        function updateContext() {
            var companyValue = companyInput.val() || '';
            var locationValue = locationDropdown.val() || '';
            var languageValue = languageDropdown.val() || '';
            var typeValue = typeDropdown.val() || '';

	    var companyNamePrefix = companyValue.substring(0, 3);

	    var contextValue = '';

            // Concatenate the values to form the context
            //var contextValue = companyNamePrefix + '_' + locationValue + '_' + languageValue + '_' + typeValue;

	    if (companyNamePrefix || locationValue || languageValue || typeValue) {
            contextValue = companyNamePrefix + '_' + locationValue + '_' + languageValue + '_' + typeValue;
        }
            
            // Set the context input field value
	    context_input.val(contextValue);
	    contextInput.val(contextValue);

	    var context = $("#context").val();
            console.log(context);
            $.ajax({
                url: "{{ route('check_context') }}",// URL to your server-side script for checking the combination
                type: "POST",
                data: { context: context },
		headers: {
        		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         	},
                success: function(response) {
			//console.log(response);
			if (response.context) 
			{
        			var context = response.context;
        			var promptPath = response.prompt_path;
        			console.log("success");
        			console.log("Context: " + context);
        			console.log("Prompt Path: " + promptPath);
				$('#exit_context').text(context);
        			$('#exit_prompt').text(promptPath);

        			$('#context-exit').modal('show');
	
				$("#context-exit .button0").on("click", function () 
				{
                    			// Reload the page when "No" is clicked
                    			window.location.reload();
                		});
    			}

                },
		error: function(jqXHR, textStatus, errorThrown) {
		        console.log("AJAX Error: " + textStatus, errorThrown);
    		}
            });

        }

        // Add change event listeners to the input fields
        companyInput.on('change', updateContext);
        locationDropdown.on('change', updateContext);
        languageDropdown.on('change', updateContext);
        typeDropdown.on('change', updateContext);

        // Trigger the initial update when the page loads
        updateContext();


    });
</script>


<script>

     // AJAX request to check if context and prompt combination exists
	$(document).ready(function() {
        $("#context").change(function() {
	console.log("exit context function");
            var context = $("#context").val();
            var prompt = $("#upload_file").val();
	    console.log(context);
	    console.log(prompt);
            $.ajax({
                url: "{{ route('check_context') }}",// URL to your server-side script for checking the combination
                type: "POST",
                data: { context: context, prompt: prompt },
                success: function(response) {
                    if (response === "exists") {
                        showModal();
                    }
                }
            });
        });
    });

</script>


<style>
.card { border: 0px solid rgba(0,0,0,.125) !important; }
.card-body { padding: 0rem !important; }
</style>


@if($message = Session::get('success'))
<script>
//var successMessage = '{{ $message }}';

//console.log('Success message:', successMessage);

    $(document).ready(function() {
       
        $('#success_msg').modal('show');
        history.replaceState({}, document.title, window.location.pathname + window.location.search);
        
	//$('#success_msg').css('display', 'block');

	function clearFormFields() {
            $('#upload_file').val('');
            $('#company_name').val('');
            $('#location').val('');
            $('#language_code').val('');
            $('#type').val('');
            $('#context_value').val('');
            $('#remarks').val('');
        }

	
        // Add a click event listener to the document body
        $('body').on('click', function(e) {
            // Check if the click target is outside of the modal
            if (!$('#success_msg').is(e.target) && $('#success_msg').has(e.target).length === 0) {
                // Close the modal if the click is outside
                $('#success_msg').modal('hide');
               
               // clearFormFields();          
               
            }
        });

	$('#success_msg .btn-success').on('click', function(){   
                $('#success_msg').modal('hide');  
                   
               // clearFormFields();       
            
        });

	$('#success_msg button.close').on('click', function() {             
                $('#success_msg').modal('hide'); 
                                   
               // clearFormFields();               
               
        });
    });
    

</script>




<div class="modal fade bs-example-modal-md" id="success_msg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="position: absolute;left: 50%;top: 50%;transform: translate(-50%, -50%);overflow: visible;padding-right: 15px;width: 420px;display: block;z-index: 999999;opacity: inherit;" data-backdrop="true">
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


<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">
    <div class="px-3" style="background-color: #FFF; text-align: center; color: black; height: 50px; padding-top: 8px; margin-bottom: 20px;">
        <h2 class="text-2xl font-medium" style="font-weight: bold;">Create Prompt</h2>
    </div>

    <form action="{{ route('prompt_create') }}" method="POST" name="campaign_form" id="campaign_form" onsubmit="return validateForm()" enctype="multipart/form-data">

        @csrf

	<!--Prompt File Upload and Date -->
       <div class="- mx-3 flex mb-6">
	    <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">Upload prompt<span style="color: #ff0000">*</span></label>
		</div>
		<div class="md:w-1/2 px-3 mb-6 md:mb-0">
		  <input class="w-64 bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" aria-describedby="user_avatar_help" id="upload_file" style="width: 300px;" autocomplete="off"
			type="file" name="upload_file" title="Upload WAV file" accept=".wav" placeholder="Please upload only WAV files" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="Upload WAV files">

			 <div id="file_error" class="error-message"></div>
			<span class="text-sm text-sky-500">The file must be wav,PCM Encoded,16 Bits,mono,at 8000HZ.</span>

		</div>

        </div>  




	<div class="-   mx-3 md:flex mb-6">
        <!-- Company Name input -->
           <div class="md:w-1/2 px-3 mb-6 md:mb-0" style="text-align: right; line-height: 42px;">
                <label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">
                    Company Name<span style="color: #ff0000">*</span>
                </label>
        </div>
        <div class="md:w-1/2 px-3 mb-6 md:mb-0">

                <input name="company_name" autocomplete="off"
                    class="w-64 bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" id="company_name" title="Company_name/ Person/ Institute"
                    type="text" placeholder="Company name/ Person/ Institute" minlength = "3" maxlength="10" style="width: 300px;">

                 <div id="company_name_error" class="error-message"></div>
		<span class="text-sm text-sky-500">Min length: 3 & Max length: 10</span>

        </div>
            </div>



	<!-- Location -->
            <div class="-   mx-3 md:flex mb-6">
            <!-- Location input -->
            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">
                    Location<span style="color: #ff0000">*</span>
                </label>
                </div>
                <div class="md:w-1/2 px-3 mb-6 md:mb-0">

                        <select name="location" id="location" class="w-full bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" style="width: 300px; float: left" autocomplete="off">
                                <option value="" disabled selected>Select a Location</option>

                        </select>
                <div id="location_error" class="error-message" style = "clear: both;"></div> 
			
                </div>
            </div>


	<!-- Language Code -->
            <div class="-   mx-3 md:flex mb-6">
            <!-- language code input -->
            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">
                    Language Code<span style="color: #ff0000">*</span>
                </label>
                </div>
                <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                        <select name="language_code" id="language_code" class="w-full bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" style="width: 300px; float: left" autocomplete="off">
                                <option value="" disabled selected>Select a Language</option> 
                        </select>       
			<div id="language_code_error" class="error-message" style = "clear: both;"></div>
                </div>
            </div>


	<div class="- mx-3 flex mb-6">
           <!--  type dropdown -->
           <div class="md:w-1/2 px-3 mb-6 md:mb-0">
			<label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">
                    Type<span style="color: #ff0000">*</span>
                </label>
                        </div>
                <div class="md:w-1/2 px-3 mb-6 md:mb-0">
				<select name="type" id="type" class="w-full bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" style="width: 300px; float: left" autocomplete="off">
				<option value="" disabled selected>Select a Type</option>
                                <option value="TRANS">TRANSACTION</option>
                                <option value="INFOR">INFORMATION</option>
                                <option value="PROMO">PROMOTION</option>
                        </select>
         <div id="type_error" class="error-message" style = "clear: both;"></div> 
   </div>
  </div>



	<!-- Context -->
        <div class="-   mx-3 md:flex mb-6">
	<!-- Context input -->
           <div class="md:w-1/2 px-3 mb-6 md:mb-0" style="text-align: right; line-height: 42px;">
                <label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">
                    Context<span style="color: #ff0000">*</span>
                </label>
	</div>
	<div class="md:w-1/2 px-3 mb-6 md:mb-0">
		
		<input type="hidden" name="context" id="context" value="">
                 <input name="context_value" autocomplete="off"
                    class="w-64 bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" id="context_value" title="context" readonly
                     minlength = "5" maxlength="15" style="width: 300px;">  

		 <div id="context_error" class="error-message"></div>
		@if($message = Session::get('error'))
            <span id = "exit-context" class="text-red-500">{{ $message }}</span>
        	@endif

        </div>
            </div>



	<div class="-   mx-3 md:flex mb-6">
        <!-- Remarks input -->
           <div class="md:w-1/2 px-3 mb-6 md:mb-0" style="text-align: right; line-height: 42px;">
                <label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">
                    Remarks<span style="color: #ff0000">*</span>
                </label>
        </div>
        <div class="md:w-1/2 px-3 mb-6 md:mb-0">

                <textarea name="remarks"
                    class="w-64 bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" id="remarks" title="Remarks"
                    type="text" placeholder="Maximum 50 characters" maxlength = "50" style="width: 300px; height:100px;" autocomplete="off"></textarea>

                 <div id="remarks_error" class="error-message"></div>

        </div>
            </div>




    	
	<div id="file_type_error" style= "align-items: center; justify-content: center; text-align: center;" class="error-message" style="color: red;"></div>
	
	
	 <!-- Buttons (Clear, Submit, Cancel) -->
        <div class="-   mx-3 md:flex mb-6">
	    <!-- Clear button -->
            <div class="md:w-1/2 px-3 mb-4 md:mb-0">
                    <button type="button" id="clear_btn"
                    class=" md:w-full mt-7 bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full">
                    Clear
                </button></a>
            </div>
	    <!-- Submit button -->
            <div class="md:w-1/2 px-3 mb-4 md:mb-0">
                <button type="submit" name="submit_btn" id="submit_btn"
                    class="md:w-full mt-7 bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full">
                    Submit
                </button>
            </div>
	    <!-- Cancel button -->
            <div class="md:w-1/2 px-3 mb-4 md:mb-0">
                    <button type="button" id="cancel_btn"
                    class=" md:w-full mt-7 bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full"
		onclick="window.location.reload();">
                    Cancel
                </button></a>
            </div>
        </div>
</div>


<!-- exit context model if the free channel is not available -->
<div class="modal fade bs-example-modal-md" id="context-exit" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="context-exit" aria-hidden="true" data-backdrop="false" style=" position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 600px;">
    <div class="modal-dialog">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-header" style="border-top: 4px inset red; text-align: center; style="background-color: green">
                <h5 class="modal-title" style="color: green; text-align: center;">Confirm Context</h5>
            </div>
            <div class="modal-body">
		<p>The Context <span id="exit_context" class="exit_context" style="color: red;"></span> is already available.</p>
                <p>Do you want to replace the exiting prompt <span id="exit_prompt" class="exit_prompt" style="color: red;"></p>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa;">
		<button type="button" class=" md:w-full bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal">Yes</button>
                <button type="button" class=" md:w-full bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full button0" onclick="location.reload();">No</button>
            </div>
        </div>
    </div>
</div>



 <script>
document.addEventListener('DOMContentLoaded', function () {
    var companyNameInput = document.getElementById('company_name');

    companyNameInput.addEventListener('input', function (event) {
        var inputValue = event.target.value;
        // Use a regular expression to remove special characters
        var filteredValue = inputValue.replace(/[^a-zA-Z0-9]/g, '');

        if (inputValue !== filteredValue) {
            // Update the input value to remove invalid characters
            event.target.value = filteredValue;
        }
    });
});
</script> 



<!-- javascript function for clear button -->
		<script>
  			// Wait for the document to be ready
  			$(document).ready(function() {
    				// Attach a click event handler to the "Clear" button
    				$('#clear_btn').click(function() {
      				var file = document.getElementById('upload_file');
      				var context = document.getElementById('context_value');
				var remarks = document.getElementById('remarks');
				var company = document.getElementById('company_name');
				var language = document.getElementById('language_code');
				var location = document.getElementById('location');
				var type = document.getElementById('type');
      				// Clear the input fields by setting their values to an empty string
      				file.value = '';
      				context.value = '';
				remarks.value = '';
				company.value = '';
				language.value = '';
				location.value = '';
				type.value = '';
				$('#file_error').text('');
				$('#company_name_error').text('');
				$('#location_error').text('');
				$('#language_code_error').text('');
				$('#type_error').text('');
				$('#exit-context').text('');
				$('#remarks_error').text('');
				$('#file_type_error').text('');
    				});
  			});
		</script>


<!-- javascript function for display the mp3 file validation error -->
<!--	<script>
    		// Wait for the document to be ready
    		$(document).ready(function () {
        		// Attach a change event handler to the file input
        		$('#upload_file').change(function () {
            		var fileInput = this;
            		var fileError = document.getElementById("file_type_error");

            		// Check if the selected file is not an MP3
            		if (fileInput.files.length > 0) 
			{
                		var file = fileInput.files[0];
                		if (file.type !== 'audio/mpeg') 
				{
                    			// Display the error message
                    			fileInput.value = ''; // Clear the input
                    			fileError.textContent = "Please upload a valid MP3 file.";
                    			fileError.style.color = "red";
                		} 
				else 
				{
                    			fileError.textContent = ''; // Clear any previous error message
                		}
            		}
        		});
    		});
	</script> -->



<script>
    // Wait for the document to be ready
    $(document).ready(function () {
        // Attach a change event handler to the file input
        $('#upload_file').change(function () {
            var fileInput = this;
            var fileError = document.getElementById("file_type_error");

            // Check if the selected file is not in the required format
            if (fileInput.files.length > 0) {
                var file = fileInput.files[0];
                if (file.type !== 'audio/wav') {
                    // Display the error message for incorrect format
                    displayErrorMessage("Uploaded file format is incorrect. It should be in the format 'wav, PCM Encoded, 16 Bits, mono, at 8000Hz.'");
                    return; // Exit the function
                }

                // Check for PCM encoding format
                var reader = new FileReader();
                reader.onload = function (e) {
                    var buffer = e.target.result;
                    if (!isPCMFormat(buffer)) {
                        // Display the error message for incorrect PCM encoding
                        displayErrorMessage("Uploaded WAV file is not in PCM encoding format.");
                        return; // Exit the function
                    }

                    // Check for 16 Bits, mono, at 8000Hz
                    if (!is16BitMono8000Hz(buffer)) {
                        // Display the error message for incorrect audio properties
                        displayErrorMessage("Uploaded WAV file does not meet the criteria of 16 Bits, mono, at 8000Hz.");
                        return; // Exit the function
                    }

                    // All criteria are met; clear any previous error message
                    fileError.textContent = '';
                };
                reader.readAsArrayBuffer(file);
            } else {
                fileError.textContent = ''; // Clear any previous error message
            }
        });
    });

    function displayErrorMessage(message) {
        var fileError = document.getElementById("file_type_error");
        var fileInput = document.getElementById("upload_file");
        fileInput.value = ''; // Clear the input
        fileError.textContent = message;
        fileError.style.color = "red";
    }

    function isPCMFormat(buffer) {
        var view = new DataView(buffer);
        var audioFormat = view.getUint16(20, true); // Byte 20 contains audio format
        return audioFormat === 1; // PCM format is usually represented as 1
    }

    function is16BitMono8000Hz(buffer) {
        var view = new DataView(buffer);
        var sampleSize = view.getUint16(34, true); // Byte 34 contains bits per sample
        var numChannels = view.getUint16(22, true); // Byte 22 contains number of channels
        var sampleRate = view.getUint32(24, true); // Byte 24 contains sample rate
        return sampleSize === 16 && numChannels === 1 && sampleRate === 8000;
    }
</script>



<!-- javascript function for clear the error message for file and context -->
	<script>
    		// Wait for the document to be ready
    		$(document).ready(function () {
        		// Attach an input event handler to the file input
        		$('#upload_file').on('input', function () {
            			var fileError = document.getElementById("file_error");
				var companyError = document.getElementById("company_name_error");
				var locationError = document.getElementById("location_error");
				var languageError = document.getElementById("language_code_error");
				var typeError = document.getElementById("type_error");
	    			var contextError = document.getElementById("context_error");
				var remarksError = document.getElementById("remarks_error");
                                var filetypeError = document.getElementById("file_type_error");
				var exit_context = document.getElementById("exit-context");
            			fileError.textContent = ''; // Clear the error message
				companyError.textContent = '';
				locationError.textContent = '';
				languageError.textContent = '';
                                typeError.textContent = '';
	    			contextError.textContent = '';
				remarksError.textContent = '';
				 filetypeError.textContent = '';
				//exit_context.textContent = '';


        		});

			$('#context').on('input', function () {
				var fileError = document.getElementById("file_error");
                                var companyError = document.getElementById("company_name_error");
                                var locationError = document.getElementById("location_error");
                                var languageError = document.getElementById("language_code_error");
                                var typeError = document.getElementById("type_error");
                                var contextError = document.getElementById("context_error");
                                var remarksError = document.getElementById("remarks_error");
                                var filetypeError = document.getElementById("file_type_error");
                                var exit_context = document.getElementById("exit-context");
                                fileError.textContent = ''; // Clear the error message
                                companyError.textContent = '';
                                locationError.textContent = '';
                                languageError.textContent = '';
                                typeError.textContent = '';
                                contextError.textContent = '';
                                remarksError.textContent = '';
                                 filetypeError.textContent = '';
                                //exit_context.textContent = '';

				

        		});

			$('#remarks').on('input', function () {
				var fileError = document.getElementById("file_error");
                                var companyError = document.getElementById("company_name_error");
                                var locationError = document.getElementById("location_error");
                                var languageError = document.getElementById("language_code_error");
                                var typeError = document.getElementById("type_error");
                                var contextError = document.getElementById("context_error");
                                var remarksError = document.getElementById("remarks_error");
                                var filetypeError = document.getElementById("file_type_error");
                                var exit_context = document.getElementById("exit-context");
                                fileError.textContent = ''; // Clear the error message
                                companyError.textContent = '';
                                locationError.textContent = '';
                                languageError.textContent = '';
                                typeError.textContent = '';
                                contextError.textContent = '';
                                remarksError.textContent = '';
                                 filetypeError.textContent = '';
                                //exit_context.textContent = '';


                        });

			$('#company_name').on('input', function () {
				var fileError = document.getElementById("file_error");
                                var companyError = document.getElementById("company_name_error");
                                var locationError = document.getElementById("location_error");
                                var languageError = document.getElementById("language_code_error");
                                var typeError = document.getElementById("type_error");
                                var contextError = document.getElementById("context_error");
                                var remarksError = document.getElementById("remarks_error");
                                var filetypeError = document.getElementById("file_type_error");
                                var exit_context = document.getElementById("exit-context");
                                fileError.textContent = ''; // Clear the error message
                                companyError.textContent = '';
                                locationError.textContent = '';
                                languageError.textContent = '';
                                typeError.textContent = '';
                                contextError.textContent = '';
                                remarksError.textContent = '';
                                 filetypeError.textContent = '';
                                //exit_context.textContent = '';

                        });

			$('#language_code').on('input', function () {
				var fileError = document.getElementById("file_error");
                                var companyError = document.getElementById("company_name_error");
                                var locationError = document.getElementById("location_error");
                                var languageError = document.getElementById("language_code_error");
                                var typeError = document.getElementById("type_error");
                                var contextError = document.getElementById("context_error");
                                var remarksError = document.getElementById("remarks_error");
                                var filetypeError = document.getElementById("file_type_error");
                                var exit_context = document.getElementById("exit-context");
                                fileError.textContent = ''; // Clear the error message
                                companyError.textContent = '';
                                locationError.textContent = '';
                                languageError.textContent = '';
                                typeError.textContent = '';
                                contextError.textContent = '';
                                remarksError.textContent = '';
                                 filetypeError.textContent = '';
                                //exit_context.textContent = '';

                        });


			$('#location').on('input', function () {
				var fileError = document.getElementById("file_error");
                                var companyError = document.getElementById("company_name_error");
                                var locationError = document.getElementById("location_error");
                                var languageError = document.getElementById("language_code_error");
                                var typeError = document.getElementById("type_error");
                                var contextError = document.getElementById("context_error");
                                var remarksError = document.getElementById("remarks_error");
                                var filetypeError = document.getElementById("file_type_error");
                                var exit_context = document.getElementById("exit-context");
                                fileError.textContent = ''; // Clear the error message
                                companyError.textContent = '';
                                locationError.textContent = '';
                                languageError.textContent = '';
                                typeError.textContent = '';
                                contextError.textContent = '';
                                remarksError.textContent = '';
                                 filetypeError.textContent = '';
                                //exit_context.textContent = '';

                        });

			$('#type').on('input', function () {
				var fileError = document.getElementById("file_error");
                                var companyError = document.getElementById("company_name_error");
                                var locationError = document.getElementById("location_error");
                                var languageError = document.getElementById("language_code_error");
                                var typeError = document.getElementById("type_error");
                                var contextError = document.getElementById("context_error");
                                var remarksError = document.getElementById("remarks_error");
                                var filetypeError = document.getElementById("file_type_error");
                                var exit_context = document.getElementById("exit-context");
                                fileError.textContent = ''; // Clear the error message
                                companyError.textContent = '';
                                locationError.textContent = '';
                                languageError.textContent = '';
                                typeError.textContent = '';
                                contextError.textContent = '';
                                remarksError.textContent = '';
                                 filetypeError.textContent = '';
                                //exit_context.textContent = '';

                        });


    		});
	</script>

 </form>
    
</div>

</html>

@endsection
  






