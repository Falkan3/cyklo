<?php

namespace App\Http\Controllers\REST\Products;

use App\Http\Controllers\REST\Images\ImageController;
use App\ProductCategoryTranslation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\ProductCategory;

class ProductCategoryController extends Controller
{
    //REST

    /**
     * Display a listing of the resource.
     *
     * @param  string $lang =null
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index($lang = null)
    {
        $categories = ProductCategory::all();
        foreach ($categories as $category) {
            if (isset($category)) {
                if (\Lang::getLocale() !== config('app.fallback_locale')) {
                    $trans = $category->translation($category->id, \Lang::getLocale());
                    if (isset($trans)) {
                        if (isset($trans[0]['attributes']['name']))
                            $category->name = $trans[0]['attributes']['name'];
                        if (isset($trans[0]['attributes']['description']))
                            $category->name = $trans[0]['attributes']['description'];
                    }
                }
            }
        }

        return $categories;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $images_data = app('App\Http\Controllers\REST\Images\ImageController')->getImagesForImageSelector($request);

        return view('admin.REST.productcategories.create', $images_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $new = new ProductCategory;
        $new->name = substr($request['name'], 0, 25);
        $new->description = substr($request['description'], 0, 250);
        if(isset($request['image_id']))
            $new->image_id = intval($request['image_id']);
        else
            $new->image_id = null;

        $new->save();

        //insert localizations
        foreach (config('app.locales') as $key => $locale) {
            if ($key !== config('app.fallback_locale')) {
                if (isset($request['name_' . $key]) || isset($request['description_' . $key])) {
                    $trans = new ProductCategoryTranslation();
                    $trans->product_category_id = $new->id;
                    $trans->language = $key;

                    if (isset($request['name_' . $key])) {
                        $trans->name = $request['name_' . $key];
                    }
                    if (isset($request['description_' . $key])) {
                        $trans->description = $request['description_' . $key];
                    }
                    $trans->save();
                }
            }
        }

        return back()->with(['message' => __('pages/admin.productcategorycreated')]);
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
        return null;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $lang =null
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit($lang = null, $id, Request $request)
    {
        $category = ProductCategory::find($id);
        $trans_array = [];
        //$image['src'] = HelperController::getImage(null, $id);
        if (isset($category) && !empty($category)) {
            foreach (config('app.locales') as $key => $locale) {
                if ($key != config('app.fallback_locale')) {
                    $trans = $category->translation($category->id, $key);
                    if (isset($trans) && isset($trans[0])) {
                        $trans_array[$key] = $trans[0]['attributes'];
                    }
                }
            }

            $return_data = [
                'productcategory' => $category,
                'translations' => $trans_array,
            ];
            $images_data = app('App\Http\Controllers\REST\Images\ImageController')->getImagesForImageSelector($request);
            $return_data = array_merge($return_data, $images_data);
            return view('admin.REST.productcategories.edit', $return_data);
        } else {
            return back()->with(['error' => __('pages/admin.productcategorynotfound')]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string $lang =null
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($lang = null, $id, Request $request)
    {
        $old = ProductCategory::find($id);
        if (isset($old)) {
            $old->name = substr($request['name'], 0, 25);
            $old->description = substr($request['description'], 0, 250);
            if(isset($request['image_id']) && $request['image_id'])
                $old->image_id = intval($request['image_id']);
            else
                $old->image_id = null;

            $old->save();

            //insert localizations
            foreach (config('app.locales') as $key => $locale) {
                if ($key !== config('app.fallback_locale')) {
                    if (isset($request['name_' . $key]) || isset($request['description_' . $key])) {
                        $trans = new ProductCategoryTranslation;
                        $trans = ProductCategoryTranslation::firstOrNew(['product_category_id' => $old->id, 'language' => $key]);
                        $trans->product_category_id = $old->id;
                        $trans->language = $key;

                        if (isset($request['name_' . $key])) {
                            $trans->name = $request['name_' . $key];
                        }
                        if (isset($request['description_' . $key])) {
                            $trans->description = $request['description_' . $key];
                        }
                        $trans->save();
                    }
                }
            }
        } else {
            return back()->withInput()->with(['error' => __('pages/admin.productcategorynotfound')]);
        }

        return back()->with(['message' => __('pages/admin.productcategoryupdated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $lang =null
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang = null, $id, Request $request)
    {
        try {
            ProductCategory::destroy($id);
            return redirect(url(\Lang::getLocale() . '/REST/productitems/', null, env('HTTPS')))->with('message', __('pages/admin.productcategorydeleted'));
        } catch (\Exception $ex) {
            return back()->with(['error' => __('pages/admin.productcategorydeletederror')]);
        }
    }
}
