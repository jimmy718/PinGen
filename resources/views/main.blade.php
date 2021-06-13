<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="{{ mix('js/app.js') }}" defer></script>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        <title>PIN Generator</title>
    </head>
    <body>
        <div id="app">
            <pin-generator-page></pin-generator-page>
        </div>
    </body>
</html>
