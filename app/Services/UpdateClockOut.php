<?php

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateClockOut
{

    public function clock_out_update()
    {

        $today_date = Date('Y-m-d');

        $company_time = CompanySetting::where('id', 1)
            ->select('start_time', 'end_time')
            ->first();

        $end_time = $company_time->end_time;
        $remove_fifteen_min = Carbon::parse($end_time)->subMinutes(0)->format('H:i:s');

        $time1 = strtotime($company_time->start_time);
        $time2 = strtotime($company_time->end_time);
        $for_evening_clock_out_time_hour = ((round(abs($time2 - $time1) / 3600, 2)) / 2);

        $add_hour_for_evening = Carbon::parse($company_time->start_time)->addHours($for_evening_clock_out_time_hour)->format('H:i:s');

        $remove_fifteen_min_for_evening_clock_out = Carbon::parse($add_hour_for_evening)->subMinutes(0)->format('H:i:s');

        $all_active_users = User::where('is_active', 1)->get();

        foreach ($all_active_users as $user) {

            $leaves = DB::table('leaves')
                ->where('from_date', $today_date)
                ->where('to_date', $today_date)
                ->where('user_id', $user->id)
                ->where('no_of_days', 0.5)
                ->where('status', 'A')
                ->first();

            if ($leaves) {
                if ($leaves->shift == 'E') {
                    DB::table('employee_time_sheets')
                        ->where('user_id', $user->id)
                        ->where('date', $today_date)
                        ->whereNotNull('clock_in')
                        ->whereNull('clock_out')
                        ->update([
                            'clock_out' => $remove_fifteen_min_for_evening_clock_out,
                            'is_cron_job' => 1
                        ]);
                } else {
                    DB::table('employee_time_sheets')
                        ->where('user_id', $user->id)
                        ->where('date', $today_date)
                        ->whereNotNull('clock_in')
                        ->whereNull('clock_out')
                        ->update([
                            'clock_out' => $remove_fifteen_min,
                            'is_cron_job' => 1
                        ]);
                }
            } else {
                DB::table('employee_time_sheets')
                    ->where('user_id', $user->id)
                    ->where('date', $today_date)
                    ->whereNotNull('clock_in')
                    ->whereNull('clock_out')
                    ->update([
                        'clock_out' => $remove_fifteen_min,
                        'is_cron_job' => 1
                    ]);
            }
        }

        // $x = DB::table('employee_time_sheets')->limit(15)->get();

    }

}
