<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supermarket extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'name',
        'brand',
        'operator',
        'branch',
        'phone',
        'latitude',
        'longitude',
        'has_parking_attendant',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
