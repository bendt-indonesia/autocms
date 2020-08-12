@extends('layouts.backend', [
    "page_title" => 'Edit Page Element'
])

@section('title',$page->name." > List ".$model->name)
@section('title_right')
    <div class="pull-right">
        <button data-toggle="modal" data-target="#listModal" class="btn btn-success btn-sm">
            <i class="fa fa-plus mr-2"></i> Add Item
        </button>
    </div>
@endsection


@section('content')
    <!-- Modal -->
    <div class="modal fade" id="listModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data"
                      action="{{route('cms.create.list.detail',['slug'=>$page->slug,'list_slug'=>$model->slug])}}">
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="form-group row">
                            <label class="control-label col-sm-2" for="city">
                                Sort No
                            </label>
                            <div class="col-sm-10">
                                <div class="form-horizontal">
                                    <input type="text" name="sort_no" class="form-control"
                                           value="1" required>
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-tabs customtab" role="tablist">
                            @foreach(cstore('language') as $index=>$locale)
                                <li class="nav-item">
                                    <a class="nav-link {{$index==0?"active":""}} {{count($errors->get($locale->iso.'.*'))>0?"text-danger":""}}"
                                       data-toggle="tab" href="#mtab-{{$locale->iso}}" role="tab" aria-expanded="true">
                                        <span class="hidden-sm-up"><i class='fa fa-language mr-2'></i></span> <span
                                            class="hidden-xs-down">{{$locale->name}} {{count($errors->get($locale->iso.'.*'))>0?"*":""}}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            @foreach(cstore('language') as $index=>$locale)
                                <div class="tab-pane  {{$index==0?"active":""}}" id="mtab-{{$locale->iso}}"
                                     role="mtabpanel"
                                     aria-expanded="true">
                                    <div class="mt-3">
                                        @foreach($model->preset as $index=>$el)
                                            @include('autocms::backend.partials._control-group', [
                                                'controls' => [
                                                    $locale->iso.'['.$el->name.']' => [
                                                        'id' => 0,
                                                        'type' => $el->type,
                                                        'label' => $el->label != '' ? $el->label : $el->name,
                                                        'placeholder' => $el->placeholder,
                                                        'default' => old($el->name,$el->content),
                                                        'rows' => '4',
                                                        'note' => $el->note,
                                                        'required' => (strpos($el->rules, 'required') !== false)
                                                    ],
                                                ]
                                            ])
                                            <hr>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                        <span class="hidden-sm-up"><i class='fa fa-language mr-2'></i></span> <span
                            class="hidden-xs-down">{{$locale->name}} {{count($errors->get($locale->iso.'.*'))>0?"*":""}}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <form method="post" enctype="multipart/form-data"
              action="{{route('cms.update.list.detail',['slug'=>$page->slug])}}"
              id="form_list_update">
        {{csrf_field()}}
        <!-- Tab panes -->
        <div class="tab-content">
            @foreach(cstore('language') as $index=>$locale)
                <div class="tab-pane  {{$index==0?"active":""}}" id="tab-{{$locale->iso}}" role="tabpanel"
                     aria-expanded="true">
                    <div class="pad-20">
                        @foreach($model->details as $idx=>$detail)
                            <div class="card card-body mb-4">
                                    <input type="hidden" name="detail_id" value="{{$detail->id}}">
                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn btn-sm btn-info"
                                                            data-id="{{$detail->id}}"
                                                            data-list_id="{{$model->id}}"
                                                            data-type="promote"
                                                            onclick="move(this)"
                                                        {{$idx==0?"disabled":""}}
                                                    ><i class="fa fa-arrow-up"></i></button>
                                                </div>
                                                <input type="text" name="sort_no" class="form-control text-center"
                                                       value="{{$detail->sort_no}}"
                                                       style="width: 150px; display: inline">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-sm btn-info"
                                                            data-id="{{$detail->id}}"
                                                            data-list_id="{{$model->id}}"
                                                            data-type="demote"
                                                            onclick="move(this)"
                                                        {{($idx == (count($model->details)-1))?"disabled":""}}
                                                    ><i class="fa fa-arrow-down"></i></button>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="clearfix"></div>
                                    <hr>
                                    @foreach($detail->elements as $row)
                                        @if($row->locale == $locale->iso)
                                            @include('autocms::backend.partials._control-group', [
                                                'controls' => [
                                                    $locale->iso.'['.$row->name.']' => [
                                                        'id' => $row->id,
                                                        'type' => $row->type,
                                                        'label' => $row->name,
                                                        'placeholder' => $row->placeholder,
                                                        'default' => old($locale->iso.'.'.$row->name,$row->content),
                                                        'rows' => '4',
                                                        'note' => $row->note,
                                                        'required' => (strpos($row->rules, 'required') !== false)
                                                    ],
                                                ]
                                            ])
                                        @endif
                                    @endforeach
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <button type="button" class="btn btn-primary btn-sm btn-block"
                                                    onclick="save_list(this,{{$detail->sort_no}},{{$index}});"><i
                                                    class="fa fa-save"></i> Save
                                            </button>
                                        </div>
                                        <div class="col-sm-6">
                                            <a data-href="{{route('cms.delete.list.detail',['slug'=>$page->slug,'detail_id'=>$detail->id])}}"
                                               class="btn btn-danger btn-block btn-sm text-white" onclick="pop_delete(this);"><i
                                                    class="fa fa-close"></i> Delete</a>
                                        </div>
                                    </div>
                            </div> <!--card-->
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        </form>
    </div> <!-- card -->

    <div class="text-center mb-5 mt-5">
        <a href="{{route('cms.page',['slug'=>$page->slug])}}" class="btn btn-default btn-block">Back</a>
    </div>
@endsection

@section('script')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>

        window.serverPath = "{{route('upload_image')}}";
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

        function move(ele) {
            var url = "{{route('cms.move.list',['slug'=>$page->slug])}}";
            var data = ele.dataset;
            var msg = "Please contact site Administrator";

            $(".loader-container").show();

            axios({
                method: 'post',
                url: url,
                data: data
            }).then(function (resp) {
                if (resp.status == 200) {
                    success('Data saved!', 1);
                } else {
                    error_warning(msg, 1);
                }
                $(".loader-container").hide();
            }).catch(function (error) {
                error_warning(msg, 1);
                $(".loader-container").hide();
            });
        }

        function save_list(el, sort_no) {
            $(".loader-container").show();
            var url = "{{route('cms.update.list.detail',['slug'=>$page->slug])}}";
            let data = new FormData(el.form);

            axios({
                method: 'post',
                url: url,
                data: data,
                config: {headers: {'Content-Type': 'multipart/form-data'}}
            }).then(function (resp) {
                if (sort_no != data.get('sort_no')) {
                    success('Data saved!', 1);
                } else {
                    success('Data saved!', 0);
                }
                $(".loader-container").hide();
            }).catch(function (error) {
                error_warning("Please check all the required field(s)", 0);
                $(".loader-container").hide();
            });
        }
    </script>

@endsection
