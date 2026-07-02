<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Printer;
use Storage;

class PrinterController extends Controller
{
	public function getPrinters(Request $request) {

		$branch = $request->branch;

		$printers = Printer::with('kitchen')->where('branch_id', $branch->id)->get();

		return response()->json(['printers' => $printers]);
	}

	  public function add(Request $request)
	{
		// Validate the incoming request data
		$validatedData = $request->validate([
			'title' => 'required|string|max:255',
			'ip' => 'required|ip', // Ensures a valid IP format
			'kitchen_id' => 'required|integer',
		]);

		// Get the branch information from the request
		$branch = $request->branch;

		// Check if the IP already exists for the same kitchen_id
		$existingPrinter = Printer::where('ip', $validatedData['ip'])
			->where('kitchen_id', $request->kitchen_id)
			->first();

		if ($existingPrinter) {
			return response()->json(['error' => 'The IP address is already assigned to this kitchen.'], 422);
		}

		// Create the new printer record
		$printer = Printer::create([
			'kitchen_id' => $request->kitchen_id,
			'title' => $validatedData['title'],
			'ip' => $validatedData['ip'],
			'branch_id' => $branch->id,
			'is_primary' => filter_var($request->is_primary, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
		]);

		// Return a success response
		return response()->json(['message' => 'Printer added successfully.'], 200);
	}

	public function update(Request $request)
{

    $kitchen = Printer::findOrFail($request->id);

    $kitchen->update([
        'title' => $request->title ?? $kitchen->title,
        'ip' => $request->ip ?? $kitchen->ip,
        'kitchen_id' => $request->kitchen_id ?? $kitchen->kitchen_id,
        'is_primary' => $request->has('is_primary') ? filter_var($request->is_primary, FILTER_VALIDATE_BOOLEAN) : $kitchen->is_primary,
        'status' => $request->has('status') ? filter_var($request->status, FILTER_VALIDATE_BOOLEAN) : $kitchen->status
    ]);

    // Return a success message along with the updated record
    return response()->json([
        'message' => 'Printer updated successfully.',
        'printer' => $kitchen // Include the updated printer record
    ], 200);
}



	public function delete(Request $request){

		$printer = Printer::findorFail($request->id);

		$printer->delete();

		return response()->json(['message' => 'printer delete succussully'],200);
	}



}
