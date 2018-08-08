<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public function category()
    {
        return $this->belongsTo('App\ImageCategory');
    }

    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public function translation($id, $language = null)
    {
        if ($language == null)
            $language = \Lang::getLocale();
        if ($language == config('app.fallback_locale')) {
            return null;
        } else {
            return ImageTranslation::where('image_id', '=', $id)->where('language', '=', $language)->get();
            //return $this->hasMany('App\ImageTranslation')->where('language', '=', $language);
        }
    }
}
