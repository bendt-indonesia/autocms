<?php

namespace Bendt\Autocms\Models;

use Illuminate\Database\Eloquent\Model;

class PageListElement extends Model
{
    protected $table = 'page_list_element';
    protected $guarded = [];
    protected $fillable = ['page_list_detail_id','name','type','content','editor','dropify','label','placeholder','note'];

    public function page_list_detail()
    {
        return $this->belongsTo(PageListDetail::class);
    }

    public function scopeLocale($query, $locale)
    {
        $query->orderBy('id', 'ASC');
        return $query->where('locale', $locale);
    }

    public function scopeKey($query, $name)
    {
        return $query->where('name', $name);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}
