@extends('layouts.auth')

@section('content')
    <link rel="stylesheet" type="text/css" href="https://yourpostman.in/sms_portal/libraries/assets/icon/icofont/css/icofont.css">

    <style>
    .bg-gray-700{
        background-color:#00ee5a !important;
        color:black;
    }
    body {
    font-family: 'Nunito', 'Segoe UI', sans-serif; /* Primary: Nunito, Fallback: Segoe UI */
}

</style>
    <div class="min-h-screen flex items-center bg-red-200" style="align-items: start; padding-top: 3%;">
	<div><img src="https://yourpostman.in/whatsapp_report_portal/assets/img/cm-logo.png" alt="logo" style=" width: 75%;"> </div>
        <div class="bg-white w-full max-w-lg rounded-lg shadow overflow-hidden mx-auto">
            <div class="py-4 px-6">
                <!-- <div class="text-center font-bold text-black text-3xl">Celeb Media</div> -->
                <!-- <div class="mt-1 text-center font-black text-gray-600 text-4xl" style="font-weight: 900 !important;">OBD Call - Login</div> -->

                <div class="mt-1 text-center font-bold text-gray-900 text-xl" style="font-weight: 800 !important;">OBD Call - Login</div>
                <!-- <div class="mt-1 text-center text-gray-600">Login</div> -->
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    @if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
                    <div class="mt-4 w-full">
                        <input type="email" name="email" placeholder="Email address" 
                               class="w-full mt-2 py-3 px-4 bg-gray-100 text-gray-700 border border-gray-300 rounded block appearance-none placeholder-gray-500 focus:outline-none focus:bg-white"
				oninput="clearError('email', 'login-error')" title="Enter your Email address"/>
                        @error('email')
                        <p id="email-error" class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <div class="mt-4 w-full">
                        <input type="password" name="password" id="password" placeholder="Password" title="Enter your Password" 
                               class="w-full mt-2 py-3 px-4 bg-gray-100 text-gray-700 border border-gray-300 rounded block appearance-none placeholder-gray-500 focus:outline-none focus:bg-white" style="width: 90%; float: left; border-right: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;"
				oninput="clearError('password', 'login-error')" />
                        <div class="w-full mt-2 py-3 px-4 bg-gray-100 text-gray-700 border border-gray-300 rounded block appearance-none placeholder-gray-500 focus:outline-none focus:bg-white" style="width: 10%; float: right; padding-top: 15px !important; padding-bottom: 17px !important; padding-left: 13px !important; cursor: pointer; border-left: 0px; border-top-left-radius: 0px; border-bottom-left-radius: 0px;" onclick="password_visible()" title="click to show/hide the Password">
				<span class="input-group-addon" id="password_visiblitity"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L525.6 386.7c39.6-40.6 66.4-86.1 79.9-118.4c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C465.5 68.8 400.8 32 320 32c-68.2 0-125 26.3-169.3 60.8L38.8 5.1zM223.1 149.5C248.6 126.2 282.7 112 320 112c79.5 0 144 64.5 144 144c0 24.9-6.3 48.3-17.4 68.7L408 294.5c8.4-19.3 10.6-41.4 4.8-63.3c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3c0 10.2-2.4 19.8-6.6 28.3l-90.3-70.8zM373 389.9c-16.4 6.5-34.3 10.1-53 10.1c-79.5 0-144-64.5-144-144c0-6.9 .5-13.6 1.4-20.2L83.1 161.5C60.3 191.2 44 220.8 34.5 243.7c-3.3 7.9-3.3 16.7 0 24.6c14.9 35.7 46.2 87.7 93 131.1C174.5 443.2 239.2 480 320 480c47.8 0 89.9-12.9 126.2-32.5L373 389.9z"/></svg></span>

                        </div>
                        <div style="clear: both"></div>
                        @error('password')
                        <p id="password-error" class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
		    <div class="mt-4 w-full">
                    @error('login')
                    <p id="login-error" class="text-red-500 text-xs mt-1">
                        {{ $message }}
                    </p>
                    @enderror
                </div>
                    <div class="flex justify-between items-center mt-6">
			{{---<a href="{{ route('forgot-password') }}" class="text-gray-600 text-sm hover:text-gray-500">Forgot password?</a>---}}
                        <button type="submit" style="margin-left: 45%; font-family: 'Nunito', 'Segoe UI', sans-serif !important;"
                                class="py-2 px-4 bg-gray-700 text-white rounded hover:bg-gray-600 focus:outline-none">
                            Login
                        </button>
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-center py-4 bg-gray-100 text-center" style="background-color: #00ee5a; color: black !important;">
                <h1 class="text-white-600 text-sm" style=" font-family: 'Nunito', 'Segoe UI', sans-serif !important;">Don't have an account</h1>
                <a href="{{ route('register') }}" class="text-white-600 font-bold mx-2 text-sm hover:text-white-500">Register now</a>
            </div>
        </div>
    </div>
@endsection 

<script>
    function password_visible() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
            // document.getElementById('password_visiblitity').innerHTML = '<i class="icofont icofont-eye"></i>';
	    document.getElementById('password_visiblitity').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.0 by @fontawesome -https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>';
        } else {
            x.type = "password";
            // document.getElementById('password_visiblitity').innerHTML = '<i class="icofont icofont-eye-blocked"></i>';
	    document.getElementById('password_visiblitity').innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512"><!--! Font Awesome Free 6.4.0 by @fontawesome -https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L525.6 386.7c39.6-40.6 66.4-86.1 79.9-118.4c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C465.5 68.8 400.8 32 320 32c-68.2 0-125 26.3-169.3 60.8L38.8 5.1zM223.1 149.5C248.6 126.2 282.7 112 320 112c79.5 0 144 64.5 144 144c0 24.9-6.3 48.3-17.4 68.7L408 294.5c8.4-19.3 10.6-41.4 4.8-63.3c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3c0 10.2-2.4 19.8-6.6 28.3l-90.3-70.8zM373 389.9c-16.4 6.5-34.3 10.1-53 10.1c-79.5 0-144-64.5-144-144c0-6.9 .5-13.6 1.4-20.2L83.1 161.5C60.3 191.2 44 220.8 34.5 243.7c-3.3 7.9-3.3 16.7 0 24.6c14.9 35.7 46.2 87.7 93 131.1C174.5 443.2 239.2 480 320 480c47.8 0 89.9-12.9 126.2-32.5L373 389.9z"/></svg>';
        }
    }

function clearError(fieldId, loginErrorId) {
        const errorElement = document.getElementById(fieldId + '-error');
        if (errorElement) {
            errorElement.textContent = ''; // Clear the field-specific error message
        }
        
        // Clear the "Invalid email or password" error
        const loginErrorElement = document.getElementById(loginErrorId);
        if (loginErrorElement) {
            loginErrorElement.textContent = '';
        }
    }

    // Clear the "Invalid email or password" error when typing in the email or password fields
    document.querySelector('input[name="email"]').addEventListener('input', function () {
        clearError('email', 'login-error');
    });

    document.querySelector('input[name="password"]').addEventListener('input', function () {
        clearError('password', 'login-error');
    });

</script>







