<?php

namespace Bendt\autocms\Models;

use Illuminate\Database\Eloquent\Model;

class PageListPreset extends Model
{
    protected $table = 'page_list_preset';
    protected $guarded = [];

    public function page_list()
    {
        return $this->belongsTo(PageList::class);
    }
}
