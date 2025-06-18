@extends('home.layout')

@section('home-title')
    Random Generators Index
@endsection

@section('home-content')
    {!! breadcrumbs(['Home' => 'home', 'Generators' => 'world/generators']) !!}

    <h1>
        Random Generators
    </h1>

    <div class="row shops-row">
        @foreach ($generators as $generator)
            <div class="col-md-3 col-6 mb-3 text-center">
                @if ($generator->has_image)
                    <div class="generator-image">
                        <a href="{{ $generator->url }}"><img src="{{ $generator->generatorImageUrl }}" alt="{{ $generator->name }}" /></a>
                    </div>
                @endif
                <div class="generator-name mt-1">
                    <a href="{{ $generator->url }}" class="h5 mb-0">{{ $generator->name }}</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
