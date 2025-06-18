@extends('home.layout')

@section('home-title')
    {{ $generator->name }}
@endsection

@section('content')
    {!! breadcrumbs(['Home' => 'home', 'Generators' => 'generators', $generator->name => $generator->url]) !!}
    <h1>{{ $generator->name }}</h1>

    <div class="text-center">

        @if ($generator->has_image)
            <img src="{{ $generator->generatorImageUrl }}" style="max-width:100%" alt="{{ $generator->name }}" />
        @endif
        <p>{!! $generator->parsed_description !!}</p>

        <hr>

        <p>
        <div id="randomValue"><br></div>
        </p>

        <button class="btn btn-primary" type="button" onclick="gen()">Generate</button>

    </div>
@endsection

@section('scripts')
    <script>
        var values = {!! json_encode($objects) !!};

        function gen() {

            var n1 = values[Math.floor(Math.random() * values.length)];

            if (n1.link != null) {
                document.getElementById("randomValue").innerHTML = '<a href="' + n1.link + '">' + n1.text + '</a>';
            } else {
                document.getElementById("randomValue").innerHTML = n1.text;
            }
        }
    </script>
@endsection
