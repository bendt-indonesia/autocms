<table>
    @foreach($controls as $key => $control)
        <tr>
            <th style="vertical-align: top;">
                <label for="{{$key}}">
                    @if(isset($control['type']) && $control['type'] == 'file' && isset($control['default']) && !is_null($control['default']))
                        <img src="{{$control['default']}}" width="100" height="100" />
                    @else
                        {{$control['label']}}
                    @endif
                </label>
            </th>
            <td style="vertical-align: top;">:</td>
            <td>
                @if(isset($control['type']) && $control['type'] == 'textarea')
                    <textarea class="form-control" id="{{$key}}" name="{{$key}}" placeholder="{{isset($control['placeholder'])?$control['placeholder']:''}}">{{$control['default']}}</textarea>
                @elseif(isset($control['type']) && $control['type'] == 'select')
                    <select id="{{$key}}" name="{{$key}}" class="form-control">
                        @if(isset($control['can-empty']) && $control['can-empty'])
                            <option value="">{{isset($control['empty-text'])?$control['empty-text']:'- Select -'}}</option>
                        @endif
                        @if(isset($control['categories']) && $control['categories'])
                            @include('autocms::backend.partials._categoryoptions', ['categories' => $control['options'], 'selected_id' => $control['default']])
                        @else
                            @foreach($control['options'] as $option)
                                <option value="{{$option->id}}" {{$control['default'] == $option->id ? 'selected' : ''}}>{{$option->name}}</option>
                            @endforeach
                        @endif
                    </select>
                @elseif(isset($control['type']) && $control['type'] == 'file')
                    <input type="file" id="{{$key}}" name="{{$key}}"/>
                @else
                    <input type="{{isset($control['type'])?$control['type']:""}}" class="form-control" id="{{$key}}" name="{{$key}}" value="{{$control['default']}}" placeholder="{{isset($control['placeholder'])?$control['placeholder']:''}}"/>
                @endif
                <span class="validation-error">{{$errors->first($key)}}</span>
            </td>
        </tr>
    @endforeach
    <tr>
        <td>
            <button type="submit">Submit</button>
        </td>
    </tr>
</table>
