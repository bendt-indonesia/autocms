<option {{selected("parent_id", $category['id'], isset($model->parent_id) ? $model->parent_id : (isset($model->category_id) ? $model->category_id : "" ))}} value="{{$category['id']}}">
    @if(count($parent)>0)
        @foreach($parent as $row)
            {{$row}} >
        @endforeach
    @endif
    @include('autocms::backend.include.category.option-text-control',['category'=>$category])
</option>
