@extends('layouts.backend', [

])

@section('title',$model->name." Page")

@section('content')

    <form method="post" enctype="multipart/form-data" id="form_list"
          action="{{route('cms.update.list.detail',['slug'=>$slug])}}"
    >
        {{csrf_field()}}
        <input type="hidden" name="detail_id" value="{{$model->id}}">
        <div class="card">
            <div class="card-body p-b-0">
            <div class="form-group row">
                <label class="control-label col-sm-2" for="city">
                    Sort No
                </label>
                <div class="col-sm-10">
                    <div class="form-horizontal">
                        <input type="text" name="sort_no" class="form-control"
                               value="{{$model->sort_no}}" required>
                    </div>
                </div>
            </div>

            @if(count(cstore('language'))>1)
                <h6 class="card-subtitle">Please select language tab you wish to edit</h6>
            @endif
            </div>
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
                            @endforeach
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <button type="button" class="btn btn-primary btn-block mt-3"
                onclick="save_list(this);"><i
                class="fa fa-save"></i> Save Changes
        </button>
    </form>
@endsection

@section('script')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        var token = document.head.querySelector('meta[name="csrf-token"]').getAttribute('content');

        axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.post['Content-Type'] = 'multipart/form-data';

        function success(msg, reload) {
            swal(msg, {
                icon: "success",
                timer: 1000
            }).then(function (willContinue) {
                if (reload) {
                    location.reload();
                }
            });
        }

        function error_warning(msg, reload) {
            swal({
                title: 'An error has occured!',
                text: msg,
                icon: "warning",
                dangerMode: true,
            }).then(function (willContinue) {
                if (reload) {
                    location.reload();
                }
            });
        }

        function save_list(el) {
            $(".loader-container").show();
            var url = "{{route('cms.update.list.detail',['slug'=>$slug])}}";
            let data = new FormData(el.form);
            axios({
                method: 'post',
                url: url,
                data: data,
                config: {headers: {'Content-Type': 'multipart/form-data'}}
            }).then(function (resp) {
                success('Data saved!', 0);
                $(".loader-container").hide();
            }).catch(function (error) {
                error_warning("Please check all the required field(s)", 0);
                $(".loader-container").hide();
            });
        }
    </script>
@endsection
