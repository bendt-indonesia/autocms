<?php

namespace Bendt\Autocms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Page extends Model
{
    protected $table = 'page';
    protected $guarded = [];
    protected $with = ['children','lists'];

    public function elements()
    {
        return $this->hasMany(PageElement::class)->orderBy('sort_no');
    }

    public function lists()
    {
        return $this->hasMany(PageList::class);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function title($locale)
    {
        return $this->hasMany(PageElement::class)->title($locale)->get();
    }

    public function description($locale)
    {
        return $this->hasMany(PageElement::class)->description($locale)->get();
    }

    public function keywords($locale)
    {
        return $this->hasMany(PageElement::class)->keywords($locale)->get();
    }

    public function list($slug)
    {
        return $this->hasMany(PageList::class)->key($slug)->get();
    }

    public function locale_elements($locale)
    {
        return $this->hasMany(PageElement::class)->locale($locale)->get();
    }
}
