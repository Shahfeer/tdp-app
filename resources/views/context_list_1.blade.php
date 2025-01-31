<!-- Extends the 'layouts.app' view template and begins the 'content' section -->
@extends('layouts.app')
<!-- Starts the 'content' section -->
@section('content')

<!-- 
<script>
let isPlaying = false;
document.addEventListener('click', function (event) {
alert("CAME"+event.target.classList.contains('play-pause'));
    // if (event.target.classList.contains('play-pause')) {
        var audioUrl = event.target.getAttribute('data-audio');
        var audioElement = document.getElementById('audioPlayer');
        var audioSource = document.getElementById('audioSource');

        var baseUrl = '/obd_call_neron/storage/app/convert_prompt_files/';

        // If audio is paused or a different audio is selected, play it
        if (audioSource.src !== baseUrl + audioUrl) {
            audioSource.src = baseUrl + audioUrl;
            audioElement.load();
        }

alert(audioElement.paused+"========"+isPlaying);
        if (isPlaying == false) {
        alert("Played");
            // If audio is paused, play it
            audioElement.play().then(function () {
		alert("first_Play")
		isPlaying = true;
		audioElement.play();
                event.target.innerHTML = '';
                event.target.innerHTML = '<i class="fas fa-pause"></i>';
            }).catch(function (error) {
		isPlaying = false;
		alert("second_Play")
		// audioElement.pause();
	        // event.target.innerHTML = '<i class="fas fa-play"></i>';
                console.error('Audio play error:', error);
            });
        } else {
alert("Paused");
	    isPlaying = true;
            // If audio is playing, pause it
            audioElement.pause();
	    event.target.innerHTML = '';
            event.target.innerHTML = '<i class="fas fa-play"></i>';
        }


    // }
});
</script>
-->

<style>
.card { border: 0px solid rgba(0,0,0,.125) !important; }
.card-body { padding: 0rem !important; }
</style>
<div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4 flex flex-col">               
    <div class="px-3" style="background-color: #FFF; height: 50px; padding-top: 8px;">
        <h2 class="text-2xl font-medium">Prompt List</h2>
    </div>


   <!-- Create New Campaign Button -->
<div class="d-flex justify-content-end">
    <a href="{{ route('context_create') }}" class="btn btn-success" style="align:Right;">Create a New Prompt</a>
</div>
	

    <!-- Campaign List Table -->    
<div class="card mt-4">
    <div class="card">
        <div class="col card-body table-responsive">
            <table class="context_list-table hover stripe" id="context_list-table"   style="width:100%">
                <thead>
                    <tr>
                    <th>No.</th>
		  @if(Auth::user()->user_master_id === 1)
                <th>User Name</th>
                @endif
                    <th>Context</th>
                    <th>Prompt Name</th>
                    <th>Entry Time</th>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

<audio id="audioPlayer" controls style="display: none;">
    <source id="audioSource" src="" type="audio/wav">
</audio>


 <script>
let isPlaying = false;
function playAudio(audioUrl, audio_id) {
console.log("Audio function");

alert(audioUrl+"===="+isPlaying);   
/*    var baseUrl = '/obd_call_neron/storage/app/convert_prompt_files/';

    var audioElement = document.getElementById('audioPlayer');
    var audioSource = document.getElementById('audioSource');
	
    // Set the audio source URL
    audioSource.src = baseUrl + audioUrl;

    // Load and play the audio
    audioElement.load();
    audioElement.play();  */


    var baseUrl = '/obd_call_neron/storage/app/convert_prompt_files/';

    var audioElement = document.getElementById('audioPlayer');
    var audioSource = document.getElementById('audioSource');


    // If audio is paused or a different audio is selected, play it
    // if (audioElement.paused || audioSource.src !== baseUrl + audioUrl) {
    if(isPlaying == false) {
	alert("!!");
        audioSource.src = baseUrl + audioUrl;
        audioElement.load();
        audioElement.play();
	isPlaying = true;
        document.getElementById('play-pause').innerHTML = '<i class="fas fa-pause"></i>';
	// document.getElementById('audioid_'+audio_id).innerHTML = '<i class="fas fa-pause"></i>';
    } else {
	alert("@@");
        // If audio is playing, pause it
	if (!audioElement.paused) {
        audioElement.pause();
	isPlaying = false;
        document.getElementById('play-pause').innerHTML = '<i class="fas fa-play"></i>';
	// document.getElementById('audioid_'+audio_id).innerHTML = '<i class="fas fa-play"></i>';
	}
    }

}

</script>

<!--
<script>

document.addEventListener('click', function (event) {
    if (event.target.classList.contains('play-pause')) {
        var audioUrl = event.target.getAttribute('data-audio');
        var audioElement = document.getElementById('audioPlayer');
        var audioSource = document.getElementById('audioSource');

        var baseUrl = '/obd_call_neron/storage/app/convert_prompt_files/';
	let isPlaying = false;

        // If audio is paused or a different audio is selected, play it
        if (audioSource.src !== baseUrl + audioUrl) {
            audioSource.src = baseUrl + audioUrl;
            audioElement.load();
        }

alert(audioElement.paused);
        if (audioElement.paused) {
	alert("Played");
            // If audio is paused, play it
            audioElement.play().then(function () {
                event.target.innerHTML = '<i class="fas fa-pause"></i>';
            }).catch(function (error) {
                console.error('Audio play error:', error);
            });
        } else {
alert("Paused");
            // If audio is playing, pause it
            audioElement.pause();
            event.target.innerHTML = '<i class="fas fa-play"></i>';
        }
	

    }
});

</script> -->

@endsection

