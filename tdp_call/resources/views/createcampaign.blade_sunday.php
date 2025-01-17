<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')
<div id='loader' style="display: none;"></div>

<!-- HTML section begins -->
<html>


<style>   <!-- CSS styles for media queries -->
    .modal.fade .modal-dialog {
        transform: translate(0, -175%) !important;
    }
    .modal-footer button 
    {
        width: calc(50% - 10px); /* Set the width to 50% minus some padding */
        margin: 0 5px; /* Add some margin to separate the buttons */
    }
    .bg-gray-900{
    background-color: #00ee5a !important;
    color:black !important;
}

.bg-gray-500{
    background-color: lawngreen !important;
    color:black !important;
}
.modal-content p {
    word-wrap: break-word !important; /* Force long words to break and wrap within the container */
    white-space: normal !important; /* Ensure that the text breaks to the next line if necessary */
    overflow-wrap: break-word !important; /* Another way to break words if they are too long */
    text-align: center !important; /* Optional: center-align the text inside the modal */
}
</style>


<style>

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

  .button1 
  {
    background-color: green;
    color: white;
    width: 40px;
    height: 40px;
  }


  .button3 
  {
    background-color: red;
    color: white;
    width: 40px;
    height: 40px;
  }


  .button4 
  {
    background-color: green;
    color: white;
    width: 40px;
    height: 40px;
  }

  .bg-green-800 
  {
    background-color: green;
  }


  .download-sample
  {
    background-color: green; /* Replace with your desired background color */
    padding: 8px 10px; /* Adjust the padding as needed */
    color: white;
  }


  #error_message 
  {
    align-items: center; /* Vertical centering */
    justify-content: center; /* Horizontal centering */
    text-align: center; /* Center-align text */
  }


  .preloader-wrapper 
  {
      display: flex;
      justify-content: center;
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
      min-width: 128px;
      min-height: 128px;
       z-index: 99999;
       /* background-color:#f27878; */
       position: fixed;
  }

</style>


<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>  -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
    

<!-- JavaScript and jQuery libraries -->
<script>
    $(function() 
    {
        init();
    });


function chooseFile() {
      document.getElementById('upload_file').value = '';
    }

    function init() 
    {
        document.getElementById('upload_file').addEventListener('change', handleFileSelect, false);
    }

    function handleFileSelect(event) {
    var fileInput = document.querySelector('#upload_file');
    var fileName = fileInput.value;
    var extn = fileName.split('.').pop().toLowerCase(); // Ensure extension is lowercase for comparison
    var errorElement = document.getElementById('error_message');

    // Clear the error message initially
    errorElement.textContent = '';
    
    if (fileName && extn !== '') {
        if (extn === 'csv') {
            var fd = new FormData(document.getElementById("campaign_form"));
            fd.append('file_type', extn);
            console.log(fd);
            console.log(new Date().toLocaleString());

            var reader = new FileReader();
            reader.onload = function(e) {
                var contents = e.target.result;
                var lines = contents.split('\n');
                var columnCount = lines[0].split(',').length;
                console.log("Column count: " + columnCount);

                var expectedColumns = null;
                var errorMessage = '';

                if ($("#non_customised").is(":checked")) {
                    console.log("non_customised");
                    expectedColumns = 1;
                    errorMessage = 'File should contain only mobile numbers.';
                } else if ($("#customised").is(":checked")) {
                    console.log("customised");
                    expectedColumns = 2;
                    errorMessage = 'File should contain mobile number and audio URL.';
                } else {
                    errorMessage = 'Invalid file format selection. Please upload a valid file.';
                }

                // Validate the column count based on selection
                if (expectedColumns !== null && columnCount !== expectedColumns) {
                    // Show error if column count doesn't match
                    errorElement.textContent = errorMessage;
                    errorElement.style.color = 'red';
                    fileInput.value = ''; // Clear the file input
                } else {
                    // File is valid, proceed with handling it
                    handleFile(fd);
                }
            };
            reader.readAsText(event.target.files[0]);

        } else {
            // Not a CSV file
            errorElement.textContent = 'Please upload a valid CSV file!';
            errorElement.style.color = 'red';
            fileInput.value = ''; // Clear the file input
        }

    } else {
        // No file or invalid extension
        errorElement.textContent = 'Please upload a valid CSV file!';
        errorElement.style.color = 'red';
        fileInput.value = ''; // Clear the file input
    }

    // Clear the error message when the input changes
    fileInput.addEventListener("input", function () {
        errorElement.textContent = ""; // Clear the error message
    });
}

    function handleFile(fd)
    {
        $("#loader").show();
        $(".preloader-wrapper").show();

        $.ajax({
            method: 'POST',
            url: "{{ url('process-xlsx-to-csv') }}",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            data: fd,
                contentType: false,
                processData: false,
                success: function (response_msg) 
                {
                    if(response_msg.csv_file)
                    {
                        console.log(response_msg.csv_file)
                        console.log(new Date().toLocaleString());
                        call_remove_duplicate_invalid(response_msg.csv_file);
                    }
      
                    if(response_msg.error)
                    {
                        console.log("!!!!");
                        //console.log(error);
                        $("#loader").hide();
                        $(".preloader-wrapper").hide();
                        console.log("Wrong file");

                        var errorElement = document.getElementById('error_message');
                        errorElement.textContent = "Please upload a valid Excel or CSV file!";
                        errorElement.style.color = 'red';
                        fileInput.value = '';

                        fileInput.addEventListener("input", function () 
                        {
                            errorElement.textContent = ""; // Clear the error message
                        });
                    }
                },
                error: function (xhr, status, error) 
                {
                    console.log("!!!!");
                    console.log(error);
                    //$("#loader").hide();
                    console.log("Wrong file");
  
                    var errorElement = document.getElementById('error_message');
                    errorElement.textContent = "Error: Please upload a valid Excel, CSV, or Text file!";
                    errorElement.style.color = 'red';
                    fileInput.value = '';

                    errorElement.addEventListener("input", function () 
                    {
                        errorElement.textContent = ""; // Clear the error message
                    });

                }
            })
            
    }


    /* Function to call remove duplicate and invalid numbers */
    function call_remove_duplicate_invalid(dataArray) 
    {
	    console.log("Excel records insertion started");
        console.log(dataArray);

        var userMasterId = {{ auth()->user()->user_master_id }};

        var campaignType = $('input[name="campaign_type"]:checked').val();
        console.log(campaignType);

        $("#loader").show();
        $('.preloader-wrapper').show();
        $.ajax({
            method: 'POST',
            url: "{{ url('process-mobile-numbers') }}",
            data: { validateMobno: 'validateMobno', mobno: dataArray, campaign_type: campaignType},
            success: function (response_msg) 
            {

                console.log(new Date().toLocaleString());
                let invalidCount = response_msg.invalidCount;
                let duplicateCount = response_msg.duplicateCount;
                let totalCount = response_msg.totalCount;
                let validCount = response_msg.validCount;
                let available_credits = response_msg.available_credits;
                console.log("available_credits:" +  available_credits);
                console.log("valid count:" + validCount);
                console.log("duplicate count:" + duplicateCount);
                console.log("total count:" + totalCount);
                console.log("invalid count:" + invalidCount);

                if (validCount > available_credits && userMasterId == 2)
                {
                    // Show the confirmation modal
                    $('.preloader-wrapper').hide();
                    $("#loader").hide();

                    $('#upload_file').val('');
                    $('#context').val('');
                    $('#txt_list_mobno').val('');
                    $('#retry_count').val('0');

                    $("#alertModal").modal("show");

		                $('body').on('click', function(e) 
                    {
                      // Check if the click target is outside of the modal
                      if (!$('#alertModal').is(e.target) && $('#alertModal').has(e.target).length === 0) 
                      {
                          // Close the modal if the click is outside
                          $('#alertModal').modal('hide');
                      }
                    });		

                    return; // Prevent further execution
                }

                if (invalidCount > 0 || duplicateCount > 0) 
                {
                
                      // Update the total number count in the modal
                      $("#totalNumberCount").text(response_msg.totalCount);

                      // Update the valid number count in the modal
                      $("#validNumberCount").text(response_msg.validCount);

                      // Update the invalid number count in the modal
                      $("#invalidNumberCount").text(response_msg.invalidCount);

                      // Update the duplicate number count in the modal
                      $("#duplicateNumberCount").text(response_msg.duplicateCount);

                      // Show the confirmation modal
                      $("#confirmationModal").modal("show");

                      // Add event listener to the "No" button in the modal
                      $("#confirmationModal .button0").on("click", function () 
                      {
                            // Reload the page when "No" is clicked
                            window.location.reload();
                      });
                
                }

                $("#loader").hide();
                $(".preloader-wrapper").hide();
            },
            error: function (response_msg, status, error) 
            {
		            console.log(new Date().toLocaleString());
		            console.log('Error:', status, error);
		            // Set the error message text
	    	        const errorText = "An error occurred. Please try again.";

    		        // Set the error message in the modal
    		        $('#errorText').text(errorText);

                // Show the error modal
                $('#errorModal').modal('show');
                $("#loader").hide();
                $(".preloader-wrapper").hide();
            }
        });
    }

</script>

<script>

  document.addEventListener("DOMContentLoaded", function() 
  {
    var retryCountSelect = document.getElementById("retry_count");
    var retryTimeInput = document.getElementById("retry_time");

    retryCountSelect.addEventListener("change", function() 
    {
        var selectedValue = parseInt(retryCountSelect.value);
        if (selectedValue === 0) 
        {
            retryTimeInput.value = "0";
            retryTimeInput.disabled = true;
        } 
        else 
        {
            retryTimeInput.value = "";
            retryTimeInput.disabled = false;
        }
    });

    // Trigger change event when page loads if retry_count is 0
    if (parseInt(retryCountSelect.value) === 0) 
    {
        var event = new Event("change");
        retryCountSelect.dispatchEvent(event);
    }

    // Event listener for retry_time input
    retryTimeInput.addEventListener("input", function() 
    {
        var retryTimeInt = parseInt(retryTimeInput.value);
        var retryTimeError = document.getElementById("retry_time_error");
        
        // Clear previous error message
        retryTimeError.textContent = "";

        // Validate retry time interval
        if (retryTimeInt < 900) 
        {
            retryTimeError.textContent = "Minimum retry time interval should be 900.";
            // retryTimeError.style.color = "red";
        } 
        else if (retryTimeInt > 3600) 
        {
            retryTimeError.textContent = "Maximum retry time interval should be 3600.";
            // retryTimeError.style.color = "red";
        }
        retryTimeError.style.color = "red";
    });
});

</script>


<script>

$(document).ready(function () 
{
    // Get the context dropdown element
    var contextDropdown = $('#context');
   
    // Get the customised and non-customised radio buttons
    var customisedRadio = $('#customised');
    var nonCustomisedRadio = $('#non_customised');
    // alert(JSON.stringify(nonCustomisedRadio));

    // Call context_list function when the page loads
    context_list();

    // Add change event listener to customised radio button
    customisedRadio.on('change', function () {
        console.log("Customised radio button changed");
        context_list();
        // Add your logic here to handle the change
    });

    // Add change event listener to non-customised radio button
    nonCustomisedRadio.on('change', function () {
        console.log("Non-customised radio button changed");
        console.log('hellllllllllllllllllllllllllllllllllo');
        context_list();
        // Add your logic here to handle the change
    });

    function context_list()
    {
        var playButton = $('#playButton');
        playButton.hide();
       
        // Get the audio element
        var audioPlayer = $('#audioPlayer');

        var isAudioPlaying = false; 
        contextDropdown.on('change', function () 
        {
            console.log("!!!!!!!!!!");

	        var selectedContext = contextDropdown.val();
            if (selectedContext !== '') 
            {
	            isAudioPlaying = false;
                playButton.html('Play');
                document.getElementById("playButton").style.backgroundColor = "#121827";
	
	            // Retrieve the audio URL for the selected context using an AJAX request
                $.ajax({
                    url: "{{ route('get_audio_by_context') }}",
                    type: 'GET',
                    data: { context: selectedContext },
                    success: function (data) 
                    {
                        console.log(data.audio_url);
                        if (data.audio_url != "") 
                        {
                            // Show the play button if a context is selected
                            playButton.show();
                            console.log("@@@");
                            // Set the audio source and play
                            audioPlayer.attr('src', data.audio_url);
                            //   audioPlayer.get(0).play();
                        }
                    },
                    error: function (xhr, status, error) 
                    {
                        // Handle the error here
                        console.error(xhr.responseText);
                    }
                });
            }        
	        else 
            {
                // Hide the play button if no context is selected
                playButton.hide();
		        // Pause and reset the audio player
                audioPlayer.get(0).pause();
                audioPlayer.attr('src', '');
            }
        });


	    playButton.on('click', function () 
        {
            console.log("play button click");
            if (audioPlayer.attr('src')) 
            {
                if (isAudioPlaying) 
                {
                    audioPlayer.get(0).pause();
                    isAudioPlaying = false;
                    playButton.html('Play');
		            document.getElementById("playButton").style.backgroundColor = "#121827";
                } 
                else 
                {	
		            audioPlayer.get(0).currentTime = 0;
                    audioPlayer.get(0).play();
                    isAudioPlaying = true;
                    playButton.html('Stop');
		            document.getElementById("playButton").style.backgroundColor = "#FF0000";
                }
            }
        });


        audioPlayer.on('ended', function () 
        {
            // Audio has finished playing, toggle the button back to "Play"
            isAudioPlaying = false;
            playButton.html('Play');
            document.getElementById("playButton").style.backgroundColor = "#121827";
        });


        var campaign_type = $('input[name="campaign_type"]:checked').val();

        $.ajax({
            url: "{{ route('get_context') }}", // Replace 'get_context' with the name of your route
            type: 'GET',
            data: {
                campaign_type: campaign_type // Replace 'campaign_type' with the actual value you want to pass
            },
            success: function (contexts) 
            {
                console.log(contexts.length);
                if (contexts.length > 0) 
                {
                    console.log(contexts);
                
                    // Clear existing options
                    contextDropdown.empty();

		            // Add the default option
                    contextDropdown.append($('<option>', 
                    {
                        value: '',
                        text: 'Select a Context',
                        disabled: 'disabled',
                        selected: 'selected'
                    }));
		
                    // Add each context as an option
                    contexts.forEach(function (context) 
                    {
                        contextDropdown.append($('<option>', 
                        {
                            value: context.context,
                            text: context.context,
                        
                        }));
                    });
                }
                else 
                {
                    // No data available, display a message
                    contextDropdown.empty();
                    contextDropdown.append($('<option>', {
                        value: '',
                        text: 'No data available',
                        disabled: 'disabled',
                        selected: 'selected'
                    }));
                }
            },
            error: function (xhr, status, error) 
            {
                // Handle the error here
                console.error(xhr.responseText);
            }
        });
    }
});

</script> 


<script>

    /* JavaScript function for form validation */
    // function validateForm()
    // {

    //     console.log('validateForm() function is being called.');

    //     var file = document.getElementById("upload_file").value;
    //     var fileerror = document.getElementById("file_error");
    //     var context = document.getElementById("context").value;
    //     var contexterror = document.getElementById("context_error");  
    //     var retrycount = document.getElementById("retry_count").value;
    //     var retrycounterror = document.getElementById("retry_count_error");
    //     var retrytime = document.getElementById("retry_time").value;
    //     var retrytimeerror = document.getElementById("retry_time_error");

    //     var errorElement = document.getElementById('error_message');

    //     var flag = true;


    //     if(file == "" || context == "" || retrycount == "" || retrytime == "")
    //     {
    //         if(file == "")
    //         {
    //             var x = document.getElementById("upload_file");
    //             //  x.style.setProperty("border-color", "red", "important");
    //             fileerror.textContent = "CSV/Excel File is required.";
    //             fileerror.style.color = "red";
    //             errorElement.textContent = "";

    //             x.addEventListener("change", function() 
    //             {
    //               // Clear the error message when a change is detected
    //               fileerror.textContent = "";
    //               contexterror.textContent = "";
    //             });

    //         }
    //         if(context == "")
    //         {
    //             var x = document.getElementById("context");
    //             //x.style.setProperty("border-color", "red", "important");
    //             contexterror.textContent = "Context is required.";
    //             contexterror.style.color = "red";

    //             x.addEventListener("change", function() 
    //             {
    //                 // Clear the error message when a change is detected
    //                 contexterror.textContent = "";
    //                 errorElement.textContent = "";
    //                 fileerror.textContent = "";
    //             });
    //         }

    //         if(retrycount == "")
    //         {
    //             var x = document.getElementById("retry_count");
    //             //x.style.setProperty("border-color", "red", "important");
    //             retrycounterror.textContent = "Retry Count is required.";
    //             retrycounterror.style.color = "red";

    //             x.addEventListener("change", function() 
    //             {
    //               // Clear the error message when a change is detected
    //               retrycounterror.textContent = "";
    //             });
    //         }

    //         if(retrytime == "")
    //         {
    //             var x = document.getElementById("retry_time");
    //             //x.style.setProperty("border-color", "red", "important");
    //             retrytimeerror.textContent = "Retry Time Interval is required.";
    //             retrytimeerror.style.color = "red";

    //             x.addEventListener("change", function() 
    //             {
    //               // Clear the error message when a change is detected
    //               retrytimeerror.textContent = "";
    //             });
    //         }
    //         if(x<=900 && x>=3600){
    //         retrytimeerror.textContent = "Retry Time Interval is not match";
    //           retrycounterror.style.color = "red";
    //         }
            
    //         flag = false;

    //     }
    //     if(flag)
    //     {

    //         // Disable the button
    //         document.getElementById("submit_btn").disabled = true;
    //         document.getElementById("submit_btn").style.backgroundColor = "gray";

    //         document.getElementById("cancel_btn").disabled = true;
    //         document.getElementById("cancel_btn").style.backgroundColor = "gray";

    //         document.getElementById("clear_btn").disabled = true;
    //         document.getElementById("clear_btn").style.backgroundColor = "gray";

    //         //Display processing message 
    //         var processingMsg = document.createElement("p");
    //         processingMsg.textContent = "Campaign creation Processing...please wait";
    //         processingMsg.style.fontSize = "18px";
    //         //   submit_btn.parentNode.insertBefore(processingMsg, submit_btn);
    //         document.getElementById("submit_btn").parentNode.insertBefore(processingMsg, document.getElementById("submit_btn"));

    //         return true;
    //     } 
    //     else 
    //     {
    //         return false;
    //     }
    // }
function validateForm() {
    console.log('validateForm() function is being called.');

    var fileInput = document.getElementById("upload_file");
    var fileerror = document.getElementById("file_error");
    var contextInput = document.getElementById("context");
    var contexterror = document.getElementById("context_error");  
    var retrycountInput = document.getElementById("retry_count");
    var retrycounterror = document.getElementById("retry_count_error");
    var retrytimeInput = document.getElementById("retry_time");
    var retrytimeerror = document.getElementById("retry_time_error");

    var errorElement = document.getElementById('error_message');
    var flag = true;

    // Clear previous error messages
    fileerror.textContent = "";
    contexterror.textContent = "";
    retrycounterror.textContent = "";
    retrytimeerror.textContent = "";
    errorElement.textContent = "";

    // Check for required fields
    if (fileInput.value === "") {
        fileerror.textContent = "CSV/Excel File is required.";
        fileerror.style.color = "red";
        flag = false;
    }

    if (contextInput.value === "") {
        contexterror.textContent = "Context is required.";
        contexterror.style.color = "red";
        flag = false;
    }

    if (retrycountInput.value === "") {
        retrycounterror.textContent = "Retry Count is required.";
        retrycounterror.style.color = "red";
        flag = false;
    }

    if (retrytimeInput.value === "") {
        retrytimeerror.textContent = "Retry Time Interval is required.";
        retrytimeerror.style.color = "red";
        flag = false;
    } else {
        var retryTimeValue = parseInt(retrytimeInput.value);
        // Validate retry time
       if ((retryTimeValue < 900 || retryTimeValue > 3600) && retrytimeInput.value !== "0") {
    retrytimeerror.textContent = "Retry Time Interval must be between 900 and 3600.";
    retrytimeerror.style.color = "red";
    flag = false;
}

    }

    if (flag) {
        // Disable the buttons
        document.getElementById("submit_btn").disabled = true;
        document.getElementById("submit_btn").style.backgroundColor = "gray";

        document.getElementById("cancel_btn").disabled = true;
        document.getElementById("cancel_btn").style.backgroundColor = "gray";

        document.getElementById("clear_btn").disabled = true;
        document.getElementById("clear_btn").style.backgroundColor = "gray";

        // Display processing message 
        var processingMsg = document.createElement("p");
        processingMsg.textContent = "Campaign creation Processing...please wait";
        processingMsg.style.fontSize = "18px";
        document.getElementById("submit_btn").parentNode.insertBefore(processingMsg, document.getElementById("submit_btn"));

        return true;
    } else {
        return false;
    }
}

</script>

<script>
    // Function to format the current date and time as YYYY-MM-DDTHH:MM
    function getCurrentDateTime() 
    {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@if($message = Session::get('success'))
 <script>
    $(document).ready(function() 
    {

        $('#success_msg').modal('show');

	      function clearFormFields() 
          {
 	          $('#upload_file').val('');
	          $('#retry_count').val('');
              $('#retry_time').val('');
	          $('#context').val('');
	      }

        // Add a click event listener to the document body
        $('body').on('click', function(e) 
        {
            // Check if the click target is outside of the modal
            if (!$('#success_msg').is(e.target) && $('#success_msg').has(e.target).length === 0) 
            {
                // Close the modal if the click is outside
                $('#success_msg').modal('hide');
                clearFormFields();
                location.reload();		
            }
        });

	      $(document).on('keydown', function(e) 
          {
            if (e.key === 'Escape') 
            {
                $('#success_msg').modal('hide');
                clearFormFields();
                location.reload();

            }
	      });

	      $('#success_msg .btn-success').on('click', function()
          {
		        $('#success_msg').modal('hide');
                clearFormFields();
                location.reload();
	      });

	      $('#success_msg button.close').on('click', function() 
          {
		        $('#success_msg').modal('hide');
                clearFormFields();
                location.reload();
	      });
	
    });
</script> 

@endif


<div class="modal fade bs-example-modal-md" id="success_msg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px" data-backdrop="true">
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
                <p  style="margin-top: 15px; position:center;"><b>{!! $message !!}</b></p>

                <br>
            </center>
            <button type="button" class="btn btn-success" aria-label="Close" style="margin-top:40px;">Close</button>
        </div>
    </div>
</div>
<div class = "card">
<!-- <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col"> -->
    <div class="px-3" style="background-color: #FFF; text-align: center; color: black; height: 50px; padding-top: 8px; margin-bottom: 20px;">
      <!--  <h2 class="text-2xl font-medium" style="font-weight: bold;">Create Campaign</h2> -->
	 <span class="mx-2 text-black text-xl uppercase font-bold"> Create Campaign </span>
		
    </div>

    <form action="{{ route('file-import') }}" method="POST" name="campaign_form" id="campaign_form" onsubmit="return validateForm()" enctype="multipart/form-data">

        @csrf

        <!-- customised radio button -->
        <div class="- mx-3 flex mb-6">
            <div class="col-sm-7">
                <label class=" col-sm-3 col-form-label" for="user_avatar" style="text-align: right; line-height: 42px; font-size: medium;">Customised OBD<span data-toggle="tooltip" data-original-title="Select campaign type">
            <label style="color: #FF0000;">*</label> [?]
        </span>
            </div>
            <div class="md:w-1/2 px-3 mb-6 md:mb-0 flex">
                <div class="mr-4 flex items-center">
                    <input type="radio" name="campaign_type" id="customised" checked value="C" onchange="clearInputs()">
                    <label for="static" class="ml-2">Yes</label>
                </div>
        
                <div class="mr-4 flex items-center">
                     <input type="radio" name="campaign_type" id="non_customised" value="N" checked  onchange="clearInputs()">
                        <label for="dynamic" class="ml-2">No</label>
                </div>
            </div>
        </div>


	      <!-- File Upload and Date -->
        <div class="- mx-3 flex mb-6">
            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class=" col-sm-3 col-form-label" for="user_avatar" style="text-align: right; line-height: 42px; font-size: medium;">Upload file<span data-toggle="tooltip" data-original-title="Choose csv format only">
            <label style="color: #FF0000;">*</label> [?]
        </span>
	          </div>

	          <div class="md:w-1/2 px-3 mb-6 md:mb-0">
		          <div class="flex items-center">
          		    <input
                	   class="form-control" accept=".csv"
                    	aria-describedby="user_avatar_help" id="upload_file" type="file" onclick="chooseFile()" name="upload_file" title="Upload CSV / Excel file" accept="text/plain,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" style="width: 300px;"
                        data-toggle="tooltip" data-placement="top" data-html="true" title=""
                        data-original-title="Upload the Mobile Numbers via Excel, CSV, Text Files" autocomplete="off">

		              <!-- <a href="public/sample_mobileno.csv" class=" md:w-full mt-7 bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" download style="margin-top: -4px; width: 200px; text-align: center; margin-left: 10px">Download Sample</a> -->
                      <a id="download_sample_link" href="#" download class=" md:w-full mt-7 bg-gray-500  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" download style="margin-top: -4px; width: 200px; text-align: center; margin-left: 10px">Download Sample</a>

		          </div>
			        <div id="file_error" class="error-message"></div>
		        </div>
	      </div>
	
          <div class="- mx-3 flex mb-6">
          <div class="md:w-1/2 px-3 mb-6 md:mb-0">
        <label class="col-form-label" for="retry_count" style="text-align: right; line-height: 42px; font-size: medium;">
            Call Retry Count
            <span class="text-danger">*</span>
        </label>
        <!-- Tooltip next to label -->
        <span data-toggle="tooltip" data-original-title="Choose retry count" style="margin-left: 5px; cursor: pointer;">
            [?]
        </span>
    </div>
    <div class="md:w-1/2 px-3 mb-6 md:mb-0">
        <select class="w-full bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1"
            style="width: 300px;" autocomplete="off" id="retry_count" name="retry_count" title="Retry Count">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <div id="retry_count_error" class="error-message"></div>
    </div>
</div>



	      <!-- Hidden text area for mobile numbers -->
	      <div class="md:w-1/2 px-3"  >
		        <textarea id="txt_list_mobno" name="txt_list_mobno" tabindex="2" 
                            placeholder="919234567890,919234567891,919234567892,919234567893"
                            class="form-control form-control-primary required" data-toggle="tooltip"
                            data-placement="top" data-html="true" title=""
                            data-original-title="Enter Mobile Numbers. Each row must contains only one mobile no  with Country Code and without + symbol. For Ex : 919234567890,919234567891,919234567892,919234567893"
                            style="height: 150px !important; width: 100%; display: none;">
            </textarea>
	      </div>


          <div class="- mx-3 flex mb-6">
          <div class="md:w-1/2 px-3 mb-6 md:mb-0">
        <label class="col-form-label" for="retry_time" style="text-align: right; line-height: 42px; font-size: medium;">
            Retry Time Interval
            <span class="text-danger">*</span>
        </label>
        <!-- Tooltip icon next to the label -->
        <span data-toggle="tooltip" data-original-title="Set time interval" style="margin-left: 5px; cursor: pointer;">
            [?]
        </span>
    </div>
    
    <div class="md:w-1/2 px-3 mb-6 md:mb-0">
        <input type="text" name="retry_time" id="retry_time" class="form-control"
            style="width: 300px;" autocomplete="off"
            placeholder="Min. 900 Secs & Max. 3600 Secs"
            onkeydown="return /^[0-9]$/.test(event.key) || ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight'].includes(event.key)">
        
        <div id="retry_time_error" class="error-message"></div>
    </div>
</div>


	    <!-- Context -->
        <div class="-mx-3 md:flex mb-6 context_msg" >
	        <!-- Context input -->
            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class=" col-sm-3 col-form-label" for="user_avatar" style="text-align: right; line-height: 42px; font-size: medium;">
                    Context<span data-toggle="tooltip" data-original-title="choose context name">
            <label style="color: #FF0000;">*</label> [?]
        </span>
                </label>
		    </div>
		    <div class="md:w-1/2 px-3 mb-6 md:mb-0">
            <input type="hidden" name="ivr_id" id="ivr_id" value="">
			    <select name="context" id="context"class="form-control" style="width: 300px; float: left" autocomplete="off">
        			<option value="" disabled selected>Select a Context</option>
        			<!-- You can use JavaScript to populate the options dynamically -->
    			</select>
                <button id="playButton" type="button" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" style="margin-left: 10px; width: 100px; display: none; float: left">
                        Play
                </button><br><br>
		        <div id="context_error" class="error-message"></div>
		    </div>
        </div>

        <audio id="audioPlayer" controls style="display: none"></audio>

	      <!-- error message display for invalid file format -->
	      <div id="error_message" class="error-message"></div>
	
	      <!-- Displaying Messages -->
	      <div class="-x-3 md:flex mb-6">
		        @if(Session::has('message'))
			          <div class="alert {{ Session::get('alert-class') }}" role="alert">
				            {{ Session::get('message') }}
			          </div>
		        @endif
	      </div>

	
	      <!-- Buttons (Clear, Submit, Cancel) -->
        <div class="-   mx-3 md:flex mb-6">
	          <!-- Clear button -->
            <div class="md:w-1/2 px-3 mb-4 md:mb-0">
                  <button type="button" id="clear_btn"
                  style="background-color: #00ee5a !important;"
                  class="w-full mt-7 text-black py-3 px-4 rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition ease-in-out duration-300">
                    Clear
                  </button></a>
            </div>
	          <!-- Submit button -->
            <div class="md:w-1/2 px-3 mb-4 md:mb-0">
                <button type="submit" name="submit_btn" id="submit_btn"
                style="background-color: #00ee5a !important;"
                class="w-full mt-7 text-black py-3 px-4 rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition ease-in-out duration-300">
                    Submit
                </button>
            </div>
	          <!-- Cancel button -->
            <div class="md:w-1/2 px-3 mb-4 md:mb-0">
                <a href="{{ route('cancel') }}">
                    <button type="button" id="cancel_btn"
                    style="background-color: #00ee5a !important;"
                    class="w-full mt-7 text-black py-3 px-4 rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition ease-in-out duration-300">
                    Cancel
                </button></a>
            </div>
       
</div>
        <div class="preloader-wrapper" style="display:none;">
            <div class="preloader">
            </div>
            <div class="text" style="color: white; background-color:#f27878; padding: 10px; margin-left:600px;">
                <b>Mobile number validation processing ...<br/> Please wait.</b> 
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // Wait for the document to be ready
            $(document).ready(function() 
            {
              // Attach a click event handler to the "Clear" button
              $('#clear_btn').click(function() 
              {
                // Clear the input fields by setting their values to an empty string

                $('#upload_file').val('');
                $('#context').val('');
                $('#txt_list_mobno').val('');
                $('#retry_count').val('0');
                $('#retry_time').val('0');
                $('#retry_time_error').text('');
                $('#context_error').text('');
                $('#file_error').text('');
                $('#error_message').text('');
                $('#playButton').hide();
                var audioPlayer = $('#audioPlayer')[0];
                audioPlayer.pause(); // Pause the audio
                
              });
            });
        </script>


<script>
    // Get the radio buttons
    const customisedRadio = document.getElementById('customised');
    const nonCustomisedRadio = document.getElementById('non_customised');

    // Get the download link
    const downloadLink = document.getElementById('download_sample_link');

    // Get the context dropdown
    const contextDropdown = document.getElementById('context');

    // Add event listeners to the radio buttons
    customisedRadio.addEventListener('click', updateDownloadLink);
    nonCustomisedRadio.addEventListener('click', updateDownloadLink);

    // Function to update the download link based on the selected radio button
    function updateDownloadLink() 
    {
        // Get the selected value
        const selectedValue = document.querySelector('input[name="campaign_type"]:checked').value;

        // Clear the context value
        contextDropdown.value = '';

        // Update the download link based on the selected value
        if (selectedValue === 'C') 
        {
            downloadLink.href = 'public/sample_mobileno_1.csv'; // Replace 'path_to_customised_file.csv' with the actual path to the customised file
        } 
        else if (selectedValue === 'N') 
        {
            downloadLink.href = 'public/sample_mobileno.csv';
        }
    }

    // Call the function initially to set the correct download link based on the default checked radio button
    updateDownloadLink();
</script>

<script>
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
  });
</script>

<script>
function clearInputs() {
    // Clear all relevant input fields
    document.getElementById("retry_time").value = '';
    document.getElementById("upload_file").value = '';
    document.getElementById("retry_count").value = '';
    document.getElementById("context").value = '';
    
    // You can clear other inputs as needed
    // For example, if you want to clear dropdowns
    document.getElementById("retry_count").selectedIndex = 0; // Select the first option
    document.getElementById("context").selectedIndex = 0; // Select the first option
    document.getElementById("retry_time").selectedIndex = 0; // Select the first option

    // Reset any error messages if applicable
    document.getElementById("company_name_error").textContent = '';
    document.getElementById("file_error").textContent = '';
    document.getElementById("context_error").textContent = '';
    document.getElementById("remarks_error").textContent = '';
    // Add other error messages you want to clear
}
</script>
                
    </form>
    
</div>



<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Confirmation Modal for showing invalid and duplicate numbers -->
<div class="modal fade bs-example-modal-md" id="confirmationModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="confirmationModal" aria-hidden="true" data-backdrop="false" style=" position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
    <div class="modal-dialog">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-body" style="border-top: 4px inset blue;">
                <!-- Other confirmation content here -->
                <center>
                <p><strong>Invalid Numbers:</strong> <span><strong id="invalidNumberCount"></strong></span>
                <span style="margin-right: 10px;"></span>
                <strong>Duplicate Numbers:</strong> <span><strong id="duplicateNumberCount"></strong></span></p>
                            <p style="color: #333;">Are you sure you want to create a campaign?</p>
                </center>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa;">
                <button type="button" class=" md:w-full bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal">Yes</button>
                <button type="button" class=" md:w-full bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full button0" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div> 


<!-- Confirmation Modal for mobile number exceed available credits -->
<div class="modal fade bs-example-modal-md" id="alertModal" data-backdrop="true" tabindex="-1" role="dialog" aria-labelledby="alertModal" aria-hidden="true"  style=" position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
    <div class="modal-dialog" style="pointer-events: auto;">
        <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="modal-body" style="border-top: 4px inset red;">
                <!-- Other confirmation content here -->
                <center>
		              <p style="color: #333;">You don't have enough credits to create this campaign. Please renew your credits.</p>
                </center>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa;">
		            <center>
		                <button type="button" class= "button button4" data-dismiss="modal" onclick="location.reload();">OK</button>
		            </center>
            </div>
        </div>
    </div>
</div>



<!-- Error Modal for ajax -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="errorModalLabel">Error</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="errorText">An error occurred.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</html>

@endsection
