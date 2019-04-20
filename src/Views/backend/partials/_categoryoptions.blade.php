@foreach($categories as $category)
    <option value="{{$category->id}}" {{$category->id == $selected_id ? 'selected' : ''}}>{{$category->name}}</option>
    @include('autocms::backend.partials._categoryoptions', ['categories' => $category->children, 'selected_id' => $selected_id])
@endforeach
