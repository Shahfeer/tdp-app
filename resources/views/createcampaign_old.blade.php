@extends('layouts.app')
@section('content')
<html>


<style>
/* @media (max-width: 480px) { // Small Mobile
    .modal.fade .modal-dialog {
        transform: translate(0, -150%) !important;
    }
}

@media (min-width: 481px) and (max-width: 767px) { // Medium Mobile
    .modal.fade .modal-dialog {
        transform: translate(0, -160%) !important;
    }
}

@media (min-width: 768px) and (max-width: 1024px){ // Tablet
    .modal.fade .modal-dialog {
        transform: translate(0, -150%) !important;
    }
}

@media (min-width: 1025px) and (max-width: 1280px){ // Laptop
    .modal.fade .modal-dialog {
        transform: translate(0, -135%) !important;
    }
}

@media (min-width: 1281px) { // High Resolution Laptop */
    .modal.fade .modal-dialog {
        transform: translate(0, -175%) !important;
    }
// }
</style>

<!-- <style>
.modal {
    position: relative !important;
    z-index: 99999 !important;
}
</style>  -->


<!-- <link href="{{ asset('css/conformpop.css') }}" rel='stylesheet'> -->

<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>    -->


<script>
$(function() {
init();
});

function init() {
      document.getElementById('upload_file').addEventListener('change', handleFileSelect, false);
    }


function handleFileSelect(event) {
      var flenam = document.querySelector('#upload_file').value;
      var extn = flenam.split('.').pop();

      if (extn == 'xlsx' || extn == 'xls') {
        ExportToTable();
      } else {
        const reader = new FileReader()
        reader.onload = handleFileLoad;
        reader.readAsText(event.target.files[0])
      }
    }

function handleFileLoad(event) {
      console.log(event);
      $('#txt_list_mobno').val(event.target.result);
      $('#txt_list_mobno').focus();
      call_remove_duplicate_invalid();
    }

  var value_list = new Array; ///this one way of declaring array in javascript
    function ExportToTable() {
      var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
      /*Checks whether the file is a valid excel file*/
      if (regex.test($("#upload_file").val().toLowerCase())) {
        var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
        if ($("#upload_file").val().toLowerCase().indexOf(".xlsx") > 0) {
          xlsxflag = true;
        }
        /*Checks whether the browser supports HTML5*/
        if (typeof (FileReader) != "undefined") {
          var reader = new FileReader();
          reader.onload = function (e) {
            var data = e.target.result;
            /*Converts the excel data in to object*/
            if (xlsxflag) {
              var workbook = XLSX.read(data, {
                type: 'binary'
              });
            } else {
              var workbook = XLS.read(data, {
                type: 'binary'
              });
            }
            /*Gets all the sheetnames of excel in to a variable*/
            var sheet_name_list = workbook.SheetNames;

            var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/
            sheet_name_list.forEach(function (y) {
              /*Iterate through all sheets*/
              /*Convert the cell value to Json*/
              if (xlsxflag) {
                var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
              } else {
                var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
              }
              if (exceljson.length > 0 && cnt == 0) {
                BindTable(exceljson, '#txt_list_mobno');
                cnt++;
              }
            });
            $('#txt_list_mobno').show();
            $('#txt_list_mobno').focus();
          }
          if (xlsxflag) {
            /*If excel file is .xlsx extension than creates a Array Buffer from excel*/
            reader.readAsArrayBuffer($("#upload_file")[0].files[0]);
          } else {
            reader.readAsBinaryString($("#upload_file")[0].files[0]);
          }
        } else {
          alert("Sorry! Your browser does not support HTML5!");
        }
      } else {
        alert("Please upload a valid Excel file!");
      }
    }


 function BindTable(jsondata, tableid) {
      /*Function used to convert the JSON array to Html Table*/
      var columns = BindTableHeader(jsondata, tableid); /*Gets all the column headings of Excel*/
      for (var i = 0; i < jsondata.length; i++) {
        for (var colIndex = 0; colIndex < columns.length; colIndex++) {
          var cellValue = jsondata[i][columns[colIndex]];
          if (cellValue == null)
            cellValue = "";
          value_list.push("\n" + cellValue);
        }
      }
      $(tableid).val(value_list);
    }

    function BindTableHeader(jsondata, tableid) {
      /*Function used to get all column names from JSON and bind the html table header*/
      var columnSet = [];
      for (var i = 0; i < jsondata.length; i++) {
        var rowHash = jsondata[i];
        for (var key in rowHash) {
          if (rowHash.hasOwnProperty(key)) {
            if ($.inArray(key, columnSet) == -1) {
              /*Adding each unique column names to a variable array*/
              columnSet.push(key);
              value_list.push("\n" + key);
            }
          }
        }
      }
      return columnSet;
    }


function call_remove_duplicate_invalid() {
      $("#txt_list_mobno_txt").html("");
      var txt_list_mobno = $("#txt_list_mobno").val();

      var chk_remove_duplicates = 0;
      if ($("#chk_remove_duplicates").prop('checked') == true) {
        chk_remove_duplicates = 1;
      }

      var chk_remove_invalids = 0;
      if ($("#chk_remove_invalids").prop('checked') == true) {
        chk_remove_invalids = 1;
      }

      var chk_remove_stop_status = 0;
      if ($("#chk_remove_stop_status").prop('checked') == true) {
        chk_remove_stop_status = 1;
      }

      $.ajax({
        method: 'POST',
        url: "{{ url('process-mobile-numbers') }}",
        data: { validateMobno: 'validateMobno', mobno: txt_list_mobno, dup: chk_remove_duplicates, inv: chk_remove_invalids },
        success: function (response_msg) {
          let response_msg_text = response_msg.msg;
	let invalidCount = response_msg.invalidCount;
//console.log(invalidCount);
	//console.log(response_msg_text);
          const response_msg_split = response_msg_text.split("||");
          $("#txt_list_mobno").val(response_msg_split[0]);

	// Update the invalid number count in the modal
        $("#invalidNumberCount").text(response_msg.invalidCount);

        // Show the confirmation modal
	console.log("Before modal trigger");
	$(document).ready(function() {
         $("#confirmationModal").modal("show");
	});
	console.log("Modal trigger executed");

          if (response_msg_split[1] != '') {
            $("#txt_list_mobno_txt").html("Invalid Mobile Nos : " + response_msg_split[1]);
          }
	
	// Print duplicateCount and invalidCount
        //console.log("Duplicate Count: " + response.duplicateCount);
        //console.log("Invalid Count: " + response.invalidCount);

          if (chk_remove_stop_status == 1) {

          }

        },
        error: function (response_msg, status, error) {
        }
      });
    }



</script>



<script>

// JavaScript function for form validation

function validateForm(){
var file = document.forms["campaign_form"]["file"].value;
var context = document.getElementById("context").value;
var caller_id = document.getElementById("caller_id").value;
//var txt_max_retry_count = document.getElementById("txt_max_retry_count").value;
//var txt_retry_time = document.getElementById("txt_retry_time").value;

var flag = true;



if(file == "" || context == "" || caller_id == "")
{
if(file == "")
{
        var x = document.getElementById("file");
        x.style.setProperty("border-color", "red", "important");
}
if(context == "")
{
        var x = document.getElementById("context");
        x.style.setProperty("border-color", "red", "important");
}
if(caller_id == "")
{
        var x = document.getElementById("caller_id");
        x.style.setProperty("border-color", "red", "important");
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
} else {
return false;
}
}

</script>

<script>
    // Function to format the current date and time as YYYY-MM-DDTHH:MM
    function getCurrentDateTime() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    // Set the current date and time in the input field
    const scheduleAtInput = document.getElementById('schedule_at');
    scheduleAtInput.value = getCurrentDateTime();
</script>

@if($message = Session::get('success'))

<!-- Success pop up Starts --> 
<div class="modal fade bs-example-modal-md" id="success_msg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="false" style=" position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px; display: contents;">
  <div class="modal-dialog modal-md">
      <div class="modal-content" id='mdl' style="min-height: 320px;">
	<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a>
        <center>
        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#28a745" class="bi bi-check-circle-fill" viewBox="0 0 16 16" style="margin-top:25px;">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
</svg>
<br>
<h3 style="color:green; font-size:22px; margin-top:10px;"><b>SUCCESS</b></h3>
	<br>
        <p style="margin: 15px;text-align: justify;"><b>{{$message}}</b></p>
        <br>
        </center>
	<a href="#close-modal" rel="modal:close" class="btn btn-success" data-dismiss="modal" aria-label="Close" style="margin-top:40px; font-weight: bold;">Close</a>
    </div>
  </div>
</div>

<!-- jQuery Modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

<script>
$('#success_msg').modal('show');
$('#success_msg').css('display', 'contents');
</script>
<!-- Success pop up ends -->

@endif

<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col" style="padding-left: 1rem !important; padding-top: 0.5rem !important;">
<div style="background-color: #FFF; height: 50px; padding-top: 0px;">
        <h2 class="text-2xl font-medium">Create Campaign</h2>
</div>

    <form action="{{ route('file-import') }}" method="POST" name="campaign_form" id="campaign_form" onsubmit="return validateForm()" enctype="multipart/form-data">

        @csrf

	<!-- File Upload and Date -->
        <div class="- mx-3 flex mb-6">
            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300" for="user_avatar">Upload file* <a href="public/sample_mobileno.csv" title="Download Sample File. CSV/Excel file only Allowed" style="font-size: 0.75rem;">[Download Sample File]</a></label>
                <input
                    class="w-full bg-gray-200 text-black border border-gray-200 rounded py-3 px-4 mb-3" accept=".csv, .xls, .xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                    aria-describedby="user_avatar_help" id="upload_file" type="file" name="upload_file" required title="Upload CSV / Excel file" accept="text/plain,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                              data-toggle="tooltip" data-placement="top" data-html="true" title=""
                              data-original-title="Upload the Mobile Numbers via Excel, CSV, Text Files">

            </div>
	<div class="md:w-1/2 px-3" >
		<textarea id="txt_list_mobno" name="txt_list_mobno" tabindex="2" required=""
                            onblur="call_remove_duplicate_invalid()"
                            placeholder="919234567890,919234567891,919234567892,919234567893"
                            class="form-control form-control-primary required" data-toggle="tooltip"
                            data-placement="top" data-html="true" title=""
                            data-original-title="Enter Mobile Numbers. Each row must contains only one mobile no  with Country Code and without + symbol. For Ex : 919234567890,919234567891,919234567892,919234567893"
                            style="height: 150px !important; width: 100%; display: none;"></textarea>
			<div id='txt_list_mobno_txt' class='text-danger'></div>
	</div>

            <div class="col  col-md-6" style="display:none;">
                        <div class=" text-lg uppercase font-medium text-gray-800 block mb-2 dark:text-gray-300">
				        DATE* <input type="datetime-local" id="schedule_at" name="schedule_at" class="form-control" style="width:400px; margin-top: 10px; height: 60px;">

                        </div>         
            </div>
        </div>

	
	   <!-- Context, Caller ID and Time Interval -->
            <div class="-   mx-3 md:flex mb-6">
            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="uppercase tracking-wide text-black mb-2" for="company">
                    Context*
                </label>
                <input name="context" required
                    class="w-full bg-gray-200 text-black border border-gray-200 rounded py-3 px-4 mb-3" id="context" title="Context & Campaign name auto generated"
                    type="text" placeholder="Context ">

            </div>
            <div class="md:w-1/2 px-3" style="display: none;">
                <label class="uppercase tracking-wide text-black mb-2" for="title">
                    Caller ID*
                </label>
                <input class="w-full bg-gray-200 text-black border border-gray-200 rounded py-3 px-4 mb-3" id="caller_id" name="caller_id" title="Caller ID"
                    type="text" placeholder="Caller ID" required value="8002">
            </div>
            </div>


	     <!-- Input Fields for Time Interval and Calls Per Batch (Hidden) -->
            <div class="		-   mx-3 md:flex mb-6" style='display: none;'>
            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="uppercase tracking-wide text-black mb-2" for="company">
                    Time Interval Per Batch*
                </label>
                <input type="number" name="time_interval" title="Time Interval Per Batch [Min. 15 Seconds & Max. 900 Seconds allowed]" class="time w-full bg-gray-200 text-black border border-gray-200 rounded py-3 px-4 mb-3" id="time_interval" min="15" max="900" placeholder="Time Interval Per Batch [Min. 15 Seconds & Max. 900 Seconds allowed]">

            </div>  
            <div class="md:w-1/2 px-3">
                <label class="uppercase tracking-wide text-black mb-2" for="title">
                  Calls Per Batch*
                </label>
                <input type="number" name="file_count" class="count w-full bg-gray-200 text-black border border-gray-200 rounded py-3 px-4 mb-3" id="file_count" min="1" max="32" placeholder="Calls Per Batch [Min. 1 & Max. 32 allowed]" title="Calls Per Batch [Min. 1 & Max. 32 allowed]">
            </div>
            
        </div>


	<!-- Input Fields for Input and Output File Paths (Hidden) -->
        <div class="-   mx-3 md:flex mb-6" style='display: none;'>
            <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="uppercase tracking-wide text-black mb-2" for="title">
                    Input File path*
                </label>
                <input name="input_file_path"  class="input w-full bg-gray-200 text-black border border-gray-200 rounded py-3 px-4 mb-3" id="input_file_path"
                    type="text" id="input_path" readonly value="/opt/lampp/htdocs/obd_call/public/" placeholder="Input File Path" >

            </div>  
            <div class="md:w-1/2 px-3">
                <label class="uppercase tracking-wide text-black mb-2" for="title">
                    Output File path*
                </label>
                <input name="output_file_path"  class="output w-full bg-gray-200 text-black border border-gray-200 rounded py-3 px-4 mb-3" id="output_file_path"
                    type="text" id="path"value="/var/spool/asterisk/outgoing"readonly placeholder="Output File Path" >
            </div>
        </div>

	
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
            <div class="md:w-1/2 px-3 mb-4 md:mb-0">
                <a href="{{ route('createcampaign') }}">
                    <button type="button" id="clear_btn"
                    class=" md:w-full mt-7 bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full">
                    Clear
                </button></a>
            </div>
            <div class="md:w-1/2 px-3 mb-4 md:mb-0">
                <button type="submit" name="submit_btn" id="submit_btn"
                    class="md:w-full mt-7 bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full">
                    Submit
                </button>
            </div>
            <div class="md:w-1/2 px-3 mb-4 md:mb-0">
                <a href="{{ route('cancel') }}">
                    <button type="button" id="cancel_btn"
                    class=" md:w-full mt-7 bg-gray-900  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full">
                    Cancel
                </button></a>
            </div>
        </div>

        
        
    </form>
    
</div>



 <div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Other confirmation content here -->
                <p><strong>Invalid Numbers:</strong> <span id="invalidNumberCount"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div> 



</html>

@endsection
  





