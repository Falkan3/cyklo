<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $fillable = array(
        'product_item_id',
    );

    public function product()
    {
        return $this->belongsTo('App\ProductItem', 'productItem_id');
    }

    public function translation($id, $language = null)
    {
        if ($language == null)
            $language = App::getLocale();
        if ($language == config('app.fallback_locale')) {
            return null;
        } else {
            return ProductCategoryTranslation::where('product_option_id', '=', $id)->where('language', '=', $language)->get();
            //return $this->hasMany('App\ProductOptionTranslation')->where('language', '=', $language);
        }
    }
}
