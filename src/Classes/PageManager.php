<?php

namespace Bendt\Autocms\Classes;

use Bendt\Autocms\Models\Page;
use Bendt\Autocms\Services\PageService;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PageManager
{
    const CACHE_PREFIX = 'page-', CACHE__MINUTE_DURATION = 1440;

    private $_data = null;

    private function checkCache($slug){
        $data = Cache::get(self::CACHE_PREFIX.$slug);
        if(is_null($data)) {
            $data = $this->_fetch($slug);
        }
        return $data;
    }

    public function clearCache($slug)
    {
        Cache::forget(self::CACHE_PREFIX.$slug);
    }

    public function get($slug)
    {
        $data = $this->checkCache($slug);
        return $data;
    }

    private function _fetch($slug)
    {
        $config = Cache::remember(self::CACHE_PREFIX.$slug, self::CACHE__MINUTE_DURATION, function() use ($slug){
            return $this->_fetchFromDatabase($slug);
        });

        return $config;
    }

    private function _fetchFromDatabase($slug)
    {
        return PageService::get($slug);
    }
    /*
    public function store($input)
    {
        $new = new Model($input);
        $new->save();

        $this->_clearCache();
    }

    public function storeMany(array $inputs)
    {
        DB::beginTransaction();
        foreach ($inputs as $key => $value)
        {
            (new Model([
                "name" => $key,
                "value" => $value
            ]))->save();
        }
        DB::commit();
        $this->_clearCache();
    }

    public function updateMany(array $inputs)
    {
        // Populate config
        $config = $this->data();

        DB::beginTransaction();
        foreach ($inputs as $key => $value)
        {
            if(isset($config[$key]))
            {
                $config[$key]->value = $value;
                $config[$key]->save();
            }
        }
        DB::commit();
        $this->_clearCache();
    }*/
}

