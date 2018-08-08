<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandImage extends Model
{
    protected $fillable = array(
        'brand_id',
        'image_id',
    );

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }
}
