<?php

namespace Bendt\Autocms\Models;

use Illuminate\Database\Eloquent\Model;

class PageGroup extends Model
{
    protected $table = 'page_group';
    protected $guarded = [];

    public function elements()
    {
        return $this->hasMany(PageElement::class)->get();
    }
}
