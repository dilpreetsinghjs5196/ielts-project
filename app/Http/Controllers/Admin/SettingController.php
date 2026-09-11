<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function toggleComingSoon(Request $request)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        Setting::updateOrCreate(
            ['key' => 'coming_soon'],
            ['value' => $request->status ? '1' : '0']
        );

        return response()->json(['success' => true, 'message' => 'Coming Soon status updated successfully.']);
    }
}
