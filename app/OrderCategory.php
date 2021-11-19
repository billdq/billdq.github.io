<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderCategory extends Model
{
    protected $fillable = [
        'category', 'no_of_qr_code'
    ];

    protected $casts = [
        'no_of_qr_code' => 'integer',
    ];

    public function order() {
        return $this->belongsTo('App\Order');
    }

    public function qr_codes() {
        return $this->hasMany(QrCode::class);
    }
}
