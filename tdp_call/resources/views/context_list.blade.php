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

  .bg-gray-900 {
    background-color: #00ee5a !important;
    color:black !important;
  }
  .play-pause{
    color:black !important;
    display: inline-block;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #01fc60 !important;
    /* background: #91a0fc !important;  */
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

.entry-time-header {
            white-space: nowrap; /* Prevents line break in the header */
            min-width: 150px; /* Adjust the width as necessary */
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
</script>


<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">
  <div class="px-3" style = "text-align: center; color: black; height: 50px; padding-top: 8px;">
   <h2 class="text-2xl font-medium" style="font-weight: bold;">Prompt List</h2>
	<!-- <span class="mx-2 text-black text-xl uppercase font-bold"> Prompt List </span> -->
  </div>

  <!-- Create New Campaign Button -->
  <div class="d-flex justify-content-end">
  <a href="{{ route('context_create') }}" class="md:w-full bg-gray-900 text-white py-2 px-4 border-b-4 border-gray-500 rounded-full hover:border-gray-100" style="text-align: center; width: 220px;">Create a New Prompt</a>

  </div>


  <!-- Campaign List Table -->
  <div class="card mt-4">
    <div class="card">
      <div class="col card-body table-responsive">
        <table class="context_list-table hover stripe" id="context_list-table" style="width:100%">
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
              <th>Prompt Status</th>
              <th class="entry-time-header">Entry Time</th>
              <th class="noExport">Action</th>
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


