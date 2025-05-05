<?php
namespace App\Services;

use App\Models\ClientClinicianAssign;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    private $pushNotificationService;
    public function __construct(PushNotificationService $pushNotificationService)
    {
        $this->pushNotificationService = $pushNotificationService;
    }

    public function getAssignedClients(User $user)
    {
        return;
    }

    /* send notification to user before 5 min of clock in time */
    public function sendClockInAlert()
    {
        $clocked_in_user_id_list = DB::table('employee_time_sheets')
            ->whereDate('date', date('Y-m-d'))
            ->pluck('user_id');

        $usersDeviceTokens = User::query()
            ->whereNotNull('device_token')
            ->whereNotIn('id', $clocked_in_user_id_list)
            ->pluck('device_token');

        return $this->pushNotificationService->send($usersDeviceTokens, 'Clock IN Time', 'Hey, Clock IN before its too late.');
    }

    /* Send notification to users who didnt clock in yet */
    public function sendClockInLateAlert()
    {
        //
    }

    // Send clock out notification to users at 6 pm
    public function sendClockOutNotification()
    {
        //
    }

    // Send notification to users who forgot to clock out
    public function sendClockOutForgotAlert()
    {
        //
    }
}
