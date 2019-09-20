<?php

namespace Bendt\Autocms\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class PageListDetail extends Model
{
    protected $table = 'page_list_detail';
    protected $guarded = ['id'];
    protected $with = ['elements'];

    public function page_list()
    {
        return $this->belongsTo(PageList::class);
    }

    public function elements()
    {
        return $this->hasMany(PageListElement::class)->orderBy('sort_no');
    }

    public function element($name)
    {
        return $this->hasMany(PageListElement::class)->key($name)->first()->content;
    }

    public function elements_type($type)
    {
        return $this->hasMany(PageListElement::class)->type($type);
    }

    public function locale_elements($locale)
    {
        return $this->hasMany(PageListElement::class)->locale($locale)->get();
    }
}
