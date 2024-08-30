<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherHistory extends Model
{
    use HasFactory;
    protected $table = 'weather_histories';

    protected $fillable = [
        'user_id',
        'location_history',
    ];
}
