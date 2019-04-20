<?php

namespace Bendt\Autocms\Classes;

use Illuminate\Support\Facades\Cache;
use Bendt\Autocms\Store\StoreMapper;

class StoreManager
{
    // Static Variables
    const CACHE_DURATION = 1440; // Minutes
    public static $LIST = [];

    // Non Static Variables
    public $key;
    private $_closure;
    private $_data;

    // Static Functions
    public static function get($key)
    {
        $store = self::_getStore($key);

        if($store) return $store->getValue();
        else return null;
    }

    public static function forget($key)
    {
        $store = self::_getStore($key);

        if($store) {
            $store->removeValue();
            return true;
        }
        else return false;
    }

    private static function _getStore($key)
    {
        if(array_key_exists($key, self::$LIST))
        {
            $store = self::$LIST[$key];
        }
        else {
            $store = self::_createIfPlanned($key);
        }

        return $store;
    }

    private static function _createIfPlanned($key)
    {
        $closure = self::_mapKeyToFunction($key);

        if($closure) {
            $store = new StoreManager($key, $closure);
            self::$LIST[$key] = $store;

            return $store;
        }
        else return null;
    }

    private static function _mapKeyToFunction($key)
    {
        return StoreMapper::MAP($key);
    }

    // Non Static Functions
    public function __construct($key, \Closure $closure)
    {
        $this->key = $key;
        $this->_closure = $closure;
    }

    public function getValue()
    {
        if(is_null($this->_data)) {
            $this->_data = Cache::remember($this->key, self::CACHE_DURATION, $this->_closure);
        }

        return $this->_data;
    }

    public function removeValue()
    {
        $this->_data = null;
        Cache::forget($this->key);

        return true;
    }




}
