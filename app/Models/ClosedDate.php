<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClosedDate extends Model
{
    protected $fillable = [
        'closed_on',
        'reason',
    ];

    protected $casts = [
        'closed_on' => 'date',
    ];
}