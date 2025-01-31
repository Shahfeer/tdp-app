<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>OBD CALL</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    
</head>
<body style="background: url('resources/image/icons/IVR.jpg') no-repeat center center fixed; background-size: cover;">

{{--<body class="bg-gray-200 min-h-screen font-base"> --}}
<div id="app">
    @yield('content')
</div>
</body>
</html>
