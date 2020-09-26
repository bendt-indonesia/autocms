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
 * Get the path to a versioned Mix file.
 *
 * @param  string  $path
 * @param  string  $manifestDirectory
 * @return \Illuminate\Support\HtmlString|string
 *
 * @throws \Exception
 */
if (! function_exists('asset_path')) {
    function asset_path($path, $mixin = false)
    {

        $env     = env('APP_ENV');
        $cdnUrl  = env('CDN_URL');

        if($mixin) {
            $path = mix($path);
            $path = substr($path,1);
        }

        // Reference CDN assets only in production or staging environemnt.
        // In other environments, we should reference locally built assets.
        if ($cdnUrl && ($env === 'production')) {
            $mixPath = $cdnUrl . $path;
        } else {
            $mixPath = asset($path);
        }
        return $mixPath;
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
            return env('CDN_URL') != '' ? asset_path($check) : url($check);
        } else {
            return '';
        }
    }
}

/**
 * Check CMS Element Exists or Not,
 * then return CDN Full URL
 *
 * @param array $page
 *
 * @param string $key
 *
 * @return string
 */
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
 * Dial Phone Helper
 *
 * Any Number format into +62
 * @param string $key
 *
 * @param string $country_code
 *
 * @return string
 */
if(!function_exists('phoneDial')) {
    function phoneDial($number_text, $country_code = '+62')
    {
        preg_match_all('!\d+!', $number_text, $matches);
        $matches = implode('', $matches[0]);
        if($matches[0] === '0') {
            $matches = $country_code.substr($matches,1);
        } else {
            $matches = $country_code.$matches;
        }
        return $matches;
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


/**
 * @param string $name
 * @param string $value
 * @param string $default_value
 *
 * @return string
 */
if (!function_exists('checked_radio')) {
    function checked_radio($name, $value, $default_value = null)
    {
        $old = old($name);
        $checked = !is_null($old) ? $old == $value : $default_value == $value;

        return $checked ? 'checked' : '';
    }
}