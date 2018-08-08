<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index() {
        return view('main.index');
    }

    public function store() {
        $categories = app('App\Http\Controllers\HelperController')->listProductCategories();
        return view('main.pages.store', ['categories' => $categories]);
    }

    public function catalog(Request $request) {
        $search_params = $this->get_search_parameters($request);
        return view('main.pages.catalog', ['search_params' => $search_params]);
    }

    public function form() {
        return view('main.form', []);
    }

    //Misc

    private function get_search_parameters(Request $request) {
        $search_params = [];
        if(isset($request->q_search_name)) {
            $search_params['q_search_name'] = $request->q_search_name;
        }
        if(isset($request->q_search_onsale)) {
            $search_params['q_search_onsale'] = $request->q_search_onsale;
        }
        if(isset($request->q_search_recommended)) {
            $search_params['q_search_recommended'] = $request->q_search_recommended;
        }
        if(isset($request->q_search_bestsellers)) {
            $search_params['q_search_bestsellers'] = $request->q_search_bestsellers;
        }
        if(isset($request->q_search_new)) {
            $search_params['q_search_new'] = $request->q_search_new;
        }

        return $search_params;
    }
}
