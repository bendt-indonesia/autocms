@foreach($controls as $key => $control)
    @include('autocms::backend.partials._input', ['name' => $key, 'control' => $control])
@endforeach
