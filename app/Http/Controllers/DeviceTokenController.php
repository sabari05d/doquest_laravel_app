<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeviceToken;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        $user = $request->user();

        // updateOrCreate so duplicates don't create many rows
        DeviceToken::updateOrCreate(
            ['token' => $request->token],
            ['user_id' => $user->id, 'platform' => $request->platform ?? 'web']
        );

        return response()->json(['status' => 'ok']);
    }
}

