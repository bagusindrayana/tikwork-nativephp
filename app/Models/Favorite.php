<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $guarded = [];

    protected $casts = [
        'job_data' => 'array',
    ];
}
