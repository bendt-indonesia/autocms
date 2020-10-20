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
<?php $table_columns = []; ?>
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered m-t-3 dtb">
                            <thead>
                            <tr>
                                <th>Sort No</th>
                                @foreach($model->preset as $index=>$el)
                                    @if($el->is_table)
                                        <th>{{$el->label != '' ? \Illuminate\Support\Str::title($el->label) : \Illuminate\Support\Str::title($el->name)}}</th>
                                        <?php $table_columns[] = $el->name ?>
                                    @endif
                                @endforeach
                                <th width="1"></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($model->details as $idx=>$detail)
                                @foreach(cstore('language') as $index=>$locale)
                                    <tr>
                                        @if($index === 0)
                                            <td rowspan="{{count(cstore('language'))}}">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-sm btn-info"
                                                            data-id="{{$detail->id}}"
                                                            data-list_id="{{$model->id}}"
                                                            data-type="promote"
                                                            onclick="move(this)"
                                                            {{$idx==0?"disabled":""}}
                                                    ><i class="fa fa-arrow-up"></i></button>

                                                    <span style="width: 100px">{{$detail->sort_no}}</span>

                                                    <button type="button" class="btn btn-sm btn-info"
                                                            data-id="{{$detail->id}}"
                                                            data-list_id="{{$model->id}}"
                                                            data-type="demote"
                                                            onclick="move(this)"
                                                            {{($idx == (count($model->details)-1))?"disabled":""}}
                                                    ><i class="fa fa-arrow-down"></i></button>
                                                </div>
                                            </td>
                                        @endif

                                        @foreach($detail->elements as $row)
                                            @if($row->locale == $locale->iso)
                                                <td>{{$row->content}}</td>
                                            @endif
                                        @endforeach

                                        @if($index === 0)
                                            <td style="white-space: nowrap">
                                                <form action="{{route('backend.category.destroy', ['id' => $detail->id])}}"
                                                      method="post">
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <a href="{{route('backend.category.edit', ['id' => $detail->id])}}"
                                                       class="btn btn-warning btn-sm">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                            confirm="Are you sure you want to remove {{$detail->name}}?">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>

                                @endforeach

                                <tr>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content2')

    @foreach($model->details as $idx=>$detail)
        <div class="card">
            <div class="tab-content">
                @foreach(cstore('language') as $index=>$locale)
                    <div class="tab-pane {{$index==0?"active":""}}" id="tab-{{$detail->id}}-{{$locale->iso}}"
                         role="tabpanel"
                         aria-expanded="true">
                        <div class="pad-20">
                            <form method="post" enctype="multipart/form-data"
                                  action="{{route('cms.update.list.detail',['slug'=>$page->slug])}}"
                                  id="form_list_update">
                                {{csrf_field()}}
                                <input type="hidden" name="detail_id" value="{{$detail->id}}">
                                <div class="card card-body mb-4">
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

                                </div> <!--card-->
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="pad-20">
                <div class="row">
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-primary btn-sm btn-block"
                                onclick="save_list(this,{{$detail->sort_no}},{{$index}});"><i
                                    class="fa fa-save"></i> Save
                        </button>
                    </div>
                    <div class="col-sm-6">
                        <a data-href="{{route('cms.delete.list.detail',['slug'=>$page->slug,'detail_id'=>$detail->id])}}"
                           class="btn btn-danger btn-block btn-sm text-white"
                           onclick="pop_delete(this);"><i
                                    class="fa fa-close"></i> Delete</a>
                    </div>
                </div>
            </div>
        </div> <!-- card -->
        <br><br>
    @endforeach

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
