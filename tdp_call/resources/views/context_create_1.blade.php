<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')
<style>
.context_success{
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    z-index: 99999;
}
</style>

<!-- HTML section begins -->
<html>

<style>   <!-- CSS styles for media queries -->
/*    .modal.fade .modal-dialog {
        transform: translate(0, -175%) !important;
    } */
// }
</style>


<script src="https://code.jquery.com/jquery-3.2.1.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>  

<script>
        /* JavaScript function for form validation */
        function validateForm() {
            var fileInput = document.getElementById("upload_file");
            var fileError = document.getElementById("file_error");
            var context = document.getElementById("context").value;
            var contextError = document.getElementById("context_error");
	    var remarks = document.getElementById("remarks").value;
	   var remarksError =  document.getElementById("remarks_error");
            var flag = true;

            if (fileInput.files.length === 0 || context === "" || remarks === "") {
                if (fileInput.files.length === 0) {
                    fileError.textContent = "MP3 File is required.";
                    fileError.style.color = "red";
                }

                if (context === "") {
                    contextError.textContent = "Context is required.";
                    contextError.style.color = "red";
                }
		
		if (remarks === "") {
                    remarksError.textContent = "Remarks is required.";
                    remarksError.style.color = "red";
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

<style>
.card { border: 0px solid rgba(0,0,0,.125) !important; }
.card-body { padding: 0rem !important; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@if($message = Session::get('success'))
<script>
    // $(document).ready(function() {
        $('#success_msg').modal('show');
	$('#success_msg').css('display', 'contents');

        /* // Add a click event listener to the document body
        $('body').on('click', function(e) {
            // Check if the click target is outside of the modal
            if (!$('#success_msg').is(e.target) && $('#success_msg').has(e.target).length === 0) {
                // Close the modal if the click is outside
                $('#success_msg').modal('hide');
            }
        }); */
    //});
</script>

<!-- Success pop up HTML -->
<div class="modal fade bs-example-modal-md" id="success_msg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="false" style=" position: fixed; left: 50%; top: 50%;position: fixed; display: contents; 
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    background: rgba(0,0,0,0.60) url('https://yj360.in/obd_call_neron/public/css/loader.gif') no-repeat center center;
    z-index: 99999;">
  <div class="modal-dialog">
      <div class="modal-content" id='mdl' style="min-height: 320px;">
        <a href="#close-modal" rel="modal:close" class="close-modal ">Close</a>
        <center>
        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#28a745" class="bi bi-check-circle-fill" viewBox="0 0 16 16" style="margin-top:25px;">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
</svg>
<br>
<h3 style="color:green; font-size:22px; margin-top:10px;"><b>SUCCESS</b></h3>
        <br>
	<p style="margin: 15px;"><b>{!! $message !!}</b></p>
        <br>
	</center>
        <a href="#close-modal" rel="modal:close" class="btn btn-success" data-dismiss="modal" aria-label="Close" style="margin-top:40px; font-weight: bold;">Close</a>
    </div>
  </div>
</div>  
<!-- Success pop up ends -->
@endif

<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">
    <div class="px-3" style="background-color: #FFF; height: 50px; padding-top: 8px; margin-bottom: 20px;">
        <h2 class="text-2xl font-medium" style="font-weight: 100;">Create Prompt</h2>
    </div>

    <form action="{{ route('prompt_create') }}" method="POST" name="campaign_form" id="campaign_form" onsubmit="return validateForm()" enctype="multipart/form-data">

        @csrf

	<!--Prompt File Upload and Date -->
       <div class="- mx-3 flex mb-6">
	    <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">Upload prompt<span style="color: #ff0000">*</span> <span style="font-size: smaller; color: blue;">(MP3 file)</span></label>
		</div>
		<div class="md:w-1/2 px-3 mb-6 md:mb-0">
		  <input class="w-64 bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" aria-describedby="user_avatar_help" id="upload_file" style="width: 300px;" 
			type="file" name="upload_file" title="Upload MP3 file" accept=".mp3" placeholder="Please upload only MP3 files" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="Upload MP3 files">

			 <div id="file_error" class="error-message"></div>

		</div>

        </div>  


	

	<!-- Context, Caller ID and Time Interval -->
        <div class="-   mx-3 md:flex mb-6">
	<!-- Context input -->
           <div class="md:w-1/2 px-3 mb-6 md:mb-0" style="text-align: right; line-height: 42px;">
                <label class="uppercase tracking-wide text-black mb-2" for="company" style="text-align: right;">
                    Context<span style="color: #ff0000">*</span>
                </label>
	</div>
	<div class="md:w-1/2 px-3 mb-6 md:mb-0">

                <input name="context" 
                    class="w-64 bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" id="context" title="Context Name"
                    type="text" placeholder="Min length: 5 & Max length: 15" minlength = "5" maxlength="15" style="width: 300px;">

		 <div id="context_error" class="error-message"></div>
		@if($message = Session::get('error'))
            <span id = "exit-context" class="text-red-500">{{ $message }}</span>
        	@endif

        </div>
            </div>



	<div class="-   mx-3 md:flex mb-6">
        <!-- Remarks input -->
           <div class="md:w-1/2 px-3 mb-6 md:mb-0" style="text-align: right; line-height: 42px;">
                <label class="uppercase tracking-wide text-black mb-2" for="company" style="text-align: right;">
                    Remarks<span style="color: #ff0000">*</span>
                </label>
        </div>
        <div class="md:w-1/2 px-3 mb-6 md:mb-0">

                <textarea name="remarks"
                    class="w-64 bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" id="remarks" title="Remarks"
                    type="text" placeholder="Remarks" minlength = "5" maxlength="50" style="width: 300px; height:100px;"></textarea>

                 <div id="remarks_error" class="error-message"></div>
                @if($message = Session::get('error'))
            <span id = "exit-context" class="text-red-500">{{ $message }}</span>
                @endif

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

<script>
document.addEventListener('DOMContentLoaded', function () {
    var contextInput = document.getElementById('context');

    contextInput.addEventListener('input', function (event) {
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
	 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
		<script>
  			// Wait for the document to be ready
  			$(document).ready(function() {
    				// Attach a click event handler to the "Clear" button
    				$('#clear_btn').click(function() {
      				var file = document.getElementById('upload_file');
      				var context = document.getElementById('context');
				var remarks = document.getElementById('remarks');
      				// Clear the input fields by setting their values to an empty string
      				file.value = '';
      				context.value = '';
				remarks.value = '';
    				});
  			});
		</script>


<!-- javascript function for display the mp3 file validation error -->
	<script>
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
	</script>


<!-- javascript function for clear the error message for file and context -->
	<script>
    		// Wait for the document to be ready
    		$(document).ready(function () {
        		// Attach an input event handler to the file input
        		$('#upload_file').on('input', function () {
            			var fileError = document.getElementById("file_error");
	    			var contextError = document.getElementById("context_error");
				var remarksError = document.getElementById("remarks_error");
                                var filetypeError = document.getElementById("file_type_error");
				var exit_context = document.getElementById("exit-context");
            			fileError.textContent = ''; // Clear the error message
	    			contextError.textContent = '';
				remarksError.textContent = '';
				 filetypeError.textContent = '';
				exit_context.textContent = '';

        		});

			$('#context').on('input', function () {
            			var fileError = document.getElementById("file_error");
            			var contextError = document.getElementById("context_error");
				var remarksError = document.getElementById("remarks_error");
	    			var filetypeError = document.getElementById("file_type_error");
                                var exit_context = document.getElementById("exit-context");
            			fileError.textContent = ''; // Clear the error message
            			contextError.textContent = '';
				remarksError.textContent = '';
	    			filetypeError.textContent = '';
				exit_context.textContent = '';
				

        		});

			$('#remarks').on('input', function () {
                                var fileError = document.getElementById("file_error");
                                var contextError = document.getElementById("context_error");
                                var remarksError = document.getElementById("remarks_error");
                                var filetypeError = document.getElementById("file_type_error");
                                var exit_context = document.getElementById("exit-context");
                                fileError.textContent = ''; // Clear the error message
                                contextError.textContent = '';
                                remarksError.textContent = '';
                                filetypeError.textContent = '';
                                exit_context.textContent = '';


                        });


    		});
	</script>

 </form>
    
</div>

</html>

@endsection
  






