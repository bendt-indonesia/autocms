<div class="text-center mb-5 mt-5">
    <div class="row">
        <div class="col-sm-6">
            @if($type == 'create')
                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-floppy-o"></i> Submit</button>
            @else
                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-floppy-o"></i> Save Changes</button>
            @endif
        </div>
        <div class="col-sm-6">
            <a href="{{$back_link}}" class="btn btn-default btn-block">Back to List</a>
        </div>
        <div class="col-sm-12 m-t-2">
            @if(isset($custom_buttons))
                @foreach($custom_buttons as $row)
                    <a href="{{$row['link']}}" class="{{$row['class']}}">
                        @if(isset($row['icon']))
                            <i class="{{$row['icon']}}"></i>
                        @endif
                        {{$row['label']}}
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</div>