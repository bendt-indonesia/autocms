<?php

namespace Bendt\Autocms\Models;

use Bendt\Autocms\Services\PageGroupService;
use Illuminate\Database\Eloquent\Model;

class PageList extends Model
{
    protected $table = 'page_list';
    protected $guarded = [];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function group()
    {
        return $this->belongsTo(PageGroup::class);
    }

    public function details()
    {
        return $this->hasMany(PageListDetail::class)->orderBy('sort_no');
    }

    public function preset()
    {
        return $this->hasMany(PageListPreset::class);
    }

    public function scopeKey($query, $slug)
    {
        return $query->where('slug', $slug)->with('details','details.elements');
    }
}
