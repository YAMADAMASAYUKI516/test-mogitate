<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSeason extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'season_id',
    ];
}
