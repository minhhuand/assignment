<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

    // Mối quan hệ tới bảng order_details
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
