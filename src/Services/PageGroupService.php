<?php

namespace Bendt\autocms\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Bendt\autocms\Models\Page;
use Bendt\autocms\Models\PageElement;
use Bendt\autocms\Models\PageGroup;
use Bendt\autocms\Models\PageList;
use Bendt\autocms\Models\PageListDetail;
use Bendt\autocms\Models\PageListElement;

use Bendt\autocms\Services\LanguageService;

class PageGroupService
{
    public static function getBySlug($slug)
    {
        $page = PageGroup::where('slug', $slug)->first();

        return $page;
    }
}
