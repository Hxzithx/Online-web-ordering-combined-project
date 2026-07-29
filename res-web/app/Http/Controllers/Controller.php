<?php

namespace App\Http\Controllers;

use function Symfony\Component\Clock\now;

abstract class Controller
{
    public function checkStatus()
    {
        return response()->json([
           'success' => true,
           'message' => 'API is running successfully',
           'timestamp' => now()
        ],200);
    }
}
