<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Vite Scripts and Styles -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="font-sans antialiased">
    <div id="app">

    </div>
</body>
</html>
