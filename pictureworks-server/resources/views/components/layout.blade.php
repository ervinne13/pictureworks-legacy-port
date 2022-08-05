<!DOCTYPE HTML>
<html>

<head>
    <title>{{ $title ?? 'Pictureworks' }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}" />
    <noscript>
        <link rel="stylesheet" href="{{asset('assets/css/noscript.css')}}" />
    </noscript>
</head>

<body class="is-preload">
    <div id="wrapper">
        {{ $slot }}
    </div>
    <script>
        if ('addEventListener' in window) {
            window.addEventListener('load', function() {
                document.body.className = document.body.className.replace(/\bis-preload\b/, '');
            });
            document.body.className += (navigator.userAgent.match(/(MSIE|rv:11\.0)/) ? ' is-ie' : '');
        }
    </script>
</body>

</html>