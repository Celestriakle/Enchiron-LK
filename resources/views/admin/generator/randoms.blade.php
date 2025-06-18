@extends('admin.layout')

@section('admin-title')
    Random Generators
@endsection

@section('admin-content')
    {!! breadcrumbs(['Admin Panel' => 'admin', 'Random Generators' => 'admin/data/random']) !!}

    <h1>Random Generators</h1>

    <p>This is a list of the different random generators you have created.</p>
    <p>Click "View" to add objects to that generator.</p>

    <div class="text-right mb-3"><a class="btn btn-primary" href="{{ url('admin/data/random/generator/create') }}"><i class="fas fa-plus"></i> Create New Generator</a></div>
    @if (!count($generators))
        <p>No generators found.</p>
    @else
        <table class="table table-sm shop-table">
            <thead>
                <th>Name</th>
                <th></th>
            </thead>
            <tbody id="sortable" class="sortable">
                @foreach ($generators as $generator)
                    <tr class="sort-item" data-id="{{ $generator->id }}">
                        <td>
                            <a class="fas fa-arrows-alt-v handle mr-3" href="#"></a>
                            <a href="{{ $generator->url }}">{!! $generator->name !!}</a>
                        </td>
                        <td class="text-right">
                            <a href="{{ url('admin/data/random/generator/view/' . $generator->id) }}" class="btn btn-primary">View</a>
                            <a href="{{ url('admin/data/random/generator/edit/' . $generator->id) }}" class="btn btn-primary">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mb-4">
            {!! Form::open(['url' => 'admin/data/random/generator/sort']) !!}
            {!! Form::hidden('sort', '', ['id' => 'sortableOrder']) !!}
            {!! Form::submit('Save Order', ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    @endif

@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('.handle').on('click', function(e) {
                e.preventDefault();
            });
            $("#sortable").sortable({
                items: '.sort-item',
                handle: ".handle",
                placeholder: "sortable-placeholder",
                stop: function(event, ui) {
                    $('#sortableOrder').val($(this).sortable("toArray", {
                        attribute: "data-id"
                    }));
                },
                create: function() {
                    $('#sortableOrder').val($(this).sortable("toArray", {
                        attribute: "data-id"
                    }));
                }
            });
            $("#sortable").disableSelection();
        });
    </script>
@endsection
