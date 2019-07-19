@extends('layouts.backend', [

])

@section('title',$model->name." Page")

@section('content')

    <form method="post" enctype="multipart/form-data">
        {{csrf_field()}}

        <div class="card">
            @if(count(cstore('language'))>1)
            <div class="card-body p-b-0">
                <h6 class="card-subtitle">Please select language tab you wish to edit</h6>
            </div>
            @endif
            <!-- Nav tabs -->
            <ul class="nav nav-tabs customtab" role="tablist">
                @foreach(cstore('language') as $index=>$locale)
                <li class="nav-item">
                    <a class="nav-link {{$index==0?"active":""}} {{count($errors->get($locale->iso.'.*'))>0?"text-danger":""}}
                            " data-toggle="tab" href="#tab-{{$locale->iso}}" role="tab" aria-expanded="true">
                        <span class="hidden-sm-up"><i class='fa fa-language mr-2'></i></span> <span class="hidden-xs-down">{{$locale->name}} {{count($errors->get($locale->iso.'.*'))>0?"*":""}}</span>
                    </a>
                </li>
                @endforeach
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                @foreach(cstore('language') as $index=>$locale)
                <div class="tab-pane  {{$index==0?"active":""}}" id="tab-{{$locale->iso}}" role="tabpanel" aria-expanded="true">
                    <div class="pad-20">
                        @if(isset($grouped_elements[$locale->iso]))
                            @foreach($grouped_elements[$locale->iso] as $index2=>$row)
                                @if(
                                    count($groups) == 0 && count($fields) == 0
                                    || count($groups)>0 &&  $row->group_id && in_array($row->group_id,$groups)
                                    || count($fields)>0 && in_array($row->name,$fields)
                                )
                                    @include('autocms::backend.partials._control-group', [
                                        'controls' => [
                                            $locale->iso.'['.$row->name.']' => [
                                                'id' => $row->id,
                                                'type' => $row->type,
                                                'label' => $row->name,
                                                'placeholder' => '',
                                                'default' => old($locale->iso.'.'.$row->name,$row->content),
                                                'rows' => '4',
                                                'note' => $row->note,
                                                'required' => (strpos($row->rules, 'required') !== false)
                                            ],
                                        ]
                                    ])
                                @endif

                        @endforeach
                        @endif
                        @if(isset($grouped_list[$locale->iso]))
                            @foreach($grouped_list[$locale->iso] as $list)
                                <div class="card mt-5">
                                    <div class="card-body">
                                        <h4 class="text-black">{{$list->name}}
                                            <a href="{{route('cms.list',['slug'=>$model->slug,'id'=>$list->id])}}" class="btn btn-warning btn-rounded btn-lg pull-right"><i class="fa fa-pencil mr-2"></i> Edit</a></h4>
                                        <p>{{$list->description}}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            @if(Request::input('g') == 'meta')
                <img src="{{asset('static/seo.jpg')}}" style="max-width: 600px; margin: auto">
            @endif
        </div>

        @include('autocms::backend.partials._form-actions', ['type'=>'edit','back_link' => route('cms.page',['slug'=>$model->slug])])
    </form>
@endsection

@section('script')
    <script>
        window.serverPath = "{{route('upload_image')}}";
    </script>
@endsection
