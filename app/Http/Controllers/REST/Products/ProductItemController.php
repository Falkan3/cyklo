<?php

namespace App\Http\Controllers\REST\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;

use App\User;
use Auth;
use App\ProductItem;
use App\ProductItemTranslation;
use App\ProductCategory;
use App\ProductCategoryTranslation;

class ProductItemController extends Controller
{
    //REST

    /**
     * Display a listing of the resource.
     *
     * @param  string $lang =null
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index($lang = null, Request $request)
    {
        $offset = $request['offset'];
        $limit = $request['limit'];
        $direction = $request['direction'];

        if (empty($offset) || !is_integer(intval($offset)) || $offset < 0)
            $offset = 0;
        if (empty($limit) || !is_integer(intval($limit)) || $limit < 1)
            $limit = 100;

        if (isset($direction)) {
            if ($direction === 'next') {
                $offset += $limit;
            } elseif ($direction === 'prev') {
                $offset -= $limit;
            }
        }

        $products = $this->batch($offset, $limit);

        if ($offset < 0)
            $offset = 0;
        if ($limit < 1)
            $limit = 1;

        $categories = app('App\Http\Controllers\REST\Products\ProductCategoryController')->index();
        return view('admin.REST.productitems.index', ['offset' => $offset, 'limit' => $limit, 'products' => $products, 'categories' => $categories]);
    }

    public function batch($offset = 0, $limit = 100)
    {
        $products = ProductItem::offset($offset)->limit($limit)->get();
        //get ids of images from sql query
        $ids = [];
        $image_ids = [];
        foreach ($products as $product) {
            $ids[] =  $product->id;
            $image_ids[] = $product->image_id;
        }

        $images_data = HelperController::getImages(\Lang::getLocale(), $image_ids);
        $variations_data = []; //todo: add  variation controller

        //translate if neccessary
        foreach ($products as $product) {
            $image_data = null;
            //insert corresponding image data to the item in the product array
            foreach ($images_data as $key => $img) {
                if ($key === $product->image_id)
                    $image_data = $img;
            }
            //insert corresponding variation data to the item in the product array
            foreach ($variations_data as $key => $var) {
                if ($key === $product->id)
                    $variation_data = $var;
            }

            $trans = $product->translation($product->id, \Lang::getLocale());
            if (isset($trans)) {
                if (isset($trans[0]['attributes']['name']))
                    $product->name = $trans[0]['attributes']['name'];
                if (isset($trans[0]['attributes']['description']))
                    $product->description = $trans[0]['attributes']['description'];
            }
            $product['image_data'] = $image_data;
            $product['variation'] = $variation_data;
        }

        return $products;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  string $lang =null
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($lang = null, $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $lang =null
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang = null, $id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string $lang =null
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($lang = null, $id, Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $lang =null
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang = null, $id, Request $request)
    {

    }
}
