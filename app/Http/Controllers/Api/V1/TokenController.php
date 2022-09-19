<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

class TokenController extends Controller
{
    public function createToken()
    {
        try {
            $user = User::where('email', 'admin@admin')->first();
        } catch (\Exception) {
            return response()->json(['success' => false, 'message' => 'Page not found'], 404);
        }
        $token = $user->createToken('token', ['*'], Carbon::now()->addMinutes(40))->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
        ], 200);
    }
}
