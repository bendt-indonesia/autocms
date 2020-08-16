<?php


/**
 * Retrieve our Locale instance
 *
 * @return App\Locale
 */
use Illuminate\Support\Facades\Session;

if(!function_exists("locale")) {
    function locale()
    {
        return app()->make(App\Locale::class);
    }
}

/**
 * @param string $string
 * @param string $endString
 *
 * @return boolean
 */
if(!function_exists('endsWith')) {
    function endsWith($string, $endString)
    {
        $len = strlen($endString);
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    }
}



/**
 * Check CMS Element Exists or Not
 *
 * @param array $page
 *
 * @param string $key
 *
 * @return string
 */
if (!function_exists('el')) {
    function el($page, $key)
    {
        $locale = Session::has(env('LOCALE_SESS')) ? Session::get(env('LOCALE_SESS')) : locale()->fallback();
        return isset($page['elements'][$locale][$key]) ?
            $page['elements'][$locale][$key] :
            '';
    }
}

/**
 * Check CMS Element Exists or Not,
 * then return full URL
 *
 * @param array $page
 *
 * @param string $key
 *
 * @return string
 */
if (!function_exists('el_url')) {
    function el_url($page, $key)
    {
        $locale = Session::has(env('LOCALE_SESS')) ? Session::get(env('LOCALE_SESS')) : locale()->fallback();
        if (isset($page['elements'][$locale][$key])) {
            $check = Storage::url($page['elements'][$locale][$key]);
            if ($check[0] === "/" || $check[0] === "\\") {
                $check = substr($check, 1);
            }
            return url($check);
        } else {
            return '';
        }
    }
}

if (!function_exists('el_cdn_url')) {
    function el_cdn_url($page, $key)
    {
        $locale = locale()->current();
        if (isset($page['elements'][$locale][$key])) {
            $check = Storage::url($page['elements'][$locale][$key]);
            if ($check[0] === "/" || $check[0] === "\\") {
                $check = substr($check, 1);
            }
            return asset_path($check);
        } else {
            return '';
        }
    }
}

/**
 * Get page list by key
 *
 * @param array $page
 *
 * @param string $key
 *
 * @return array
 */
if (!function_exists('clist')) {
    function clist($page, $key)
    {
        return isset($page['lists'][$key]) ?
            $page['lists'][$key] :
            die('Page List ' . $key . ' didn\'t exists!');
    }
}

/**
 * Store Helper
 *
 * @param string $key
 *
 * @param string $default
 *
 * @return string
 */
if(!function_exists('conval')) {
    function conval($key, $default = null)
    {
        return app(\App\Config\ConfigStore::class)->value($key, $default);
    }
}


/**
 * Autocms Store Helper
 *
 * @param string $key
 *
 * @return \App\Store
 */
if(!function_exists('cstore')) {
    function cstore($key)
    {
        return \Bendt\Autocms\Classes\StoreManager::get($key);
    }
}

/**
 * Phone Helper
 *
 * @param string $key
 *
 * @param string $default
 *
 * @return string
 */
if(!function_exists('phoneval')) {
    function phoneval($key, $default = null)
    {
        $str = app(\App\Config\ConfigStore::class)->value($key, $default);
        preg_match_all('!\d+!', $str, $matches);
        $var = implode('', $matches[0]);
        return "+".$var;
    }
}


/**
 * Route for locale
 *
 * @param array $page
 *
 * @param string $key
 *
 * @return string
 */
if(!function_exists('routeL')) {

    function routeL($name,$params = [])
    {
        if(!isset($params['locale'])) {
            $locale = Session::has(env('LOCALE_SESS')) ? Session::get(env('LOCALE_SESS')) : locale()->fallback();;
            $params['locale'] = $locale;
        }
        return route($name,$params);
    }
}