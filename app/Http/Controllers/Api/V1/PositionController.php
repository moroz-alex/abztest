<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PositionCollection;
use App\Models\Position;

class PositionController extends Controller
{
    public function index()
    {
        try {
            $positions = Position::all();
        } catch (\Exception) {
            return response()->json(['success' => false, 'message' => 'Page not found'], 404);
        }

        if ($positions->count() > 0) {
            return new PositionCollection($positions);
        } else {
            return response()->json(['success' => false, 'message' => 'Positions not found'], 422);
        }
    }
}
