<?php

namespace App\Http\Controllers\REST\Images;

use App\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ImageCategory;
use App\ImageCategoryTranslation;

class ImageCategoryController extends Controller
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
        $categories = ImageCategory::all();
        foreach ($categories as $category) {
            if (isset($category)) {
                if (\Lang::getLocale() !== config('app.fallback_locale')) {
                    $trans = $category->translation($category->id, \Lang::getLocale());
                    if (isset($trans)) {
                        if (isset($trans[0]['attributes']['name']))
                            $category->name = $trans[0]['attributes']['name'];
                    }
                }
            }
        }

        return $categories;
        //return view('admin.REST.imagecategories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.REST.imagecategories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new = new ImageCategory;
        $new->name = substr($request['name'], 0, 25);

        $new->save();

        //insert localizations
        foreach (config('app.locales') as $key => $locale) {
            if ($key !== config('app.fallback_locale')) {
                if (isset($request['name_' . $key])) {
                    $trans = new ImageCategoryTranslation;
                    $trans->image_category_id = $new->id;
                    $trans->language = $key;

                    if (isset($request['name_' . $key])) {
                        $trans->name = $request['name_' . $key];
                    }
                    $trans->save();
                }
            }
        }

        return back()->with(['message' => __('pages/admin.imgcategorycreated')]);
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
     * @return \Illuminate\Http\Response
     */
    public function edit($lang = null, $id)
    {
        $category = ImageCategory::find($id);
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
            return view('admin.REST.imagecategories.edit', ['imagecategory' => $category, 'translations' => $trans_array]);
        } else {
            return back()->with(['error' => __('pages/admin.imgcategorynotfound')]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string $lang =null
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update($lang = null, $id, Request $request)
    {
        $old = ImageCategory::find($id);
        if (isset($old)) {
            $old->name = substr($request['name'], 0, 25);

            $old->save();

            //insert localizations
            foreach (config('app.locales') as $key => $locale) {
                if ($key !== config('app.fallback_locale')) {
                    if (isset($request['name_' . $key])) {
                        $trans = new ImageCategoryTranslation;
                        $trans = ImageCategoryTranslation::firstOrNew(['image_category_id' => $old->id, 'language' => $key]);
                        $trans->image_category_id = $old->id;
                        $trans->language = $key;
                        $trans->name = $request['name_' . $key];

                        $trans->save();
                    }
                }
            }
        } else {
            return back()->withInput()->with(['error' => __('pages/admin.imgcategorynotfound')]);
        }

        return back()->with(['message' => __('pages/admin.imgcategoryupdated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $lang =null
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang = null, $id)
    {
        try {
            ImageCategory::destroy($id);
            return redirect(url(\Lang::getLocale() . '/REST/images/', null, env('HTTPS')))->with('message', __('pages/admin.imgcategorydeleted'));
        } catch (\Exception $ex) {
            return back()->with(['error' => __('pages/admin.imgcategorydeletederror')]);
        }
    }
}
