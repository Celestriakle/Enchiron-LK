@if ($object)
    {!! Form::open(['url' => 'admin/data/random/delete/' . $object->id]) !!}

    <p>You are about to delete the object <strong>{{ $object->text }}</strong>. This is not reversible.</p>
    <p>Are you sure you want to delete <strong>{{ $object->text }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Object', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else
    Invalid object selected.
@endif
