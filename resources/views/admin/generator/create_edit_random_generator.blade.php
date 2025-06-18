@extends('admin.layout')

@section('admin-title')
    {{ $generator->id ? 'Edit' : 'Create' }} Generator
@endsection

@section('admin-content')
    {!! breadcrumbs([
        'Admin Panel' => 'admin',
        'Random Generators' => 'admin/data/random',
        ($generator->id ? 'Edit' : 'Create') . ' Generator' => $generator->id ? 'admin/data/random/generator/edit/' . $generator->id : 'admin/data/random/generator/create',
    ]) !!}

    <h1>{{ $generator->id ? 'Edit' : 'Create' }} Generator
        @if ($generator->id)
            <a href="#" class="btn btn-danger float-right delete-generator-button">Delete Category</a>
        @endif
    </h1>

    {!! Form::open(['url' => $generator->id ? 'admin/data/random/generator/edit/' . $generator->id : 'admin/data/random/generator/create', 'files' => true]) !!}


    <div class="form-group">
        {!! Form::label('Name') !!}
        {!! Form::text('name', $generator->name, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('Generator Image (Optional)') !!} {!! add_help('This image is used on the generator index and on the generator page as a header.') !!}
        <div>{!! Form::file('image') !!}</div>
        <div class="text-muted">Recommended size: None (Choose a standard size for all generator images)</div>
        @if ($generator->has_image)
            <div class="form-check">
                {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input']) !!}
                {!! Form::label('remove_image', 'Remove current image', ['class' => 'form-check-label']) !!}
            </div>
        @endif
    </div>

    <div class="form-group">
        {!! Form::label('Description (Optional)') !!}
        {!! Form::textarea('description', $generator->description, ['class' => 'form-control wysiwyg']) !!}
    </div>

    <div class="form-group">
        {!! Form::checkbox('is_active', 1, $generator->id ? $generator->is_active : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
        {!! Form::label('is_active', 'Set Active', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned off, the generator will not be visible to regular users.') !!}
    </div>

    <div class="text-right">
        {!! Form::submit($generator->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.delete-generator-button').on('click', function(e) {
                e.preventDefault();
                loadModal("{{ url('admin/data/random/generator/delete') }}/{{ $generator->id }}", 'Delete Generator');
            });
        });
    </script>
@endsection
