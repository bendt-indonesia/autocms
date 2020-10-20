value="{{isset($control['default'])?$control['default']:''}}"
id="{{$name}}"
name="{{$name}}"
placeholder="{{isset($control['placeholder'])?$control['placeholder']:''}}"
@if(isset($control['disabled']) && $control['disabled']) disabled @endif
@if(isset($control['readonly']) && $control['readonly']) readonly @endif