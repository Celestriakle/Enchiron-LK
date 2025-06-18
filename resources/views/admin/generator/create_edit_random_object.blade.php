@extends('admin.layout')

@section('admin-title')
    {{ $object->id ? 'Edit' : 'Create' }} Random Generator Object
@endsection

@section('admin-content')
    {!! breadcrumbs([
        'Admin Panel' => 'admin',
        'Random Generators' => 'admin/data/random',
        $generator->name => 'admin/data/random/generator/view/' . $generator->id,
        ($object->id ? 'Edit' : 'Create') . ' Object' => $object->id ? 'admin/data/random/edit/' . $object->id : 'admin/data/random/create',
    ]) !!}

    <h1>{{ $object->id ? 'Edit' : 'Create' }} {{ $generator->name }} Object
        @if ($object->id)
            <a href="#" class="btn btn-danger float-right delete-object-button">Delete Object</a>
        @endif
    </h1>

    {!! Form::open(['url' => $object->id ? 'admin/data/random/edit/' . $object->id : 'admin/data/random/create', 'files' => true]) !!}


    <div class="form-group">
        {!! Form::label('Text') !!}
        {!! Form::text('text', $object->text, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Link') !!}
        {!! Form::text('link', $object->link, ['class' => 'form-control']) !!}
    </div>

    {{ Form::hidden('random_generator_id', $object->random_generator_id ? $object->random_generator_id : $generator->id) }}

    <div class="text-right">
        {!! Form::submit($object->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.delete-object-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/data/random/delete') }}/{{ $object->id }}", 'Delete Object');
            });
        });
    </script>
@endsection
