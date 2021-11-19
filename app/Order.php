<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_no', 'remarks',
    ];

    public function order_categories() {
        return $this->hasMany(OrderCategory::class);
    }
}
