<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'city',
        'country',
        'street',
        'state',
        'post_code',
    ];

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = strtolower($value);
    }

    public function getTypeAttribute($value)
    {
        return ucfirst($value);
    }

    public function setCityAttribute($value)
    {
        $this->attributes['city'] = ucfirst($value);
    }

    public function getCityAttribute($value)
    {
        return strtoupper($value);
    }

    public function setCountryAttribute($value)
    {
        $this->attributes['country'] = strtoupper($value);
    }

    public function setStreetAttribute($value)
    {
        $this->attributes['street'] = ucfirst($value);
    }

    public function setStateAttribute($value)
    {
        $this->attributes['state'] = ucfirst($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}