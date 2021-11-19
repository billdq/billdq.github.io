<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecycleOrder extends Model
{
    protected $fillable = [
        'title', 'weight', 'amount', 'date'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'amount' => 'integer',
        'date' => 'date',
    ];
}
