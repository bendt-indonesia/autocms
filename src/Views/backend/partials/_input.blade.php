<?php
//replace array[key] become array.key
//http://ca.php.net/variables.external
$alt_name = str_replace(']', '', $name);
$alt_name = str_replace('[', '.', $alt_name);
?>
<?php
$file_exists = (isset($control['default']) && !is_null($control['default']) && $control['default'] != '');
?>

@if(isset($control['type']) && $control['type'] == 'editor')
    <textarea class="form-control tmce" id="{{$name}}" name="{{$name}}"
              placeholder="{{isset($control['placeholder'])?$control['placeholder']:''}}"
              @if(isset($control['rows']))rows="{{$control['rows']}}"@endif>{{$control['default']}}</textarea>
@elseif(isset($control['type']) && $control['type'] == 'textarea')
    <textarea class="form-control" id="{{$name}}" name="{{$name}}"
              placeholder="{{isset($control['placeholder'])?$control['placeholder']:''}}"
              @if(isset($control['rows']))rows="{{$control['rows']}}"@endif>{{$control['default']}}</textarea>
@elseif(isset($control['type']) && $control['type'] == 'select')
    @if(isset($control['disabled']) && $control['disabled'])
        <input type="hidden" name="{{$name}}" value="{{$control['default']}}"/>
    @endif
    <select id="{{$name}}" name="{{$name}}"
            class="form-control {{(isset($control['search']) && $control['search']) && (!isset($control['disabled']) || (isset($control['disabled']) && !$control['disabled'])) ? 'selectpicker show-tick':""}}"
            @if(isset($control['search'])) data-live-search="true" @endif
            {{isset($control['disabled']) && $control['disabled'] ? 'disabled' : ''}}
    >

        @if(isset($control['can-empty']) && $control['can-empty'])
            <option value="">{{isset($control['empty-text'])?$control['empty-text']:'- Select -'}}</option>
        @endif

        @if(isset($control['categories']) && $control['categories'])
            @include('autocms::backend.partials._categoryoptions', ['categories' => $control['options'], 'selected_id' => $control['default']])
        @else
            @foreach($control['options'] as $option)
                <option value="{{$option->id}}" {{$control['default'] == $option->id ? 'selected' : ''}}>{{$option->name}}</option>
            @endforeach
        @endif
    </select>
@elseif(isset($control['type']) && $control['type'] == 'file')
    <input type="file" id="{{$name.$control['id']}}" name="{{$name}}" class="input-file"/>
    @if($file_exists)
        @if(endsWith($control['default'],'jpg') || endsWith($control['default'],'png') || endsWith($control['default'],'jpeg'))
            <img class="image-thumbs" src="{{Storage::url($control['default'])}}">
        @else
            <a href="{{Storage::url($control['default'])}}" target="_blank">View File</a>
        @endif
    @endif
    <label for="{{$name.$control['id']}}" class="btn btn-file js-labelFile">
        <i class="icon fa fa-check"></i>
        <span class="js-fileName">Change file</span>
    </label>
@elseif(isset($control['type']) && $control['type'] == 'time')
    <input type="text" class="form-control timepicker" @include('autocms::backend.partials._props', ['name'=>$name,'control' => $control])/>
@elseif(isset($control['type']) && $control['type'] == 'date')
    <input type="text" class="form-control datepicker" @include('autocms::backend.partials._props', ['name'=>$name,'control' => $control])/>
@elseif(isset($control['type']) && $control['type'] == 'datetime')
    <input type="text" class="form-control datetimepicker" @include('autocms::backend.partials._props', ['name'=>$name,'control' => $control])/>
@elseif(isset($control['type']) && $control['type'] == 'radio')
    <div class="form-group">
        @foreach(["No", "Yes"] as $key => $value)
            <div class="radio d-inline mr-3">
                <label>
                    <input type="radio"
                           name="{{$name}}"
                           value="{{$key}}"
                           {{checked_radio($name, $key , 1)}}
                       @if(isset($control['disabled']) && $control['disabled']) disabled @endif
                    >
                    {{$value}}
                </label>
            </div>
        @endforeach
    </div>
@else
    <input type="{{isset($control['type'])?$control['type']:'text'}}" class="form-control" @include('autocms::backend.partials._props', ['name'=>$name,'control' => $control])/>
@endif
<small class="text-danger">{{$errors->first($name)}} {{$errors->get($alt_name)?$errors->first($alt_name):""}}</small>
