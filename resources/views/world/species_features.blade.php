@extends('world.layout')

@section('world-title')
    {{ $species->name }} Traits
@endsection

@section('content')
    {!! breadcrumbs(['World' => 'world', 'Species' => 'world/species', $species->name => $species->url, 'Traits' => 'world/species/' . $species->id . 'traits']) !!}
    <h1>{{ $species->name }} Traits</h1>

    <p>This is a visual index of all {!! $species->displayName !!}-specific traits. Click a trait to view more info on it!</p>

    @foreach ($features as $categoryId => $categoryFeatures)
        @if (!isset($categories[$categoryId]) || (Auth::check() && Auth::user()->hasPower('edit_data')) || $categories[$categoryId]->is_visible)
            <div class="card mb-3 inventory-category">
                <h5 class="card-header inventory-header">
                    @if (isset($categories[$categoryId]) && !$categories[$categoryId]->is_visible)
                        <i class="fas fa-eye-slash mr-1"></i>
                    @endif
                    {!! isset($categories[$categoryId]) ? '<a href="' . $categories[$categoryId]->searchUrl . '">' . $categories[$categoryId]->name . '</a>' : 'Miscellaneous' !!}
                </h5>
                <span class="px-3 pt-3">{!! isset($categories[$categoryId]) ? $categories[$categoryId]->parsed_description : '' !!}</span>
                <div class="card-body inventory-body">
                    @foreach ($categoryFeatures->chunk(4) as $chunk)
                        <div class="row mb-3">
                            @foreach ($chunk as $featureId => $feature)
                                <div class="col-md-3 col-6 text-center align-self-center inventory-item">
                                    @if ($feature->first()->has_image)
<<<<<<< HEAD
                                        <a class="badge" style="border-radius:.75em; {{ $feature->first()->rarity->color ? 'background-color:#' . $feature->first()->rarity->color : '' }}" href="{{ $feature->first()->url }}">
                                            <img class="my-1 modal-image" style="max-height:100%; height:150px; border-radius:.5em; background-color:rgba(255,255,255, 0.9); padding:.5em;" src="{{ $feature->first()->imageUrl }}" alt="{{ $feature->first()->name }}" data-id="{{ $feature->first()->id }}" />
=======
                                        <a class="badge" style="border-radius:.5em; {{ $feature->first()->rarity->color ? 'background-color:#' . $feature->first()->rarity->color : '' }}" href="{{ $feature->first()->url }}">
                                            <img class="my-1 modal-image" style="max-height:150px; border-radius:.5em;" src="{{ $feature->first()->imageUrl }}" alt="{{ $feature->first()->name }}" data-id="{{ $feature->first()->id }}" />
>>>>>>> 30ef9a9e3ce6120455517ca79bce70458bfbc49e
                                        </a>
                                    @endif
                                    <p>
                                        @if (!$feature->first()->is_visible)
                                            <i class="fas fa-eye-slash mr-1"></i>
                                        @endif
                                        {!! $feature->first()->displayName !!}
                                        @if ($feature->first()->subtype)
                                            <br />({!! $feature->first()->subtype->displayName !!} Subtype)
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
@endsection

@section('scripts')
    @if (config('lorekeeper.extensions.species_trait_index.trait_modals'))
        <script>
            $(document).ready(function() {
                $('.modal-image').on('click', function(e) {
                    e.preventDefault();

                    loadModal("{{ url('world/species/' . $species->id . '/trait') }}/" + $(this).data('id'), 'Trait Detail');
                });
            })
        </script>
    @endif
@endsection
