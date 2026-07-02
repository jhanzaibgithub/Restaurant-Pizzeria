<?php

namespace App\Jobs;

use App\Model\Table as ModelTable;
use App\Model\TableReservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;


class UpdateTableAvailabilityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */

    public function handle()
    {
    
        $now = Carbon::now();
    $futureTime = $now->copy()->addMinutes(15);

    $reservations = TableReservation::where('date_time', '>', $now)
        ->where('date_time', '<=', $futureTime)
        ->get();

    // if ($reservations->isEmpty()) {
    //     Log::info('No reservations found for the current time window.');
    // } else {
    //     Log::info('Reservations to process: ' . $reservations->count());
    //     Log::info('Reservations: ' . json_encode($reservations));
    // }

    foreach ($reservations as $reservation) {
        $table = ModelTable::find($reservation->table_id);
        if ($table) {
            $table->is_available = 0;
            $table->save();
            // Log::info('Updated Table ID ' . $table->id . ' to is_available = 0');
        } else {
            // Log::error('Table not found for reservation ID: ' . $reservation->id);
        }
    }
    
    $expiredReservations = TableReservation::where('date_time', '<=', $now->subHour()) // Reservations older than 1 hour
    ->where('branch_table_token_is_expired', false) // Not yet expired
    ->get();

    foreach ($expiredReservations as $reservation) {
        $table = ModelTable::find($reservation->table_id);
        if ($table) {
            // Update the reservation
            $reservation->branch_table_token_is_expired = true;
            $reservation->save();

            // Update the table
            $table->is_available = 1; // Mark table as available
            $table->save();
        } else {
            Log::error('Table not found for expired reservation ID: ' . $reservation->id);
        }
       }

        Log::info('Job completed');
    }
    
}
