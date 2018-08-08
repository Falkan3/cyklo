<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOptionTranslation extends Model
{
    protected $fillable = ['product_option_id', 'language'];

    public function option()
    {
        return $this->belongsTo('App\ProductOption', 'product_option_id');
    }
}
