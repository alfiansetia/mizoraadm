<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends BaseController
{
    public function index()
    {
        $data = Reward::get();
        return $this->successResponse($data);
    }
}
