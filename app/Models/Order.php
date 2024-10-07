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

    public function user()
    {
        // Giả sử bảng orders có cột user_id là khóa ngoại để liên kết với bảng users
        return $this->belongsTo(User::class, 'user_id');
    }

    // Định nghĩa mối quan hệ giữa Order và OrderDetai
}
