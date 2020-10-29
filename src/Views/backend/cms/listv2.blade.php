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
    @include('autocms::backend.component.modalList')
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
                                                {{$detail->sort_no}}
                                            </td>
                                        @endif

                                        @foreach($detail->elements as $row)
                                            @if($row->locale == $locale->iso && in_array($row->name,$table_columns))
                                                <td>
                                                    <?php
                                                    switch($row->type) {
                                                        case 'file':
                                                            echo "<a href='".Storage::url($row->content)."'>View File / Image</a>";
                                                            break;
                                                        default:
                                                            echo $row->content;
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                            @endif
                                        @endforeach

                                        @if($index === 0)
                                            <td style="white-space: nowrap" rowspan="{{count(cstore('language'))}}">
                                                <form action="{{route('cms.delete.list.detail', [
                                                        'detail_id' => $detail->id,
                                                        'slug'=>$page->slug,

                                                      ])}}"
                                                      method="get">
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <a href="{{route('cms.edit.list.detail', [
                                                            'detail_id' => $detail->id,
                                                            'slug'=>$page->slug,
                                                            ])}}"
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
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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
