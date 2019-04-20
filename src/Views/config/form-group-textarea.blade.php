<div class="form-group row">
    <label for="{{$key}}" class="col-sm-2 control-label text-capitalize">{{$label ? $label : $key}}</label>
    <div class="col-sm-10">
        <textarea id="{{$key}}" name="{{$key}}" class="{{$class ?? 'form-control'}}" rows="4" placeholder="{{$placeholder ?? ''}}">{{$value}}</textarea>
    </div>
</div>
