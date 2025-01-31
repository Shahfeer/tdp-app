<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')

<style>
  .card {
    border: 0px solid rgba(0, 0, 0, .125) !important;
  }

  .card-body {
    padding: 0rem !important;
  }

  .modal-header {
    background-color: #5cb85c;
    color: white;
    text-align: center;
    /* Center-align text */
    padding: 10px 30px;

  }
  .bg-gray-900{
    background-color: #00ee5a !important;
    color:black !important;
}
.bg-gray-300{
background-color: #fa8072 !important;
}

.text-white {
    color: black !important;
    background: lawngreen;
}
.bg-gray-800{
  background-color:lawngreen !important;
  color:black !important;

}
.border-gray-300{
  background-color:#00ee5a !important;
  color:black !important;

}
.border-gray-400{
  background-color: #f08787 !important;
  color:black !important;

}
.play-pause{
    color:black !important;
    display: inline-block;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    /* background: #91a0fc !important;  */
    background: #01fc60 !important;
    /* box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2); Soft shadow for depth */
    color: white; /* Icon color */
    text-align: center;
    vertical-align: middle;
    font-size: 20px; /* Slightly larger icon size */
    /* line-height: 45px; Vertically center icon */
    transition: all 0.3s ease-in-out; /* Smooth hover effect */
    cursor: pointer;
    padding: 5px;
    gap: 20px;
  }
  .custom-btn-one {
    display: inline-block;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #91a0fc !important; 
    /* background: #01fc60 !important; */
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2); /* Soft shadow for depth */
    color: white; /* Icon color */
    text-align: center;
    vertical-align: middle;
    font-size: 20px; /* Slightly larger icon size */
    /* line-height: 45px; Vertically center icon */
    transition: all 0.3s ease-in-out; /* Smooth hover effect */
    cursor: pointer;
    padding: 5px;
    gap: 20px;
}



.custom-btn:hover {
    background: linear-gradient(145deg, #2575fc, #6a11cb); /* Hover effect with color inversion */
    transform: translateY(-2px); /* Slight upward motion on hover */
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); /* Enhance shadow on hover */
}

</style>


<script>
  // Function to reload the page
  function reloadPage() {
    location.reload();
  }

  // Set a timer to reload the page every 5000 milliseconds (5 seconds)
  setTimeout(reloadPage, 60000);


  function select_sender(prompt_id) {
    $('#senderModal').modal('show');

    // Get the button element by its ID
    const approveButton = document.getElementById('approveButton');

    // Add click event listener
    approveButton.addEventListener('click', function() {
      // Reload the page when the button is clicked
      // window.location.reload();
      $.ajax({
        url: 'approve-ivr',
        method: 'POST',
        data: {
          "prompt_id": prompt_id,
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {

          $('#senderModal').modal('hide');

          var message = response.message;
          // Assuming response.data is a JSON-encoded string

          $('#responseMessage').text(message);

          // Show the modal or display element
          $('#responseModal').modal('show');

          var table = $('#ivr_approve-table').DataTable();
          table.draw();
        },
        error: function(error) {
          console.log('Error in Process');
        }
      });
    });

  }

  function decline_ivr(prompt_id) {
    // Clear previous remarks and error message before showing the modal
    $('#remarks').val(''); // Clear the remarks input field
    $('#remarksError').text(''); // Clear any previous validation messages

    $('#decline-Modal').modal('show');

    // Get the button elements by their IDs
    const declineButton = document.getElementById('declineBtn');
    const cancelButton = document.getElementById('cancelBtn');

    // Add click event listener for the decline button
    declineButton.onclick = function() {
        var remarks = $('#remarks').val();

        // Check if the remarks are empty
        if (remarks.trim() === '') {
            $('#remarksError').text('Remark is required.'); // Display validation message
            return; // Prevent form submission
        } else {
            $('#remarksError').text(''); // Clear validation message
        }

        // Check if the remarks length is between 5 and 30 characters
        if (remarks.trim().length < 5 || remarks.trim().length > 30) {
            $('#remarksError').text('Remarks should be between 5 and 30 characters.'); // Display validation message
            return; // Prevent further processing or form submission
        } else {
            $('#remarksError').text(''); // Clear validation message
        }

        // Proceed with AJAX request
        $.ajax({
            url: 'decline-ivr',
            method: 'POST',
            data: {
                "prompt_id": prompt_id,
                "remarks": remarks,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#decline-Modal').modal('hide');

                var message = response.message;
                $('#decline_message').text(message);
                $('#decline_modal').modal('show');

                var table = $('#ivr_approve-table').DataTable();
                table.draw();
            },
            error: function(error) {
                console.log('Error in Process');
            }
        });
    };

    // Add click event listener for the cancel button
    cancelButton.onclick = function() {
        $('#remarks').val(''); // Clear the remarks input field
        $('#remarksError').text(''); // Clear any validation message
        $('#decline-Modal').modal('hide'); // Optionally hide the modal
    };
}

</script>

<!-- sender id model if the status is active -->
<div class="modal fade bs-example-modal-md" id="senderModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="confirmationModal" aria-hidden="true" data-backdrop="false" style=" position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 500px;">
  <div class="modal-dialog">
    <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
      <div class="modal-header" style="border-top: 0px inset green; text-align: center;">
        <h5 class="modal-title">Approve Campaign</h5>
      </div>
      <div class="modal-body">
        <p><strong> Are you sure want to approve ? </strong> <span id="campaign_name" class="campaign_name"></span></p>
        <div id="senderIds">
        </div>
      </div>
      <div class="modal-footer" style="background-color: #f8f9fa;">
        <button type="button" id= "cancelButton" class=" bg-gray-300 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal">Cancel</button>
        <button type="button" id="approveButton" class=" bg-gray-800 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal" >Approve</button>
      </div>
    </div>
  </div>
</div>

<!-- Approve response modal -->
<div class="modal fade bs-example-modal-md" id="responseModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="noChannelsModal" aria-hidden="true" data-backdrop="false" style=" position:fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
  <div class="modal-dialog">
    <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
      <div class="modal-header" style="border-top: 0px inset red; text-align: center;">
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
<div class="modal fade bs-example-modal-md" id="decline-Modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="confirmationModal" aria-hidden="true" data-backdrop="false" style=" position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 500px;">
  <div class="modal-dialog">
    <div class="modal-content" style="width:80%;">

      <div class="modal-header">
        <h5 class="modal-title" id="addCreditModalLabel">Decline Campaign</h5>
      </div>
      <div class="modal-body">
        <p><strong> "Are you sure", want to decline ? </strong> <span id="campaign_name" class="campaign_name"></span></p>
        <div class="form-group">
          <label for="remarks">Remarks:</label>
          <input type="text" autofocus class="form-control" id="remarks" placeholder="min:5 & max:30" required minlength="5" maxlength="30" autocomplete="off">
          <span id="remarksError" class="text-danger"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="cancelButton" class=" bg-gray-300 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" data-dismiss="modal">Cancel</button>
        <button type="button" id="declineBtn" class=" bg-gray-500  text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full">Decline</button>
      </div>
    </div>
  </div>
</div>

<!-- decline response modal -->
<div class="modal fade bs-example-modal-md" id="decline_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="noChannelsModal" aria-hidden="true" data-backdrop="false" style=" position:fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); overflow: visible; padding-right: 15px; width: 400px;">
  <div class="modal-dialog">
    <div class="modal-content" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);">
      <div class="modal-header" style="border-top: 0px inset red; text-align: center;">
        <h5 class="modal-title">Decline Status</h5>
      </div>
      <div class="modal-body">
        <p id="decline_message"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class=" md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 hover:border-b-2 border-gray-500 hover:border-gray-100 rounded-full" id="ok_Button" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">
  <div class="px-3" style="text-align: center; color: black; height: 50px; padding-top: 8px;">
    <h2 class="text-2xl font-medium" style="font-weight: bold;">IVR Approval</h2>
  </div>

  <!-- Create New Campaign Button -->
  <div class="d-flex justify-content-end">
    <a href="{{ route('context_create') }}" class="md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 border-gray-500 rounded-full hover:border-gray-100" style="text-align: center;width: 220px;">Create a New Prompt</a>
  </div>


  <!-- Campaign List Table -->
  <div class="card mt-4">
    <div class="card">
      <div class="col card-body table-responsive">
        <table class="ivr_approve-table hover stripe" id="ivr_approve-table" style="width:100%">
          <thead>
            <tr>
              <th>No.</th>
              @if(Auth::user()->user_master_id === 1)
              <th>User Name</th>
              @endif
              <th>Type</th>
              <th>Context</th>
              <th>Remarks</th>
              <th>Prompt Name</th>
              <th>Entry Time</th>
              <th class="noExport">Action</th>
              <th class="noExport">Approve</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

<audio id="audioPlayer" controls style="display: none;" onended="PlayButtonShow()">
  <source id="audioSource" src="" type="audio/wav">
</audio>

<script>
   let isPlaying = false;
  function playAudio(audioUrl, audio_id) {
    console.log("Audio function");
    //var baseUrl = '/obd_call/storage/app/user_prompt_files/';

     var baseUrl = '{{ config('app.prompt_url') }}';

    var audioElement = document.getElementById('audioPlayer');
    var audioSource = document.getElementById('audioSource');

    // If audio is paused or a different audio is selected, play it
    if (isPlaying == false) {
	var elements = document.getElementsByClassName("play-pause");
	for (var i = 0; i < elements.length; i++) {
  		elements[i].innerHTML = '<i style="color: #000000" class="fas fa-play"></i>';
	}

      audioSource.src = baseUrl + audioUrl;
      audioElement.load();
      audioElement.play();
      isPlaying = true;
      document.getElementById('audioid_' + audio_id).innerHTML = '<i style="color: #ff0000" class="fas fa-stop"></i>';
    } else {
      // If audio is playing, pause it
      if (!audioElement.paused) {
        audioElement.pause();
        isPlaying = false;
        document.getElementById('audioid_' + audio_id).innerHTML = '<i style="color: #000000" class="fas fa-play"></i>';
      }
    }
  }

  function PlayButtonShow(){
	isPlaying = false;
	var elements = document.getElementsByClassName("play-pause");
	for (var i = 0; i < elements.length; i++) {
		elements[i].innerHTML = '<i style="color: #000000" class="fas fa-play"></i>';
	}	
  }
</script>

@endsection
