<?php

namespace Bendt\Autocms\Controllers;

use App\Config\ConfigStore;
use Bendt\Autocms\Classes\PageManager;
use Illuminate\Support\Facades\DB;
use Bendt\Autocms\Classes\StoreManager;
use Bendt\Autocms\Facades\Config;
use Bendt\Autocms\Models\PageGroup;
use Bendt\Autocms\Services\PageListService;
use Bendt\Autocms\Services\PageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CMSController
{
    public $config;
    public $pageManager;
    public function __construct(ConfigStore $configStore, PageManager $pageManager)
    {
        $this->config = $configStore;
        $this->pageManager = $pageManager;
    }

    public function page(Request $request, $slug)
    {
        $model = $this->getPage($slug);
        $filter = $this->filterToArray($request);

        $elements = collect($model->elements);
        $grouped_elements = $elements->groupBy('locale');

        $lists = collect($model->lists);
        $grouped_list = $lists->groupBy('locale');

        $filter['groups'] = $this->filterGroupSlugtoId($filter['groups']);

        //return view('backend.dashboard');
        return view('autocms::backend.cms.page', [
                'model' => $model,
                'grouped_elements' => $grouped_elements,
                'grouped_list' => $grouped_list,
            ] + $filter);
    }

    public function pagePost(Request $request, $slug)
    {
        try {
            $filter = $this->filterToArray($request);
            $rules = PageService::getPageElementRules($slug, $filter);

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput($request->input());
            } else {
                PageService::updatePageElementBySlug($slug, $request->all(), $request->allFiles());
                $this->pageManager->clearCache($slug);
                $request->session()->flash('success', 'Page updated!');
                return back();
            }
        } catch (\Exception $e) {
            $request->session()->flash("errors", $e->getMessage());
            return back();
        }
    }

    public function config(Request $request)
    {
        return view('backend.config.config', [

        ]);
    }

    public function configPost(Request $request)
    {
        $this->config->updateMany($request->input());
        $request->session()->flash('success', 'Configuration updated!');
        return back();
    }

    public function list(Request $request, $slug, $list_slug)
    {
        $page = $this->getPage($slug);
        $model = PageListService::getBySlug($list_slug);

        if (!$page || !$model) return redirect()->back();

        return view('autocms::backend.cms.list', [
            'page' => $page,
            'model' => $model,
        ]);
    }

    public function listDetailEdit(Request $request, $slug, $detail_id)
    {
        $model = PageListService::getDetailById($detail_id);

        $elements = collect($model->elements);
        $grouped_elements = $elements->groupBy('locale');

        return view('autocms::backend.cms.list_edit', [
            'model' => $model,
            'grouped_elements' => $grouped_elements,
            'slug' => $slug
        ]);
    }

    public function listDelete(Request $request, $slug, $detail_id)
    {
        PageListService::deleteDetail($detail_id);
        $this->pageManager->clearCache($slug);
        return redirect()->back();
    }

    public function listMove(Request $request, $slug)
    {
        $rules = [
            'id' => 'required|exists:page_list_detail,id',
            'list_id' => 'required|exists:page_list,id',
            'type' => 'required|in:promote,demote'
        ];
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            PageListService::move($request->all());
            $this->pageManager->clearCache($slug);
            return response(["success"=>1]);
        } else {
            return response(["success"=>0])->setStatusCode(500);
        }
    }

    //AJAX POST
    public function listPost(Request $request, $slug)
    {
        $detail_id = $request->input('detail_id');
        $page = $this->getPage($slug);
        if (!$page)
            return response(["success"=>0])->setStatusCode(500);

        $rules = [
            'detail_id' => 'required|exists:page_list_detail,id',
            'sort_no' => 'required|numeric|min:1',
        ];
        $rules += PageListService::getPageListElementRules($detail_id);

        try {
            $validator = Validator::make($request->all(), $rules);
            if(!$validator->fails()) {

                $input = $request->input();
                PageListService::updateDetail($input['detail_id'],['sort_no'=>$input['sort_no']]);
                PageListService::updateListElements($input['detail_id'], $request->input(), $request->allFiles());
                $this->pageManager->clearCache($slug);
                return response(["success"=>1]);
            } else {
                return response(["success"=>0, 'msg' => $rules])->setStatusCode(500);
            }
        } catch (\Exception $exception) {
            return response(["success"=>0, 'msg' => 'Invalid Validation Rules!'])->setStatusCode(500);
        }
    }

    public function createListDetail(Request $request, $slug, $list_slug)
    {
        $page = $this->getPage($slug);
        if (!$page)
            return response(["success"=>0])->setStatusCode(500);

        $rules = [
            'sort_no' => 'required|numeric|min:1',
        ];
        $rules += PageListService::getPageListPresetRules($list_slug);

        $validator = Validator::make($request->all(), $rules);
        if(!$validator->fails()) {
            DB::beginTransaction();
            PageListService::createElementsFromSlug($list_slug, $request->all(), $request->allFiles());
            $this->pageManager->clearCache($slug);
            DB::commit();
            return redirect()->back()->with('success', 'New Item has been added');
        } else {
            return back()->withErrors($validator)->withInput($request->input());
        }
    }

    private function getPage($slug)
    {
        return PageService::getBySlug($slug);
    }

    private function filterToArray($request)
    {
        return [
            'fields' => $request->get('q') ? explode(",", $request->get('q')) : [],
            'groups' => $request->get('g') ? explode(",", $request->get('g')) : [],
            'lists' => $request->get('lists') ? explode(",", $request->get('lists')) : [],
        ];
    }

    private function filterGroupSlugtoId($groups)
    {
        $page_group = StoreManager::get('page_group');
        $page_group = collect($page_group);
        $page_group = $page_group->whereIn('slug',$groups)->pluck('id')->toArray();

        return $page_group;
    }

}
