<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = array(
        'image_id',
    );

    public function products()
    {
        return $this->hasMany('App\ProductItem', 'productCategory_id');
    }

    public function images()
    {
        return $this->hasMany('App\ProductCategoryImage');
    }

    public function translation($id, $language = null)
    {
        if ($language == null)
            $language = App::getLocale();
        if ($language == config('app.fallback_locale')) {
            return null;
        } else {
            return ProductCategoryTranslation::where('product_category_id', '=', $id)->where('language', '=', $language)->get();
            //return $this->hasMany('App\ProductCategoryTranslation')->where('language', '=', $language);
        }
    }
}
