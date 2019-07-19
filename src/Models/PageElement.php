<?php

namespace Bendt\Autocms\Models;

use Illuminate\Database\Eloquent\Model;

class PageElement extends Model
{
    protected $table = 'page_element';
    protected $guarded = [];
    protected $fillable = ['page_id','group_id','locale','name','type','content','label','placeholder','note'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function group()
    {
        return $this->belongsTo(PageGroup::class);
    }

    public function scopeLocale($query, $locale)
    {
        $query->orderBy('id', 'ASC');
        return $query->where('locale', $locale);
    }

    public function scopeTitle($query, $locale)
    {
        $query->where('name', 'title');
        return $query->where('locale', $locale);
    }

    public function scopeDescription($query, $locale)
    {
        $query->where('name', 'description');
        return $query->where('locale', $locale);
    }

    public function scopeKeywords($query, $locale)
    {
        $query->where('name', 'keywords');
        return $query->where('locale', $locale);
    }

}
