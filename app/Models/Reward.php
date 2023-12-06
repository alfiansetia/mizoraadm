<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;
    protected $table = 'reward';
    protected $fillable = ['name', 'category_reward_id', 'image', 'description', 'point', 'terms', 'howto', 'store', 'start_date_time', 'end_date_time'];


    public function category_reward()
    {
        return $this->hasOne(CategoryReward::class, 'id', 'category_reward_id');
    }
}
