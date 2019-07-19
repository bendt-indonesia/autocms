<div class="m-t-25 p-3">
    @foreach($model->details as $index=>$detail)
        <div class="card card-body mb-4">
            <form method="post" enctype="multipart/form-data"
                  action="{{route('cms.update.list.detail',['slug'=>$page->slug])}}">
                {{csrf_field()}}
                <input type="hidden" name="detail_id" value="{{$detail->id}}">
                <div class="row">
                    <h4 class="col-lg-2" style="line-height: 35px">Sort No</h4>
                    <div class="col-lg-10">
                        <div class="form-horizontal">
                            <button type="button" class="btn btn-sm btn-warning"
                                    data-id="{{$detail->id}}"
                                    data-list_id="{{$model->id}}"
                                    data-type="promote"
                                    onclick="move(this)"
                                {{$index==0?"disabled":""}}
                            ><i class="fa fa-arrow-up"></i></button>
                            <input type="text" name="sort_no" class="form-control text-center"
                                   value="{{$detail->sort_no}}" style="width: 150px; display: inline">
                            <button type="button" class="btn btn-sm btn-warning"
                                    data-id="{{$detail->id}}"
                                    data-list_id="{{$model->id}}"
                                    data-type="demote"
                                    onclick="move(this)"
                                {{($index == (count($model->details)-1))?"disabled":""}}
                            ><i class="fa fa-arrow-down"></i></button>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <hr>
                @foreach($detail->elements as $index2=>$el)
                    @include('autocms::backend.partials._control-group', [
                        'controls' => [
                            $el->name => [
                                'type' => $el->type,
                                'label' => $el->name,
                                'placeholder' => $el->name,
                                'default' => old($el->name,$el->content),
                                'rows' => '4',
                            ],
                        ]
                    ])
                    <hr>
                @endforeach
                <div class="row">
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-primary btn-block"
                                onclick="save_list(this,{{$detail->sort_no}})"><i class="fa fa-save"></i> Save
                        </button>
                    </div>
                    <div class="col-sm-6">
                        <a data-href="{{route('cms.delete.list.detail',['slug'=>$page->slug,'detail_id'=>$detail->id])}}"
                           class="btn btn-danger btn-block" onclick="pop_delete(this);"><i class="fa fa-close"></i> </a>
                    </div>
                </div>
            </form>
        </div> <!--card-->
    @endforeach
</div>
