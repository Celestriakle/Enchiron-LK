@if ($generator)
    {!! Form::open(['url' => 'admin/data/random/generator/delete/' . $generator->id]) !!}

    <p>You are about to delete the generator <strong>{{ $generator->name }}</strong>. This is not reversible. If this generator has at least one object, you will not be able to delete it.</p>
    <p>Are you sure you want to delete <strong>{{ $generator->name }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Generator', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid generator selected.
@endif
