<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'variation_id', 'quantity'];

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // public static function create(array $orderData, array $variationData)
    // {
    //     $product = self::create($productData);
    //     $product->variations()->create($variationData);

    //     return $product->load('variations');
    // }
}