<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    protected $fillable = array(
        'product_category_id',
        'brand_id'
    );

    public function cartItems()
    {
        return $this->hasMany('App\CartItem');
    }

    public function options()
    {
        return $this->hasMany('App\ProductOption', 'productItem_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand', 'brand_id');
    }

    public function category()
    {
        return $this->belongsTo('App\ProductCategory', 'productCategory_id');
    }

    public function images()
    {
        return $this->hasMany('App\ProductItemImage');
    }

    public function translation($id, $language = null)
    {
        if ($language == null)
            $language = App::getLocale();
        if ($language == config('app.fallback_locale')) {
            return null;
        } else {
            return ProductItemTranslation::where('product_item_id', '=', $id)->where('language', '=', $language)->get();
            //return $this->hasMany('App\ProductItemTranslation')->where('language', '=', $language);
        }
    }
}
