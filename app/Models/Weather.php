<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Weather extends Model
{
    use HasFactory;

    protected $fillable = [
       'city_id',
       'month',
       'day_of_month',
       'time',
       'temperature',
       'direction',
       'speed'
    ];

    public function city(): HasOne
    {
        return $this->hasOne(City::class);
    }
}
