<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function index()
    {
        return redirect()->route('home');
    }

    public function fetchProfile()
    {
        $profile = Profile::firstOrCreate(['user_id' => 1]);
        // Return JSON data for Alpine to bind
        return response()->json(['profile' => $profile]);
    }

    public function update(Request $request)
    {
        $profile = Profile::firstOrCreate(['user_id' => 1]);

        $data = $request->only(['name', 'title', 'bio', 'categories']);

        // Handle categories if sent as comma-separated string from a simplified form
        if (isset($data['categories']) && is_string($data['categories'])) {
            $data['categories'] = array_map('trim', explode(',', $data['categories']));
        }

        $profile->update($data);

        return response()->json(['status' => 'success', 'profile' => $profile]);
    }
}
