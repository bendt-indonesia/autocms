<div class="form-horizontal" id="form-horizontal">
    @foreach($controls as $key => $control)
        @include('autocms::backend.partials._form-group', ['name' => $key, 'control' => $control])
    @endforeach
    @if(isset($submit_button) && $submit_button)
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default btn-default">Submit</button>
            </div>
        </div>
    @endif
</div>
