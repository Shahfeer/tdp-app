<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')
<div id='loader' style="display: none;"></div>

<!-- HTML section begins -->
<html>


<style>   <!-- CSS styles for media queries -->
    .modal.fade .modal-dialog 
    {
        transform: translate(0, -175%) !important;
    }
</style>

<style>
<!-- Styling for confirmation dialogue yes/no buttons -->

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

</style>


<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>  -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>


<?php

use Illuminate\Support\Facades\Auth;

$availCredits = \App\Models\UserCredit::where('user_id', Auth::id())->value('available_credits') ?? 0;

?>    

<!-- JavaScript and jQuery libraries -->
<script>
$(function() 
{
    init();
});

function init() 
{
      document.getElementById('upload_file').addEventListener('change', handleFileSelect, false);
}



function handleFileSelect(event) 
{
    console.log(event)
    $("#loader").show();
    $(".preloader-wrapper").show();
    var flenam = document.querySelector('#upload_file').value;

    var extn = flenam.split('.').pop();
    var errorElement = document.getElementById('error_message');
    //	var file = document.getElementById('campaign_form');
    var fileInput = document.getElementById('upload_file');

    if (extn != '' && extn != 'NULL') 
    {

        var fd = new FormData(document.getElementById("campaign_form"));

        fd.append('file_type', extn);
        console.log(fd)
        console.log(new Date().toLocaleString());

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
                $("#loader").hide();
		            $(".preloader-wrapper").hide();
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
    else 
    {
        errorElement.textContent = 'Please upload a valid Excel, CSV, or Text file!';
        errorElement.style.color = 'red';
        fileInput.value = ''; // Clear the file input

        fileInput.addEventListener("input", function () 
        {
            errorElement.textContent = ""; // Clear the error message
        });

        $("#loader").hide();
        $(".preloader-wrapper").hide();
    }
}


function handleFile() 
{
    console.log(new Date().toLocaleString());
    const input = document.getElementById('upload_file');
    const file = input.files[0];
    const reader = new FileReader();

    reader.onload = function(event) 
    {

        const data = new Uint8Array(event.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheet_name_list = workbook.SheetNames;
        const sheet = workbook.Sheets[sheet_name_list[0]];
        const jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1, cellText: true });
	      const dataArray = jsonData.map(row => row[0]);
	

        // Process jsonData as needed
        console.log(dataArray);
        console.log(new Date().toLocaleString());

        //	$('#txt_list_mobno').focus();
        call_remove_duplicate_invalid(dataArray);

    };

    reader.readAsArrayBuffer(file);

}




/* Function to handle file loading */
function handleFileLoad(event) 
{
    console.log(new Date().toLocaleString());

    const lines = event.target.result.split('\n');

    if (lines[lines.length - 1] === "") 
    {
        lines.pop();
    } 

    //console.log(formattedLines);
    console.log(new Date().toLocaleString());


    //   $('#txt_list_mobno').focus();
    call_remove_duplicate_invalid(lines); 
}



var value_list = new Array; ///this one way of declaring array in javascript
/*Function to export data to table */
function ExportToTable() 
{
	    console.log("excel read function");

      var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
      /*Checks whether the file is a valid excel file*/
      if (regex.test($("#upload_file").val().toLowerCase())) 
      {
            var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
            if ($("#upload_file").val().toLowerCase().indexOf(".xlsx") > 0) 
            {
                xlsxflag = true;
            }
            /*Checks whether the browser supports HTML5*/
            if (typeof (FileReader) != "undefined") 
            {
                var reader = new FileReader();
                reader.onload = function (e) 
                {
                  var data = e.target.result;
                  /*Converts the excel data in to object*/
                  if (xlsxflag) 
                  {
                      var workbook = XLSX.read(data, 
                      {
                          type: 'binary'
                      });
                  } 
                  else 
                  {
                      var workbook = XLS.read(data, 
                      {
                          type: 'binary'
                      });
                  }
                  /*Gets all the sheetnames of excel in to a variable*/
                  var sheet_name_list = workbook.SheetNames;

                  var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/
                  sheet_name_list.forEach(function (y) 
                  {
                      /*Iterate through all sheets*/
                      /*Convert the cell value to Json*/
                      if (xlsxflag)
		                  {
                          var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                      } 
                      else 
                      {
                          var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                      }
                      if (exceljson.length > 0 && cnt == 0) 
                      {
                          BindTable(exceljson, '#txt_list_mobno');
                          cnt++;
                      }
                  });
                  //   $('#txt_list_mobno').show();
                  $('#txt_list_mobno').focus();
	                call_remove_duplicate_invalid();
                }
                if (xlsxflag) 
                {

                  /*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                  reader.readAsArrayBuffer($("#upload_file")[0].files[0]);
                } 
                else 
                {
                  reader.readAsBinaryString($("#upload_file")[0].files[0]);
                }
            } 
            else 
            {
                alert("Sorry! Your browser does not support HTML5!");
            }
      } 
      else 
      {
        alert("Please upload a valid Excel file!");
      }
}


/* Function to bind table */
 function BindTable(jsondata, tableid) 
 {
      /*Function used to convert the JSON array to Html Table*/
      var columns = BindTableHeader(jsondata, tableid); /*Gets all the column headings of Excel*/
      for (var i = 0; i < jsondata.length; i++) 
      {
        for (var colIndex = 0; colIndex < columns.length; colIndex++) 
        {
          var cellValue = jsondata[i][columns[colIndex]];
          if (cellValue == null)
            cellValue = "";
          value_list.push("\n" + cellValue);
        }
      }
      $(tableid).val(value_list);
  }

  /* Function to bind table header */
  function BindTableHeader(jsondata, tableid) 
  {
      /*Function used to get all column names from JSON and bind the html table header*/
      var columnSet = [];
      for (var i = 0; i < jsondata.length; i++) 
      {
        var rowHash = jsondata[i];
        for (var key in rowHash) 
        {
          if (rowHash.hasOwnProperty(key)) 
          {
            if ($.inArray(key, columnSet) == -1) 
            {
              /*Adding each unique column names to a variable array*/
              columnSet.push(key);
              value_list.push("\n" + key);
            }
          }
        }
      }
      return columnSet;
  }


/* Function to call remove duplicate and invalid numbers */
function call_remove_duplicate_invalid(dataArray) 
{

	    console.log("Excel records insertion started");

      var userMasterId = {{ auth()->user()->user_master_id }};
	
	    console.log(dataArray.length);
      $("#loader").show();
      $('.preloader-wrapper').show();
      $.ajax({
        method: 'POST',
        url: "{{ url('process-mobile-numbers') }}",
        data: { validateMobno: 'validateMobno', mobno: dataArray },
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

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () 
{
    // Get the context dropdown element
    var contextDropdown = $('#context');

    var playButton = $('#playButton');

    // Get the audio element
    var audioPlayer = $('#audioPlayer');

    var isAudioPlaying = false; 

    contextDropdown.on('change', function () 
    {

	      var selectedContext = contextDropdown.val();

        if (selectedContext !== '') 
        {
            // Show the play button if a context is selected
            playButton.show();
	

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
                    if (data.audio_url) 
                    {
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


    $.ajax({
        url: "{{ route('get_context') }}", // Replace 'get_context' with the name of your route
        type: 'GET',
        success: function (contexts) 
        {
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
        },
        error: function (xhr, status, error) 
        {
            // Handle the error here
            console.error(xhr.responseText);
        }
      });
});

</script> 


 
<script>

/* JavaScript function for form validation */
function validateForm()
{

    console.log('validateForm() function is being called.');

    var file = document.getElementById("upload_file").value;
    var fileerror = document.getElementById("file_error");
    var context = document.getElementById("context").value;
    var contexterror = document.getElementById("context_error");  
    var retrycount = document.getElementById("retry_count").value;
    var retrycounterror = document.getElementById("retry_count_error");

    var errorElement = document.getElementById('error_message');

    var flag = true;


    if(file == "" || context == "" || retrycount == "")
    {
        if(file == "")
        {
            var x = document.getElementById("upload_file");
            //  x.style.setProperty("border-color", "red", "important");
            fileerror.textContent = "CSV/Excel File is required.";
            fileerror.style.color = "red";
            errorElement.textContent = "";

	          x.addEventListener("change", function() 
            {
                  // Clear the error message when a change is detected
                  fileerror.textContent = "";
                  contexterror.textContent = "";
                
              //    errorElement.textContent = "";
            });

        }
        if(context == "")
        {
            var x = document.getElementById("context");
            //x.style.setProperty("border-color", "red", "important");
            contexterror.textContent = "Context is required.";
            contexterror.style.color = "red";

            x.addEventListener("change", function() 
            {
                // Clear the error message when a change is detected
                contexterror.textContent = "";
                errorElement.textContent = "";
                fileerror.textContent = "";
            });
        }


        if(retrycount == "")
        {
              var x = document.getElementById("retry_count");
              //x.style.setProperty("border-color", "red", "important");
              retrycounterror.textContent = "Retry Count is required.";
              retrycounterror.style.color = "red";

              x.addEventListener("change", function() 
              {
                  // Clear the error message when a change is detected
                  retrycounterror.textContent = "";
              });
        }

        flag = false;

    }
    // alert("FL:"+flag);

    if(flag)
    {

                // Disable the button
                document.getElementById("submit_btn").disabled = true;
		            document.getElementById("submit_btn").style.backgroundColor = "gray";

  	            document.getElementById("cancel_btn").disabled = true;
                document.getElementById("cancel_btn").style.backgroundColor = "gray";

                document.getElementById("clear_btn").disabled = true;
                document.getElementById("clear_btn").style.backgroundColor = "gray";

	              //Display processing message 
                var processingMsg = document.createElement("p");
                processingMsg.textContent = "Campaign creation Processing...please wait";
                processingMsg.style.fontSize = "18px";
                //   submit_btn.parentNode.insertBefore(processingMsg, submit_btn);
		            document.getElementById("submit_btn").parentNode.insertBefore(processingMsg, document.getElementById("submit_btn"));

                return true;
    } 
    else 
    {
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
            $('#retry_count').val('0');
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
               // clearFormFields();
               // location.reload();		
            }
        });

        $(document).on('keydown', function(e) 
        {
                  if (e.key === 'Escape') 
                  {
                      $('#success_msg').modal('hide');
                    // clearFormFields();
                    // location.reload();

                  }
	      });

	      $('#success_msg .btn-success').on('click', function()
        {
		          $('#success_msg').modal('hide');
               // clearFormFields();
               // location.reload();
	      });

	      $('#success_msg button.close').on('click', function() 
        {
		          $('#success_msg').modal('hide');
               // clearFormFields();
                //location.reload();
	      });
	
    });
</script> 

@endif


<div class="modal fade bs-example-modal-md" id="success_msg" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 420px" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id='mdl'>
            <button type="button" class="close" aria-label="Close" style="width: 40px; padding: 0px; border-radius: 5px; margin-left:370px;">
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


    $(document).ready(function() 
    {
        $('#error_msg').modal('show');
	      $('#error_msg').css('display', 'block');

	      function clearFormFields() 
        {
           $('#user').val('');
           $('#campaign').val('');
        }

	
        // Add a click event listener to the document body
        $('body').on('click', function(e) 
        {

            // Check if the click target is outside of the modal
            if (!$('#error_msg').is(e.target) && $('#error_msg').has(e.target).length === 0) 
            {
                // Close the modal if the click is outside
                $('#error_msg').modal('hide');
		        // clearFormFields();
                // location.reload();
            }

        });

	      $('#error_msg .btn-danger').on('click', function()
        {
                $('#error_msg').modal('hide');
                //location.reload();
                // clearFormFields();
                // location.reload();
        });

	      $('#error_msg button.close').on('click', function() 
        {
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


<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">
    <div class="px-3" style="background-color: #FFF; text-align: center; color: black; height: 50px; padding-top: 8px; margin-bottom: 20px;">
        <h2 class="text-2xl font-medium" style="font-weight: bold;">Create Campaign</h2>
    </div>

<!-- <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col" style="padding-left: 1rem !important; padding-top: 0.5rem !important;">
<div style="background-color: #FFF; height: 50px; padding-top: 0px;">
        <h2 class="text-2xl font-medium">Create Campaign</h2>
</div> -->

    <form action="{{ route('file-import') }}" method="POST" name="campaign_form" id="campaign_form" onsubmit="return validateForm()" enctype="multipart/form-data">

        @csrf

	<!-- File Upload and Date -->
        <div class="- mx-3 flex mb-6">
            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">Upload file<span style="color: #ff0000">*</span> <span style="font-size: smaller; color: blue;">(csv / excel file)</span></label>
	   </div>

	<div class="md:w-1/2 px-3 mb-6 md:mb-0">
		<div class="flex items-center">
          		 <input
                	class="w-full bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" accept=".txt, .csv, .xlsx, .xls"
                    	aria-describedby="user_avatar_help" id="upload_file" type="file" name="upload_file" title="Upload CSV / Excel file" accept="text/plain,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" style="width: 300px;"
                        data-toggle="tooltip" data-placement="top" data-html="true" title=""
                        data-original-title="Upload the Mobile Numbers via Excel, CSV, Text Files" autocomplete="off" @if(Auth::user()->id != 7 && $availCredits == 0) disabled @endif>

		<a href="public/sample_mobileno.csv" class=" md:w-full mt-7 bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" download style="margin-top: -4px; width: 200px; text-align: center; margin-left: 10px">Download Sample</a>

		</div>
			<div id="file_error" class="error-message"></div>
		</div>
	</div>
	

	 <div class="- mx-3 flex mb-6">
	   <!-- File Retry Count dropdown -->
           <div class="md:w-1/2 px-3 mb-6 md:mb-0">
        		<label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">Call Retry Count<span style="color: #ff0000">*</span></label>
			</div>
		<div class="md:w-1/2 px-3 mb-6 md:mb-0">
        		<select
            			class="w-full bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" style="width: 300px;" autocomplete="off"
            			id="retry_count"
            			name="retry_count"
            			title="Retry Count">
 
            			<option value="0">0</option>
            			<option value="1">1</option>
            			<option value="2">2</option>
            			<option value="3">3</option>
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
                            style="height: 150px !important; width: 100%; display: none;"></textarea>
	</div>

	

	 <!-- Context, Caller ID and Time Interval -->
            <div class="-   mx-3 md:flex mb-6">
	    <!-- Context input -->
            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar" style="text-align: right; line-height: 42px;">
                    Context<span style="color: #ff0000">*</span>
                </label>
		</div>
		<div class="md:w-1/2 px-3 mb-6 md:mb-0">
        <input type="hidden" name="ivr_id" id="ivr_id" value="">
			<select name="context" id="context" class="w-full bg-gray-200 text-black border border-gray-200 rounded py-2 px-3 mb-1" style="width: 300px; float: left" autocomplete="off">
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
                <a href="{{ route('cancel') }}">
                    <button type="button" id="cancel_btn"
                    class=" md:w-full mt-7 bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full">
                    Cancel
                </button></a>
            </div>
        </div>




<div class="preloader-wrapper" style="display:none;">
      <div class="preloader">
      </div>
      <div class="text" style="color: white; background-color:#f27878; padding: 10px; margin-left:600px; margin-top:160px;">
     <b>Mobile number validation processing ...<br/> Please wait.</b> 
      </div>
    </div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Wait for the document to be ready
  $(document).ready(function() {
    // Attach a click event handler to the "Clear" button
    $('#clear_btn').click(function() {
      // Clear the input fields by setting their values to an empty string

      $('#upload_file').val('');
      $('#context').val('');
      $('#txt_list_mobno').val('');
      $('#retry_count').val('0');
      $('#context_error').text('');
      $('#file_error').text('');
      $('#error_message').text('');
      $('#playButton').hide();
      var audioPlayer = $('#audioPlayer')[0];
      audioPlayer.pause(); // Pause the audio
      
    });
  });
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
