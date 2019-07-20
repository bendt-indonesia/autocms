<?php

namespace Bendt\Autocms\Seeder;

use Illuminate\Support\Arr;
use Bendt\Autocms\Services\PageListService;

class PageListSeeder
{
    public static function list($locale = 'en', $page_id, $name, $description = NULL, $contents = [])
    {
        $page = PageListService::create([
            'locale' => $locale,
            'page_id' => $page_id,
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $description,
        ]);
        foreach ($contents as $row) {
            $details = $page->details()->create($row[0]);
            if (count($row['elements']) > 0) {
                foreach ($row['elements'] as $rzw) {
                    $rzw['page_list_detail_id'] = $details->id;
                    PageListService::createElement($rzw);
                }
            }
        }
        return $page;
    }

    public static function detail($sort_no, $elements = [])
    {
        $return = [
            [
                'sort_no' => $sort_no
            ],
            'elements' => $elements
        ];
        return $return;
    }

    public static function preset($list_id, $elements = [])
    {
        foreach ($elements as $el) {
            unset($el['content']);
            $el['page_list_id'] = $list_id;
            PageListService::createPreset($el);
        }
    }

    public static function element($name, $content, $optional = [])
    {
        $allowed = ['type', 'rules', 'label', 'placeholder', 'note'];
        $return = [
            'name' => Str::slug($name),
            'content' => $content,
        ];
        foreach ($optional as $index => $row) {
            if (in_array($index, $allowed)) {
                $return[$index] = $row;
            }
        }
        return $return;
    }
}
