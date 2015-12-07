<!DOCTYPE html>
<html>
<head>
    <title>was geht</title>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.12.0/mapbox-gl.css' rel='stylesheet' />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}"/>
</head>
<body>
<div class="container-fluid">
    <div class="page-header text-center">
        <h1 class="page-title">
            was geht
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <nav class="centered-pills">
                <ul class="nav nav-pills">
                    <li><a href="/">Liste</a></li>
                    <li><a href="/map">Karte</a></li>
                    <li><a href="/about">Über</a></li>
                </ul>
            </nav>
        </div>
    </div>

    @yield('content')
</div>

<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
</html>