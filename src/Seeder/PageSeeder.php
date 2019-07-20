<?php

namespace Bendt\Autocms\Seeder;

use Illuminate\Support\Arr;
use Bendt\Autocms\Services\PageService;
use Bendt\Autocms\Services\PageListService;

class PageSeeder
{
    public static function page($parent_id = NULL, $name, $slug, $contents = [])
    {
        $page = PageService::create([
            'parent_id' => $parent_id,
            'name' => $name,
            'slug' => $slug ? $slug : Str::slug($name),
        ]);
        foreach ($contents as $index=>$row) {
            if (isset($row[0])) {
                foreach ($row as $idx=>$raw) {
                    $raw['sort_no'] = $idx+1;
                    $page->elements()->create($raw);
                }
            } else {
                $row['sort_no'] = $index+1;
                $page->elements()->create($row);
            }
        }
        return $page;
    }

    public static function element($locale = 'en', $name, $content, $optional = [])
    {
        $allowed = ['type', 'rules', 'label', 'sort_no', 'placeholder', 'group_id', 'note'];
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

    public static function list($page_id, $name, $preset = [], $elements = [])
    {
        $list = PageListService::create([
            'page_id' => $page_id,
            'name' => $name,
            'slug' => Str::slug($name),
        ]);

        foreach ($preset as $idx=>$row) {
            $row['page_list_id'] = $list->id;
            $row['sort_no'] = $idx+1;
            unset($row['content'],$row['locale']);
            $list->preset()->create($row);
        }
        foreach ($elements as $idx=>$row) {
            $detail = $list->details()->create([
                'page_list_id' => $list->id,
                'sort_no' => $idx+1
            ]);

            foreach ($row as $el_idx => $el) {
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
        $arr = [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $description
        ];

        if($id !== null) {
            $arr['id'] = $id;
        }

        $group = PageService::createGroup($id);

        return $group;
    }
}
