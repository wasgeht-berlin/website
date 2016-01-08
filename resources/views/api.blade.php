@extends ('base')

@section ('content')
    <div class="col-xs-12 col-md-6 col-md-offset-3">
        <h2>API</h2>

        <p>
            Wir stellen eine einfache JSON-API zur Verfügung, die das Abfragen von
            Ereignissen und Orten zulässt.
        </p>

        <h3>Ereignisse</h3>

        <code>/api/v1/event/:id</code>

        <h4>Objekt-Struktur:</h4>

        <pre><code>{{ $eventExample }}</code></pre>

        <h3>Orte</h3>

        <code>/api/v1/location/:id</code>

        <h4>Objekt-Struktur:</h4>

        <pre><code>{{ $locationExample }}</code></pre>

        <h3>Paginierung</h3>

        <code>/api/v1/event/?limit=10&page=2</code>

        <p>
            Sollte eine auszugebende Auflistung mehr als 20 Elemente enthalten, wird sie automatisch paginiert.
            Das ausgegebene JSON enthält dann neben dem <code>data</code>-Array ein <code>meta</code>-Array mit
            folgender Struktur:
        </p>

        <pre><code>{{ $paginationExample }}</code></pre>

        <p>
            Um zwischen Seiten zu wechseln kann der <code>page</code>-Parameter verwendet werden. Zur Anpassung
            der Elemente pro Seite, kann der <code>limit</code>-Parameter mit angegeben werden.
        </p>
    </div>
@stop
