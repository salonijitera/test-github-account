<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class HealthCheckController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true]);
    }
}
