<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Order;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\Log;


class CheckAndReassignDrivers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkAndReassignDrivers';
    protected $description = 'Check and reassign drivers for confirmed orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $checking = Helpers::checkAndReassignDrivers();
        info("test cron");


    }
}
