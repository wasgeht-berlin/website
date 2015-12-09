<!DOCTYPE html>
<html>
<head>
    <title>was geht</title>
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.css" />
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
                    <li><a href="/calendar">Kalender</a></li>
                    <li><a href="/map">Karte</a></li>
                    <li><a href="/contribute">Mitmachen</a></li>
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