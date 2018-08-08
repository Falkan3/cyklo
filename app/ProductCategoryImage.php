<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryImage extends Model
{
    protected $fillable = array(
        'product_category_id',
        'image_id',
    );

    public function productCategory()
    {
        return $this->belongsTo('App\ProductCategory');
    }
}
