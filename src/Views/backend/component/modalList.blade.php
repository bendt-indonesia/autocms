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
