<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = array(
        'cart_id',
        'product_item_id',
        'product_option_id'
    );

    public function cart()
    {
        return $this->belongsTo('App\Cart');
    }

    public function product()
    {
        return $this->belongsTo('App\ProductItem');
    }

    public function option()
    {
        return $this->belongsTo('App\ProductOption', 'productOption_id');
    }
}
