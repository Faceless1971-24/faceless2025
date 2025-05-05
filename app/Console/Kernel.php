<?php

namespace App\Console;

use App\Models\CompanySetting;
use App\Models\EmployeeTimeSheet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $company_settings = Cache::remember('company_settings', 300, function () {
            return DB::table('company_settings')->first();
        });

        $clockin_time = $company_settings->start_time;
        $clockin_time = Carbon::parse("2021-01-01 " . $clockin_time)->subMinutes(15)->format('h:i:s');

        // send clock in reminder notification to staff
        $schedule->call('\App\Services\UserService@sendClockInAlert')
            ->dailyAt($clockin_time)
            ->description('clock in reminder for staff');



        /* if (env('TELESCOPE_ENABLED', false)) {
         $schedule->command('telescope:prune --hours=72')->daily();
         } */


        //daily automatic clock out , if anyone forgot to do clock out
        $clockout_time = '23:00:00';
        $schedule->call('\App\Services\UpdateClockOut@clock_out_update')
            ->dailyAt($clockout_time);


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
