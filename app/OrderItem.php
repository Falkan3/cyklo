<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = array(
        'order_id',
        'product_item_id',
        'product_option_id'
    );

    public function product()
    {
        return $this->belongsTo('App\ProductItem');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function option()
    {
        return $this->belongsTo('App\ProductOption', 'productOption_id');
    }
}
