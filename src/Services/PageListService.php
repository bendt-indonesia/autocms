<?php

namespace Bendt\Autocms\Services;

use Illuminate\Support\Facades\DB;
use Bendt\Autocms\Models\Page;
use Bendt\Autocms\Models\PageList;
use Bendt\Autocms\Models\PageListDetail;
use Bendt\Autocms\Models\PageListElement;
use Bendt\Autocms\Models\PageListPreset;
use Bendt\Autocms\Services\LanguageService;
use Illuminate\Support\Facades\Storage;

class PageListService
{
    public static function getAll()
    {
        return PageList::with('details')->get();
    }

    public static function getById($id)
    {
        return PageList::find($id);
    }

    public static function getBySlug($slug)
    {
        return PageList::with('details','details.elements','preset')->where('slug',$slug)->first();
    }

    public static function getDetailById($id)
    {
        return PageListDetail::find($id);
    }

    public static function getElementById($id)
    {
        return PageListElement::find($id);
    }

    public static function getListForView($slug, $locale)
    {
        $page = PageList::with(['page' => function ($query) use ($slug) {
            $query->where('page.slug', $slug);
        }])->where('locale', $locale)->get();
        return $page;
    }

    public static function create($input)
    {
        $model = new PageList($input);
        $model->save();
        return $model;
    }

    public static function createDetail($input)
    {
        DB::statement("UPDATE page_list_detail SET sort_no = sort_no+1 where page_list_id = :id AND sort_no >= :sort",['id'=>$input['page_list_id'],'sort'=>$input['sort_no']]);

        $model = new PageListDetail($input);
        $model->save();

        self::resort($input['page_list_id']);

        return $model;
    }

    public static function createElement($presets, $detail_id, $locale, $formData, $files)
    {
        foreach ($presets as $el) {
            if(array_key_exists($el->name,$formData)) {
                $new = collect($el)->toArray();
                unset($new['id'],$new['page_list_id'],$new['created_at'],$new['updated_at']);
                $model = new PageListElement($new);
                $model->page_list_detail_id = $detail_id;
                $model->locale = $locale;
                $model->rules = $new['rules'];

                if($el->type =='file' && isset($files[$el->name])) {
                    $file_name = md5(microtime());
                    $model->content = ImageService::generateFilename($files[$model->name], '/cms/', $file_name);
                    ImageService::save($files[$model->name], '/cms/', $file_name);

                } else {
                    $model->content = $formData[$model->name];
                }
                $model->save();
            }
        }

    }

    public static function createPreset($input)
    {
        $model = new PageListPreset($input);
        $model->save();
        return $model;
    }

    public static function createElementsFromSlug($list_slug, $input, $files)
    {
        $list = self::getBySlug($list_slug);
        $detail = self::createDetail(['page_list_id'=>$list->id, 'sort_no'=>$input['sort_no']]);
        unset($input['sort_no']);

        $language = cstore('language');
        $locales = [];

        foreach($language as $locale) {
            $locales[] = $locale->iso;
        }

        foreach ($input as $locale=>$language_tab) {
            if(in_array($locale,$locales)) {
                $files_tab = isset($files[$locale])?$files[$locale]:null;
                self::createElement($list->preset, $detail->id, $locale, $language_tab, $files_tab);
            }
        }
        return true;
    }

    public static function deleteDetail($id)
    {
        try {
            $model = PageListDetail::find($id);
            if ($model) {
                $list_id = $model->page_list_id;
                $files = $model->elements_type('file')->get();
                foreach ($files as $row) {
                    if (!is_null($row->content)) {
                        self::remove($row->content);
                    }
                }
                $model->delete();
                self::resort($list_id);
                return true;
            }
            return false;
        } catch (QueryException $e) {
            throw new Exception($e->errorInfo[2]);
        }
    }

    public static function getPageListElementRules($detail_id, $locale = null)
    {
        $page = self::getDetailById($detail_id);
        $rules = [];
        foreach ($page->elements as $row) {
            if($locale !== null && $row->locale !== $locale) continue;
            if (!is_null($row->rules)) $rules[$row->locale.".".$row->name] = $row->rules;
        }
        return $rules;
    }

    public static function getPageListPresetRules($slug)
    {
        $list = self::getBySlug($slug);
        $rules = [];
        foreach(cstore('language') as $lang) {
            foreach ($list->preset as $row) {
                if (!is_null($row->rules)) $rules[$lang->iso.'.'.$row->name] = $row->rules;
            }
        }

        return $rules;
    }

    private static function remove($file)
    {
        Storage::delete('public' . $file);
    }

    private static function _getLatestIndex($list_id)
    {
        $index = PageListDetail::where('page_list_id', $list_id)->max('sort_no');
        return $index ? $index + 1 : 1;
    }

    public static function move($input)
    {
        $list_id = $input['list_id'];
        $id = $input['id'];
        $type = $input['type'];
        $model = PageListDetail::where('page_list_id', $list_id)->find($id);
        $current_index = $model->sort_no;
        if ($type == 'promote' && $current_index > 1) {
            $list = PageListDetail::where('page_list_id', $list_id)->where('sort_no', '>=', ($current_index - 1))->orderBy('sort_no', 'desc')->get();
            foreach ($list as $row) {
                $row->sort_no++;
                $row->save();
            }
            $model->sort_no = $current_index - 1;
            $model->save();
        } else if ($type == 'demote') {
            $list = PageListDetail::where('page_list_id', $list_id)->where('sort_no', '<=', ($current_index + 1))->orderBy('sort_no', 'asc')->get();
            foreach ($list as $row) {
                $row->sort_no--;
                $row->save();
            }
            $model->sort_no = $current_index + 1;
            $model->save();
        }
        self::resort($list_id);
    }

    public static function resort($list_id)
    {
        $list = PageListDetail::where('page_list_id', $list_id)->orderBy('sort_no', 'asc')->get();
        foreach ($list as $index => $row) {
            $row->sort_no = $index + 1;
            $row->save();
        }
    }

    public static function updateDetail($id, $obj_array)
    {
        try {
            DB::beginTransaction();
            // Get Model from database
            $model = self::getDetailById($id);
            $model->fill($obj_array);
            $model->save();
            self::resort($model->page_list_id);
            DB::commit();
        } catch (QueryException $exception) {
            throw new Exception($exception->errorInfo[2]);
        }
    }

    public static function updateListElements($detail_id, $input, $files)
    {
        $detail = self::getDetailById($detail_id);
        foreach ($detail->elements as $index => $row) {
            if ($row->type == 'file' && isset($files[$row->locale][$row->name])) {
                $file_name = md5(microtime());
                $row->content = ImageService::generateFilename($files[$row->locale][$row->name], '/cms/', $file_name);
                ImageService::save($files[$row->locale][$row->name], '/cms/', $file_name);
                $row->save();
            } else if (array_key_exists($row->name,$input[$row->locale])) {
                $row->content = $input[$row->locale][$row->name];
                $row->save();
            }
        }

    }
}
