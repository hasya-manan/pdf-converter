<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Converter</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- This loads your CSS and JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- This is where Vue will inject itself -->
    <div id="app"></div>
</body>
</html>