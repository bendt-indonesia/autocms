<?php

namespace Bendt\Autocms\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Bendt\Autocms\Models\Page;
use Bendt\Autocms\Models\PageElement;
use Bendt\Autocms\Models\PageGroup;
use Bendt\Autocms\Models\PageList;
use Bendt\Autocms\Models\PageListDetail;
use Bendt\Autocms\Models\PageListElement;

use Bendt\Autocms\Services\LanguageService;

class PageService
{
    public static function get($slug)
    {
        $page = Page::with('elements','lists','lists.details','lists.details.elements')->where('slug', $slug)->first();
        $page = $page->toArray();
        $page['elements'] = self::map_elements($page['elements']);
        $page['lists'] = self::map_lists($page['lists']);
        return $page;
    }

    public static function map_lists($lists) {
        foreach ($lists as $idx=>$list) {
            $lists[$list['slug']] = $list['details'];
            unset($lists[$idx]);
        }

        foreach ($lists as $idx=>$list) {
            foreach ($list as $idx2=>$detail) {
                $lists[$idx][$idx2]['elements'] = self::map_elements($detail['elements']);
            }
        }
        return $lists;
    }

    public static function map_elements($elements) {
        $elements = collect($elements);
        $grouped_elements = $elements->groupBy('locale');

        foreach($grouped_elements as $locale=>$elements) {
            $grouped_elements[$locale] = self::map_key_content($elements,'name','content');
        }
        return $grouped_elements->toArray();
    }

    public static function map_key_content($obj, $key, $target) {
        $key_paired_contents = [];
        foreach ($obj as $element)
        {
            $key_paired_contents[$element[$key]] = $element[$target];
        }
        return $key_paired_contents;
    }

    public static function getAll()
    {
        return Page::with('elements')->get();
    }

    public static function getAllParentPage($slugs = NULL)
    {
        $page = Page::where('parent_id',NULL);

        if($slugs != NULL && count($slugs) > 0) {
            $page->whereIn('slug',[$slugs]);
        }

        return $page->with('elements')->get();
    }

    public static function getAllPage($slugs = NULL)
    {
        $page = Page::with('children');

        if($slugs != NULL && count($slugs) > 0) {
            $page->whereIn('slug',[$slugs]);
        }

        return $page->get();
    }

    public static function getChildPage($parent_slug) {
        $parent = self::getBySlug($parent_slug);
        if(!$parent) return false;
        return Page::where('parent_id',$parent->id)->with('elements')->get();
    }

    public static function getById($id)
    {
        return Page::with('elements')->where('id', $id)->first();
    }

    public static function getBySlug($slug)
    {
        return Page::with('elements')->where('slug', $slug)->first();
    }

    public static function getBySlugEN($slug,$lang = 'en')
    {
        return Page::with(['elements' => function($q) use ($lang) {
            $q->where('locale',$lang);
        }])->where('slug', $slug)->first();
    }

    public static function getPageElementRules($slug,$filter = [])
    {
        $rules = [];
        $page = self::getBySlug($slug);

        if(count($filter['groups'])) {
            $group = PageGroup::whereIn('slug',$filter['groups'])->pluck('id')->toArray();
        }

        foreach ($page->elements as $row) {
            if(
                (
                    count($filter['fields']) == 0 && count($filter['groups']) == 0
                    || isset($group) && count($group) > 0 && in_array($row->group_id,$group)
                    || count($filter['fields']) > 0 && in_array($row->name,$filter['fields'])
                ) && !is_null($row->rules)
            ) {
                $rules[$row->locale.".".$row->name] = $row->rules;
            }

        }
        return $rules;
    }

//    public static function getContentsBySlug($slug, $to_array = false)
//    {
//        $elements = PageElement::where('slug', $slug)->get();
//        return $to_array ? $elements->toArray() : $elements;
//    }
//

    public static function create($input)
    {
        $model = new Page($input);
        if(!$model->slug) {
            $model->slug = Str::slug($model->name);
        }

        $model->save();

        return $model;
    }

    public static function createGroup($input)
    {
        $model = new PageGroup($input);
        $model->save();

        return $model;
    }

    public static function createList($input)
    {
        $model = new PageList($input);
        $model->save();

        return $model;
    }

    public static function updatePageElementBySlug($slug, $input, $files)
    {
        $page = self::getBySlug($slug);
        $language = cstore('language');
        $locales = [];

        foreach($language as $locale) {
            $locales[] = $locale->iso;
        }

        foreach ($input as $index=>$language_tab) {
            if(in_array($index,$locales)) {
                $files_tab = isset($files[$index])?$files[$index]:null;
                self::updateElement($page->id,$index,$language_tab, $files_tab);
            }
        }
    }

    public static function updateElement($page_id, $locale, $tab, $files)
    {
        $elements = PageElement::where('page_id', $page_id)->where('locale',$locale)->get();

        foreach ($elements as $model) {
            if(array_key_exists($model->name,$tab)) {
                if($model->type =='file' && isset($files[$model->name])) {
                    $file_name = md5(microtime());
                    $model->content = ImageService::generateFilename($files[$model->name], '/cms/', $file_name);
                    ImageService::save($files[$model->name], '/cms/', $file_name);
                    $model->save();
                } else {
                    $model->content = $tab[$model->name];
                    $model->save();
                }
            }
        }
    }

    public static function processAndSave($model, $image = null)
    {
        $with_image = !is_null($image);

        // Insert Image Url
        if($with_image) {
            if($model->title != "") {
                $file_name = ImageService::slug_with_number($model->title);
            } else {
                $file_name = md5(microtime());
            }
            $model->image_url = ImageService::generateFilename($image, Model::$IMG_STORAGE_PATH, $file_name);
        }

        $model->save();

        //Save uploaded Image
        if($with_image && isset($file_name))
        {
            ImageService::save($image, Model::$IMG_STORAGE_PATH, $file_name);
        }
    }
}
