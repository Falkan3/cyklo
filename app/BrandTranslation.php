<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrandTranslation extends Model
{
    protected $fillable = ['brand_id', 'language'];

    public function brand()
    {
        return $this->belongsTo('App\Brand', 'brand_id');
    }
}
