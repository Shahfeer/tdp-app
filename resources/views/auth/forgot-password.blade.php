
@extends('layouts.auth')

@section('content')

    <div class="min-h-screen flex items-center bg-red-200">
        <div class="bg-white w-full max-w-lg rounded-lg shadow overflow-hidden mx-auto">
            <div class="py-4 px-6">
                <div class="text-center font-bold text-gray-700 text-3xl">Celeb Media</div>
                <div class="mt-1 text-center font-bold text-gray-600 text-xl">OBD Call</div>
                <div class="mt-1 text-center text-gray-600">Forgot Password</div>

		<form action="{{ route('verify-otp') }}" method="POST">
                    @csrf
                    <div class="mt-4 w-full">
                        <input type="email" name="email" id="email" placeholder="Enter your email address" class="w-full mt-2 py-3 px-4 bg-gray-100 text-gray-700 border border-gray-300 rounded block appearance-none placeholder-gray-500 focus:outline-none focus:bg-white" />
                    </div>

		   <div class="text-red-500 text-xs italic mt-1" id="email-error" style="display: none;">
    			Incorrect email. Please enter the correct email.
		  </div>
				

                    <div class="mt-4 w-full">
                        <input type="text" name="otp" id="otp" placeholder="Enter OTP" class="w-full mt-2 py-3 px-4 bg-gray-100 text-gray-700 border border-gray-300 rounded block appearance-none placeholder-gray-500 focus:outline-none focus:bg-white" />

			@if($message = Session::get('otp-error'))
    			<span id="otp-error" class="text-red-500 text-xs italic">{{ $message }}</span>
			@endif
                    </div>

		  <div class="text-red-500 text-xs italic mt-1" id="otp-sent-message" style="display: none;">
                        OTP has been sent to the email
                  </div>
	

            	    <div style="clear: both"></div>

                    <div class="flex justify-between items-center mt-6">
                        <a href="{{ route('login') }}" class="text-gray-600 text-sm hover:text-gray-500">Back to Login</a>
			   <button type="submit" id="verify-button" class="py-2 px-4 bg-gray-700 text-white rounded hover:bg-gray-600 focus:outline-none" disabled>
                            Verify OTP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#email').on('blur', function () {
		var email = $(this).val();

                // Make an AJAX request to send the OTP when the email field is filled
                $.ajax({
                    type: 'POST',
                    url: '{{ route('send-otp') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        email: email,
                    },
		    success: function (data) {
			// Display a message when the OTP is sent
			//$('#email-error').show();
			//$('#email').val('');
			//$('#verify-button').prop('disabled', true);
			
			$('#otp-sent-message').show();

                // Enable the "Verify OTP" button
                $('#verify-button').prop('disabled', false);
	

            },
            error: function (xhr) {
                // Handle other errors
                console.error(xhr.responseText);
                //$('#otp-sent-message').show();

		// Enable the "Verify OTP" button
                //$('#verify-button').prop('disabled', false);

		$('#email-error').show();
                $('#email').val('');
                $('#verify-button').prop('disabled', true);

            }
        });
    });
});

$('#email').on('input', function () {
        $('#email-error').hide();
	$('#otp-error').hide();

    });

$('#otp').on('input', function () {
        $('#otp-sent-message').hide();
	$('#otp-error').hide();

    });
    </script>
@endsection



