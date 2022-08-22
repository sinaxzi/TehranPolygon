<?php

namespace App\Models;

use App\Casts\Polygon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'shape'
    ];

    protected $casts =[
        'shape' => Polygon::class
    ];
}
