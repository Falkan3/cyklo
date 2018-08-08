<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductItemTranslation extends Model
{
    protected $fillable = ['product_item_id', 'language'];

    public function product()
    {
        return $this->belongsTo('App\ProductItem');
    }
}
