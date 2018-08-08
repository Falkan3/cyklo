<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageCategory extends Model
{
    public function products()
    {
        return $this->hasMany('App\Image');
    }

    public function translation($id, $language = null)
    {
        if ($language == null)
            $language = App::getLocale();
        if ($language == config('app.fallback_locale')) {
            return null;
        } else {
            return ImageCategoryTranslation::where('image_category_id', '=', $id)->where('language', '=', $language)->get();
            //return $this->hasMany('App\ImageCategoryTranslation')->where('language', '=', $language);
        }
    }
}
