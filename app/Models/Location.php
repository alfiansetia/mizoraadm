<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = 'location';
    protected $fillable = [
        'name',
        'description',
        'location',
        'street1',
        'street2',
        'postal_code',
        'lat',
        'lng',
        'phone',
        'whatsapp',
        'day_of_week',
        'start_time',
        'end_time',
        'img'
    ];
}
