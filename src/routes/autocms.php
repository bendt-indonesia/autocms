<?php

Route::group([
    'prefix' => '/backend/cms/',
    'namespace' => 'Bendt\Autocms\Controllers',
    'middleware' => ['web','auth']
], function() {
    Route::get('/config', 'CMSController@config')->name('cms.config');
    Route::post('/config', 'CMSController@configPost')->name('cms.update.config');

    Route::get('{slug}', 'CMSController@page')->name('cms.page');
    Route::post('{slug}', 'CMSController@pagePost')->name('cms.update.page');
    Route::post('{slug}/element', 'CMSController@updateContent')->name('cms.update.element');
    Route::get('{slug}/list/{list_slug}', 'CMSController@list')->name('cms.list');
    Route::post('{slug}/list/', 'CMSController@listPost')->name('cms.update.list.detail');
    Route::post('{slug}/list/move', 'CMSController@listMove')->name('cms.move.list');
    Route::post('{slug}/list/{list_slug}/create', 'CMSController@createListDetail')->name('cms.create.list.detail');
    Route::get('{slug}/list/d/{detail_id}', 'CMSController@listDelete')->name('cms.delete.list.detail');

    Route::get('{slug}/list/edit/{detail_id}', 'CMSController@listDetailEdit')->name('cms.edit.list.detail');
});

Route::post('/backend/upload_image', 'UploadController@image')->name('upload_image');
