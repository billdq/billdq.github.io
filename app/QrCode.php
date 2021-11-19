<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $casts = [
        'update_time' => 'datetime',
    ];

    public function order_category() {
        return $this->belongsTo('App\OrderCategory');
    }
}
