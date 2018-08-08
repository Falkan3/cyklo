<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryTranslation extends Model
{
    protected $fillable = ['product_category_id', 'language'];

    public function category()
    {
        return $this->belongsTo('App\ProductCategory', 'product_category_id');
    }
}
