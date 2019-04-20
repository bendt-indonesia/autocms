<div class="form-group">
    <label for="parent_id">Parent</label>
    <select class="form-control" id="parent_id" name="parent_id">
        <option value="">-- Please Select --</option>
        @include('backend.include.category.option-control',['categories'=>$m_parent_id,'parent'=>[],'selected'=>old('parent_id')])
    </select>
</div>
