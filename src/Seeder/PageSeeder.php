<?php

namespace Bendt\Autocms\Seeder;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Bendt\Autocms\Services\PageService;
use Bendt\Autocms\Services\PageListService;
use Bendt\Autocms\Models\Page;
use Bendt\Autocms\Models\PageGroup;
use Bendt\Autocms\Models\PageList;

class PageSeeder
{
    public static function getPageByKey() {
        $page = Page::with(['elements'])->get();

        $pageKeyBySlug = collect($page)->keyBy('slug')->all();

        foreach ($pageKeyBySlug as $slug=>$page) {
            $pageKeyBySlug[$slug]->elements = PageService::map_elements_row($page->elements);
        }

        return $pageKeyBySlug;
    }

    public static function findPage($slug) {
        $page = Page::with(['elements'])->where('slug',$slug)->first();
        if($page) {
            $page->elements = PageService::map_elements_row($page->elements);
        }
        return $page;
    }

    public static function page($parent_id = NULL, $name, $slug, $contents = [])
    {
        $slug = $slug ? $slug : Str::slug($name);
        $page = self::findPage($slug);
        $elements = false;

        if($page) {
            $elements = $page->elements;
            unset($page->elements);
            $page->name = $name;
            $page->save();
        } else {
            $page = PageService::create([
                'parent_id' => $parent_id,
                'name' => $name,
                'slug' => $slug,
            ]);
        }

        //dd(json_encode($elements));

        foreach ($contents as $index=>$row) {
            $name = $row['name'];
            $locale = $row['locale'];

            if(isset($elements[$locale][$name])) {
                unset($row['content'], $row['locale']);
                $el = $elements[$locale][$name];
                //Update semua element, kecuali CONTENT dan LOCALE
                $el->fill($row);
                $el->save();
            } else {
                // Buat element Baru
                $row['sort_no'] = $index+1;
                $page->elements()->create($row);
            }
        }
        return $page;
    }

    public static function element($locale = 'en', $name, $content, $optional = [])
    {
        $allowed = ['type', 'rules', 'rules_store', 'rules_update', 'label', 'sort_no', 'placeholder', 'group_id', 'note'];
        $return = [
            'name' => Str::slug($name),
            'content' => $content,
            'locale' => $locale,
        ];
        foreach ($optional as $index => $row) {
            if (in_array($index, $allowed)) {
                $return[$index] = $row;
            }
        }
        return $return;
    }

    public static function list($page_id, $name, $configPresets = [], $configElements = [], $delete_old_elements = false)
    {
        $slug = Str::slug($name);

        $list = PageList::with(['preset','elements','details.elements'])->where('page_id',$page_id)->where('slug',$slug)->first();
        $listPresets = false;
        $configPresetsKeyByName = collect($configPresets)->keyBy('name')->toArray();

        if(!$list) {
            $list = PageListService::create([
                'page_id' => $page_id,
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        } else {
            $listPresets = collect($list->preset)->keyBy('name')->all();
            $listElements = PageService::map_elements_row($list->elements);

            if($delete_old_elements) {
                foreach ($list->details as $detail) {
                    foreach ($detail->elements as $el) {
                        $el->delete();
                    }
                    $detail->delete();
                }
            } else {
                foreach ($listElements as $lang => $elements) {
                    foreach ($elements as $el_idx => $el) {
                        if(isset($configPresetsKeyByName[$el->name])) {
                            $config = $configPresetsKeyByName[$el->name];
                            $el->type = $config['type'];
                            $el->rules = $config['rules'];
                            $el->label = $config['label'];
                            $el->placeholder = $el['placeholder'];
                            $el->note = $config['note'];
                            if(isset($config['rules_store'])) {
                                $el->rules_store = $config['rules_store'];
                            }
                            if(isset($config['rules_update'])) {
                                $el->rules_update = $config['rules_update'];
                            }
                            $el->save();
                        } else {
                            $el->delete();
                        }
                    }
                }
            }
            foreach ($listPresets as $name=>$preset) {
                if(!isset($configPresetsKeyByName[$name])) $preset->delete();
            }
        }

        foreach ($configPresets as $idx=>$row) {
            unset($row['content'],$row['locale']);
            $name = $row['name'];
            $row['page_list_id'] = $list->id;
            $row['sort_no'] = $idx+1;
            if(isset($listPresets[$name])) {
                $listPresets[$name]->fill($row);
                $listPresets[$name]->save();
            } else {
                $list->preset()->create($row);
            }
        }

        foreach ($configElements as $idx=>$row) {
            $sort_no = $idx+1;
            $detail = $list->details()->create([
                'page_list_id' => $list->id,
                'sort_no' => $idx+1
            ]);

            foreach ($row as $el_idx => $el) {
                $name = $el['name'];
                $locale = $el['locale'];
                $el['sort_no'] = $el_idx+1;
                $detail->elements()->create($el);
            }

        }

        return $list;
    }

    public static function meta($locale = 'en', $group_id, $meta_title, $meta_desc, $meta_key = NULL)
    {
        $return = [
            [
                'locale' => $locale,
                'name' => 'meta-title',
                'content' => $meta_title,
                'rules' => 'required|max:60',
                'group_id' => $group_id
            ],
            [
                'locale' => $locale,
                'name' => 'meta-description',
                'content' => $meta_desc,
                'rules' => 'max:160',
                'group_id' => $group_id
            ]
        ];
        if (!is_null($meta_key)) {
            $return[] = [
                'locale' => $locale,
                'name' => 'meta-keywords',
                'content' => $meta_key,
                'rules' => 'required|max:255',
                'group_id' => $group_id
            ];
        }
        return $return;
    }

    public static function group($name, $description = null, $id = null)
    {
        $slug = Str::slug($name);
        $group = PageGroup::where('slug',$slug)->first();

        $arr = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description
        ];
        if($id !== null) {
            $arr['id'] = $id;
        }
        if($group) {
            $group->fill($arr);
            $group->save();
        } else {
            $group = PageService::createGroup($arr);
        }

        return $group;
    }
}
