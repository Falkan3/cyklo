<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageTranslation extends Model
{
    protected $fillable = ['image_id', 'language'];

    public function image()
    {
        return $this->belongsTo('App\Image');
    }
}
