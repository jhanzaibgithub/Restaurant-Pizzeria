<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Group;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
	
	public function add(Request $request)
	{
		// Validate the request data
		$validatedData = $request->validate([
          'title' => 'required|string|max:255|unique:groups,title',
		]);

		// Create a new Group record
		$group = Group::create([
			'title' => $validatedData['title'],
		]);

		// Return a response, e.g., redirect or JSON
		return response()->json([
			'message' => 'Group added successfully!',
			'group' => $group,
		]);
	}
	
	public function update(Request $request)
	{
		// Validate that ID exists
		$request->validate([
			'id' => 'required|exists:groups,id'
		], [
			'id.exists' => 'Group not found'
		]);

		// Find the group by ID
		$group = Group::findOrFail($request->id);

		// Update title if provided
		if ($request->has('title')) {
			$validatedTitle = $request->validate([
				'title' => [
					'required',
					'string', 
					'max:255',
					Rule::unique('groups', 'title')->ignore($group->id),
				]
			]);
			$group->title = $validatedTitle['title'];
		}

		// Update is_available if provided
		if ($request->has('is_available')) {
			$validatedAvailable = $request->validate([
				'is_available' => 'boolean'
			]);
			$group->is_available = $validatedAvailable['is_available'];
		}

		// Update status if provided 
		if ($request->has('status')) {
			$validatedStatus = $request->validate([
				'status' => 'boolean'
			]);
			$group->status = $validatedStatus['status'];
		}

		// Save all updates
		$group->save();

		// Return response
		return response()->json([
			'message' => 'Group updated successfully!',
			'group' => $group,
		]);
	}
	
	public function delete(Request $request)
	{
		// Find the group by ID
		$group = Group::findOrFail($request->id);

		// Delete the group
		$group->delete();

		// Return a response
		return response()->json([
			'message' => 'Group deleted successfully!',
		]);
	}
	
}