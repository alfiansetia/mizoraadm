<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\CustomApiTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RadeemController extends Controller
{

    use CustomApiTrait;

    public function index(Request $request)
    {
        $frontendToken = $request->header('Authorization');

        if (!$frontendToken) {
            return response()->json(['message' => 'Token from frontend is missing.'], 401);
        }
        try {
            $response = Http::withHeaders([
                'Authorization' => $frontendToken,
            ])->get($this->base_url . '/api/radeem/all');
            return $this->handle_response($response);
        } catch (Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan dalam pemanggilan API!'], 500);
        }
    }
}
