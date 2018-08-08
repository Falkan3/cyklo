<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public function products()
    {
        return $this->hasMany('App\ProductItem');
    }

    /*
    public function images()
    {
        return $this->hasMany('App\BrandImage');
    }
    */

    public function translation($id, $language = null)
    {
        if ($language == null)
            $language = \Lang::getLocale();
        if ($language == config('app.fallback_locale')) {
            return null;
        } else {
            return BrandTranslation::where('image_id', '=', $id)->where('language', '=', $language)->get();
            //return $this->hasMany('App\BrandTranslation')->where('language', '=', $language);
        }
    }
}
