<?php

namespace Bendt\Autocms\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Bendt\Autocms\Models\Page;
use Bendt\Autocms\Models\PageElement;
use Bendt\Autocms\Models\PageGroup;
use Bendt\Autocms\Models\PageList;
use Bendt\Autocms\Models\PageListDetail;
use Bendt\Autocms\Models\PageListElement;

use Bendt\Autocms\Services\LanguageService;

class PageGroupService
{
    public static function getBySlug($slug)
    {
        $page = PageGroup::where('slug', $slug)->first();

        return $page;
    }
}
