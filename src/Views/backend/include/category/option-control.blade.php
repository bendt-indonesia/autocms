@foreach($categories as $category)
    @include('backend.include.category.option',[ 'category' => $category, 'parent' => $parent])
    @if(isset($category['childs']))
        <?php
            $temp = $parent;
            $temp[] = $category['name'];
        ?>
        @include('backend.include.category.option-control',[ 'categories' => $category['childs'], 'parent' => $temp])
    @endif
@endforeach
