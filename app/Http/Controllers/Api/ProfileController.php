<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return response()->json($user->user);
    }

    public function create(Request $request)
    {
        try {
            $user = $request->user();
            $data = $request->validate([
                'biography' => 'nullable|string|max:255',
                'phone_number' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
            ]);

            $profile = $user->profile()->updateOrCreate($data);
            return response()->json($profile, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the profile.'], 500);
        }
    }
    public function uploadProfilePicture(Request $request)
    {
        try {
            $user = $request->user();
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->profile->update(['profile_picture' => $path]);
                return response()->json(['message' => 'Profile picture uploaded successfully', 'path' => $path]);
            }

            return response()->json(['message' => 'No file uploaded'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while uploading the profile picture.'], 500);
        }
    }
    public function updateProfilePicture(Request $request)
    {
        try {
            $user = $request->user();
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->profile->update(['profile_picture' => $path]);
                return response()->json(['message' => 'Profile picture updated successfully', 'path' => $path]);
            }

            return response()->json(['message' => 'No file uploaded'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the profile picture.'], 500);
        }
    }
}