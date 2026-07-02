<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Notification;
use App\CentralLogics\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;

class SendScheduledNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled push notifications.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $notifications = Notification::whereNotNull('schedule_at')
        ->where(function ($query) use ($now) {
            $query->where('schedule_at', '<=', $now->format('Y-m-d H:i:s'));
        })
        ->where('status', 0)
        ->get();
    
        // print_r($notifications);
        // die;
        if (!$notifications->isEmpty()) {
            foreach ($notifications as $notification) {

                try {
                    Helpers::send_push_notif_to_topic($notification, 'notify', 'general');
                } catch (\Exception $e) {
                    Toastr::warning(translate('Push notification failed!'));
                }
        
                $notification->status = 1;
                $notification->save();
            }
        }
    }
}
