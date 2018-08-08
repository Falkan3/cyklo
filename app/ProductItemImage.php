<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductItemImage extends Model
{
    protected $fillable = array(
        'product_item_id',
        'image_id',
    );

    public function product()
    {
        return $this->belongsTo('App\ProductItem');
    }
}
