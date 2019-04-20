<?php
namespace Bendt\Autocms\routes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
//use Illuminate\Routing\Route;

class Cms
{
    public static function all()
    {
        App::make('router')->get('{slug}', 'CMSController@page')->name('cms.page');
        App::make('router')->post('{slug}', 'CMSController@pagePost')->name('cms.update.page');
        App::make('router')->post('{slug}/element', 'CMSController@updateContent')->name('cms.update.element');
        App::make('router')->get('{slug}/list/{id}', 'CMSController@list')->name('cms.list');
        App::make('router')->post('{slug}/list/move', 'CMSController@listMove')->name('cms.move.list');
        App::make('router')->post('{slug}/list/{detail_id}', 'CMSController@listPost')->name('cms.update.list.detail');
        App::make('router')->get('{slug}/list/d/{detail_id}', 'CMSController@listDelete')->name('cms.delete.list.detail');

        App::make('router')->get('/', 'CMSController@config')->name('cms.config');
        App::make('router')->post('/', 'CMSController@configPost')->name('cms.update.config');

    }
}

