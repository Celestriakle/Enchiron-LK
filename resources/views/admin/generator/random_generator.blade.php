@extends('admin.layout')

@section('admin-title')
    Random Generators
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Random Generators' => 'admin/data/random', $generator->name => 'admin/data/random/generator/view' . $generator->id]) !!}

    <h1>Random Generator - {!! $generator->name !!}</h1>

    <p>This is a list of the possible options in this generator.</p>

    <div class="text-right mb-3"><a class="btn btn-primary" href="{{ url('admin/data/random/create/' . $generator->id) }}"><i class="fas fa-plus"></i> Create New Object</a></div>
    @if (!count($generator->objects))
        <p>No objects found.</p>
    @else
        <table class="table table-sm">
            <thead>
                <th>Value</th>
                <th>Link</th>
                <th></th>
            </thead>
            <tr>
                @foreach ($generator->objects as $object)
                    <td>
                        {!! $object->text !!}
                    </td>
                    <td>
                        {!! $object->link !!}
                    </td>
                    <td class="text-right">
                        <a href="{{ url('admin/data/random/edit/' . $object->id) }}" class="btn btn-primary">Edit</a>
                        <a href="#" class="btn btn-danger delete-object-button" onclick="deleteObject('{{ $object->id }}')">Delete</a>
                    </td>
            </tr>
    @endforeach
    </tbody>

    </table>
    @endif

@endsection

@section('scripts')
    @parent
    <script>
        function deleteObject(id) {
            loadModal("{{ url('admin/data/random/delete') }}/" + id, 'Delete Object');
        }
    </script>
@endsection
