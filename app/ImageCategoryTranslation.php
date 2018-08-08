<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageCategoryTranslation extends Model
{
    protected $fillable = ['image_category_id', 'language'];

    public function imageCategory()
    {
        return $this->belongsTo('App\ImageCategory', 'image_category_id');
    }
}
