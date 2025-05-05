<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\HolyDay;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CompanySettingController extends Controller
{
    public function __construct()
    {
        view()->share('main_menu', 'settings');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_unless(auth()->user()->can('company_setting'), Response::HTTP_UNAUTHORIZED);
        $company_info = CompanySetting::latest('id')->first();
        return view('settings.company_settings.index', [
            'company' => $company_info,
        ]);
    }
    public function store(Request $request)
    {

        try {
            DB::table('company_settings')->updateOrInsert(
                ['id' => $request->id],
                [
                    'company_name' => $request->company_name,
                    'company_address' => $request->company_address,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'last_login_time' => $request->last_login_time,
                    'emails' => $request->emails,
                ]
            );

            return back()->withSuccess('Company settings update successfully');
        } catch (\Exception $e) {
            return back()->withError('Company settings update failed');
        }
    }
}
