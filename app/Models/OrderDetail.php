<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_details'; 
    public $with = ['product'];

    // Mối quan hệ tới bảng products
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Thiết lập mối quan hệ với Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
