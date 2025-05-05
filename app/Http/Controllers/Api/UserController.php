<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\EmployeeTimeSheet;
use App\Services\PushNotificationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserController extends Controller
{
    private $pushNotificationService;
    public function __construct(PushNotificationService $pushNotificationService)
    {
        $this->pushNotificationService = $pushNotificationService;
    }

    public function user_data()
    {
        $user_id = auth()->id();
        $user_id = 34;
        $leave_time = EmployeeTimeSheet::where('user_id',$user_id)
                   ->whereMonth('date', Carbon::now()->month)
                   ->where('clock_out','!=',NULL)
                   ->get();
        //current month total 
        $total = $leave_time->count();
        // current month total ontime come
        $ontime = $leave_time->where('clock_in','<=','10:15:00')->Where('clock_out','>=','17:31:00')->count();
       // current month total late
        $late = $leave_time->where('clock_in','>=','10:15:00')->count();
        $total_time = EmployeeTimeSheet::where('user_id',$user_id)
                      ->whereMonth('date', Carbon::now()->month)
                      ->select(DB::raw("SUM(time_to_sec(timediff(clock_out, clock_in)) / 3600) as result"))
                      ->get(['result']);
        $expecting_working_hour = $total*8;
        $current_working = round($total_time[0]->result,2);
        if($expecting_working_hour <= $current_working)
        {
            $working = 100;
            $short = 0;
            if($expecting_working_hour>=1)
             {
                $overtime = round(((100*$current_working)/$expecting_working_hour)-100,0);
             }
             else 
             {
                $overtime = 0;
             }
            
        }
        else 
        {
            if($expecting_working_hour >= 1)
            {
                $working = round(((100*$current_working)/$expecting_working_hour),0);
                $short = 100-$working;
                $overtime = 0;
            }
            else 
            {
                $working = 0;
                $short = 100-$working;
                $overtime = 0;
            }
           
        }

        return [
            'status' => 'success',
            'total_attend' => $total,
            'total_ontime' => $ontime,
            'total_late' =>$late,
            'total_working_hour' =>$current_working,
            'working_hour_coverage_percentage' =>$working,
            'short_working_hour_percentage' =>$short,
            'overtime_percentage' =>$overtime,

        ];

       
    }
    public function user_phone_book()
    {
        // $users = Db::table('users')
        // ->join('designations','users.designation_id','=','designations.id')
        // ->select(['users.name', 'email', 'photo', 'phone', 'designations.name as desigation', 'blood_group'])
        // ->where('is_active', 1)
        // ->where('users.id','!=', 1)
        // ->get();

        $users = User::with('designation')
            ->where('is_active', 1)
            ->where('users.id','!=', 1)
            ->get();
        foreach($users as $key => $user)
        {
            $active_user[$key]['name'] = $user->name;
            $active_user[$key]['email'] = $user->email;
            $active_user[$key]['phone'] = $user->phone;
            $active_user[$key]['photo'] = $user->photo;
            $active_user[$key]['designations'] =  $user->designation->name;
            $active_user[$key]['blood_group'] = $user->blood_group_text;
        }
        return $active_user;
            // dd($active_user);

    }

    public function show(Request $request)
    {
        $user = $request->user();

        $leave_days = LeaveType::all()->sum('days');
        $user_leave = Leave::where('user_id', $user->id)->where('status','A')->sum('no_of_days');
        $remaining_leave = $leave_days - $user_leave;

        return [
            'status' => 'success',
            'remaining_leave' => $remaining_leave,
            'user' => new UserResource($user),
        ];
    }

    public function updateToken(Request $request, $user_id)
    {
        $request->validate([
            'device_token' => 'string|required'
        ]);

        try {
            $user = User::where('id', $user_id)->first();
            $user->device_token = $request->device_token;
            $user->save();

            return [
                'status' => 'success',
                'message' => 'Device token updated successfully',
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Something went wrong',
            ];
        }
    }

    public function notify()
    {
        $clocked_in_user_id_list = DB::table('employee_time_sheets')
            ->whereDate('date', date('Y-m-d'))
            ->pluck('user_id');

        $users = User::query()
            ->whereNotNull('device_token')
            ->whereNotIn('id', $clocked_in_user_id_list)
            ->pluck('device_token');

        $company_settings = DB::table('company_settings')->first();
        $clockin_time = $company_settings->start_time;

        // $clockin_time = date_format(date_create($clockin_time), 'h:i:s a');
        $clockin_time = \Carbon\Carbon::parse($clockin_time)->subtract('minutes', 5)->format('h:i:s');

        // return response()->json($clockin_time);


        $firebaseTokens = User::whereNotNull('device_token')->pluck('device_token')->all();

        // return $firebaseTokens;

        return $this->pushNotificationService->send($firebaseTokens, 'Clock IN Alert', 'Did you forget to Clock IN?');
    }
}
