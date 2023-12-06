<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $fillable = [
        'email', 'phone', 'whatsapp', 'address', 'ig', 'youtube'
    ];
    public $timestamps = false;
}
