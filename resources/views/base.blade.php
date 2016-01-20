<!DOCTYPE html>
<html>
<head>
    <title>was geht</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lib.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />
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

    <div class="row">
        <footer class="col-xs-12">
            <ul class="list-unstyled list-inline text-center">
                <li><a href="/about">Über</a></li>
                <li><a href="/api">API</a></li>
                <li><a href="https://github.com/wasgeht-berlin/">GitHub</a></li>
            </ul>
        </footer>
    </div>
</div>

<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
</html>