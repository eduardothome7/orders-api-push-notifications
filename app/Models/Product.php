<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title', 'category', 'price', 'variation_type'];

    public static function createWithVariation(array $productData, array $variationData)
    {
        $product = self::create($productData);
        $product->variations()->create($variationData);

        return $product->load('variations');
    }

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }
}