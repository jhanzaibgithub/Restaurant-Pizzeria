<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Model\Table;
use App\Model\TableReservation as ModelsTableReservation;
use App\Model\TableReservation;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TableReservationController extends Controller
{
   
/**
 * Reserve a table for a user.
 */
public function reserveTable(Request $request)
{
    try {
        // Validate the input
        $validatedData = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'user_id' => 'required|exists:users,id',
            'date_time' => 'required|date|after:now',
            'order_id' => 'nullable|exists:orders,id',
        ]);

          // Check if the table is available
          $table = Table::find($validatedData['table_id']); // Assuming Table is the model for the tables table
          if ($table->is_available == 0) {
              return response()->json([
                  'error' => 'This table is already reserved.',
              ], 422);
          }

        // Get the server's time zone
        $serverTimeZone = date_default_timezone_get();

        // Convert the input date_time to the server's time zone
        $requestDateTime = Carbon::parse($validatedData['date_time'])->setTimezone($serverTimeZone);

        // Generate a 4-digit random token
        $branchTableToken = Str::random(4);

        // Set the expiration time (1 hour after the given date_time in server's time zone)
        $expirationTime = $requestDateTime->copy()->addHour();

        // Create or update the reservation
        $reservation = TableReservation::updateOrCreate(
            [
                'table_id' => $validatedData['table_id'], 
                'user_id' => $validatedData['user_id']
            ],
            [
                'branch_table_token' => $branchTableToken,
                'branch_table_token_is_expired' => false,
                'order_id' => $validatedData['order_id'] ?? null,
                'date_time' => $requestDateTime, // Save in server's time zone
                'status' => 'pending',
            ]
        );

        // Update the expiration logic after reservation creation
        $reservation->branch_table_token_is_expired = Carbon::now()->gt($expirationTime);
        $reservation->save();

        return response()->json([
            'message' => 'Table reserved successfully.',
            'reservation' => $reservation,
            'branch_table_token' => $branchTableToken,
            'expires_at' => $expirationTime,
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Handle validation errors
        return response()->json([
            'error' => 'Validation failed',
            'messages' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        // Handle general errors
        return response()->json([
            'error' => 'An unexpected error occurred',
            'message' => $e->getMessage(),
        ], 500);
    }
}


}
