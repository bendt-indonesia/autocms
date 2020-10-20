<?php
    //replace array[key] become array.key
    //http://ca.php.net/variables.external
    $alt_name = str_replace(']', '', $name);
    $alt_name = str_replace('[', '.', $alt_name);
?>
<div class="form-group row {{$errors->first($name)||$errors->first($alt_name)?"has-error":""}}">
    <label class="control-label col-sm-2" for="{{$name}}">
        {{$control['label']}}
        @if(isset($control['required']) && $control['required'])
            <span class="text-danger">*</span>
        @endif
        @if(isset($control['note']) && $control['note'] != "")
            <br>
            <small>{{$control['note']}}</small>
        @endif
    </label>
    <div class="col-sm-10">
        @include('autocms::backend.partials._input', ['name' => $key, 'control' => $control])
    </div>
</div>

