<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
		public function update_profile(Request $request)
		{
			// dd($request->all(), $request->file('profile_image'));
			// Validate the request
			$data = $request->validate([
				'name' => 'required|string|max:255',
				'phone' => 'nullable|string|max:15',
				'email' => 'nullable|email|max:255',
				'profile_image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
				'old_password' => 'nullable|string',
				'new_password' => 'nullable|string|min:8|confirmed',
			]);

			// Get the authenticated user (assuming 'branch' is injected or available)
			$user = $request->branch;

			// Update the user's name, phone, and email if provided
			if ($request->has('name')) {
				$user->name = $data['name'];
			}

			if ($request->has('phone')) {
				$user->phone = $data['phone'];
			}

			if ($request->has('email')) {
				$user->email = $data['email'];
			}

			// Handle profile image upload
			if ($request->hasFile('profile_image')) {
				// Delete the previous image if it exists
				if ($user->image && file_exists(public_path($user->image))) {
					unlink(public_path($user->image));
				}

				// Generate the custom file name
				$file = $request->file('profile_image');
				$extension = $file->getClientOriginalExtension();
				$customName = now()->format('Y-m-d') . '-' . uniqid() . '.' . $extension;

				// Save the file to the public/assets/branch folder
				$path = $file->storeAs('public/assets/branch', $customName);

				// Update the profile_image field with the new path
				$user->image = str_replace('public/', 'storage/', $path);
			}

			// Handle password update
			if ($request->has('old_password') && $request->has('new_password')) {
				// Check if the old password matches the user's current password
				if (!Hash::check($data['old_password'], $user->password)) {
					return response()->json([
						'message' => 'The provided old password is incorrect.',
					], 422);
				}

				// Update the user's password
				$user->password = Hash::make($data['new_password']);
			}

			// Save the updated user
			$user->save();

			// Prepare response with full profile image URL
			$response = $user->toArray();
			$response['profile_image'] = $user->image ? url($user->image) : null;

			// Return response
			return response()->json([
				'message' => 'Profile updated successfully',
				'data' => $response,
			], 200);
		}

		public function get_profile(Request $request)
		{
			// Get the authenticated user (assuming 'branch' is injected or available)
			$user = $request->branch;

			// Prepare response with full profile image URL
			$response = $user->toArray();
			$response['image'] = $user->image ? url($user->image) : null;

			// Return response
			return response()->json([
				'message' => 'Profile retrieved successfully',
				'data' => $response,
			], 200);
		}

}

