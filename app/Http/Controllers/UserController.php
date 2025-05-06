<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPostRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Designation;
use App\Services\FileUploadService;
use App\Models\District;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use App\Models\EmployeeDepartment;
use App\Models\Division;
use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
        view()->share('main_menu', 'employee');
    }

    public function index()
    {
        view()->share('sub_menu', 'manage employee');
        return view('users.index');
    }


    protected function getFormData()
    {
        return [
            'user_types' => Role::whereNotNull('slug')->active()->get(),
            'designations' => Designation::all(),
            'employee_departments' => EmployeeDepartment::all(),
            'divisions' => Division::all(),
        ];
    }

    public function create()
    {
        abort_unless(auth()->user()->can('user-create'), Response::HTTP_UNAUTHORIZED);

        view()->share('sub_menu', 'add new employee');

        return view('users.create', $this->getFormData());
    }

    public function store(UserPostRequest $request)
    {
        $data = $request->validated();
        $authUser = auth()->user();

        // 🔍 Get the authenticated user's primary role's slug
        $authPrimaryRole = optional(
            $authUser->roles()->wherePivot('is_primary', 1)->first()
        )->slug;

        // 🔐 Check if the user is a superuser (god mode) or has elevated role
        $isSuperUser = $authUser->is_superuser == 1;
        $hasFullAccess = $isSuperUser || in_array($authPrimaryRole, ['admin', 'central']);

        // Get the slug of the role being assigned to the new user
        $newUserRoleSlug = optional(Role::find($request->user_type_id))->slug;

        // 🚦 Role hierarchy: low to high
        $roleOrder = ['union', 'upazila', 'district', 'division', 'central', 'admin'];

        $authRoleLevel = array_search($authPrimaryRole, $roleOrder);
        $newRoleLevel = array_search($newUserRoleSlug, $roleOrder);

        // ❌ Bail on unknown roles unless user is a superuser
        if (!$isSuperUser && ($authRoleLevel === false || $newRoleLevel === false)) {
            return back()->withError('Invalid role assignment.')->withInput();
        }

        // ❌ Prevent assigning a higher-level role (unless full access)
        if (!$hasFullAccess && $newRoleLevel > $authRoleLevel) {
            return back()->withError('You cannot assign a higher-level role.')->withInput();
        }

        // 🔒 Enforce location restriction unless full access
        if (!$hasFullAccess) {
            if ($authPrimaryRole === 'division' && $request->division_id != $authUser->division_id) {
                return back()->withError('Invalid division assignment.')->withInput();
            }

            if ($authPrimaryRole === 'district' && (
                $request->division_id != $authUser->division_id ||
                $request->district_id != $authUser->district_id
            )) {
                return back()->withError('Invalid district assignment.')->withInput();
            }

            if ($authPrimaryRole === 'upazila' && (
                $request->division_id != $authUser->division_id ||
                $request->district_id != $authUser->district_id ||
                $request->upazila_id != $authUser->upazila_id
            )) {
                return back()->withError('Invalid upazila assignment.')->withInput();
            }

            if ($authPrimaryRole === 'union' && (
                $request->division_id != $authUser->division_id ||
                $request->district_id != $authUser->district_id ||
                $request->upazila_id != $authUser->upazila_id ||
                $request->union_id != $authUser->union_id
            )) {
                return back()->withError('Invalid union assignment.')->withInput();
            }
        }

        // ✅ Proceed with user creation
        $data['password'] = Hash::make(env('USER_DEFAULT_PASSWORD'));

        try {
            $filePath = null;
            if (request()->has('photo')) {
                $filePath = $this->fileUploadService->uploadImage('photo', 'users');
            }

            $data['dob'] = $data['dob'] ? Carbon::parse($data['dob'])->format('Y-m-d') : null;
            $data['joining_date'] = $data['joining_date'] ? Carbon::parse($data['joining_date'])->format('Y-m-d') : null;
            $data['photo'] = $filePath;

            DB::beginTransaction();

            $data['is_admin'] = 1;
            $data['status'] = 'approved';



            // 👤 Create the user
            $user = User::create($data);

            // 🔖 Assign role
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $request->user_type_id,
                'is_primary' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('users.index')->withSuccess('Employee created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError($e->getMessage())->withInput($request->all());
        }
    }



    public function edit(User $user)
    {
        abort_unless(auth()->user()->can('user-update'), Response::HTTP_UNAUTHORIZED);
        $auth = auth()->user();
        $role = Session::get('role');

        $can_see_party_info = $auth->is_superuser == 1 || (isset($role) && in_array($role->slug, ['division', 'district' , 'upazila', 'union', 'central', 'admin']));

        view()->share('sub_menu', 'manage employee');

        return view('users.update', array_merge($this->getFormData(), [
            'user' => $user,
            'can_see_party_info' => $can_see_party_info,
            'districts' => District::where('division_id', $user->division_id)->get(),
            'upazilas' => Upazila::where('district_id', $user->district_id)->get(),
            'unions' => Union::where('upazilla_id', $user->upazila_id)->get(),
        ]));
    }


    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        try {
            if (request()->has('photo')) {
                $filePath = $this->fileUploadService->uploadImage('photo', 'users');
                $data['photo'] = $filePath;
            }

            $data['dob'] = $data['dob'] ? Carbon::parse($data['dob'])->format('Y-m-d') : null;

            if (isset($data['joining_date'])) {
                $data['joining_date'] = $data['joining_date']
                    ? Carbon::parse($data['joining_date'])->format('Y-m-d')
                    : null;
            }

            unset($data['user_type_id']);

            User::where('id', $user->id)->update($data);

            DB::table('role_user')->where('user_id', $user->id)->delete();

            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $request->user_type_id,
                'is_primary' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->route('users.index')->withSuccess('Employee updated successfully');
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput($request->all());
        }
    }

    public function show(User $user)
    {
        if (auth()->user()->can('user-view')) {
            $user->load(['district', 'roles', 'designation']);
            return view('users.show', compact('user'));
        } elseif (auth()->user()->can('profile-view')) {
            $user->load(['district', 'roles', 'designation']);
            return view('users.profile', compact('user'));
        } else {
            abort(Response::HTTP_UNAUTHORIZED);
        }
    }

    public function getUsers()
    {
        // Permissions
        $canEdit = auth()->user()->can('user-update');
        $canView = auth()->user()->can('user-view');
        $canActivation = auth()->user()->can('user-activation');
        $canPasswordReset = auth()->user()->can('user-password-reset');

        $status = request()->has('is_active') ? request()->is_active : 1;

        $authUser = auth()->user();
        $authPrimaryRole = optional(
            $authUser->roles()->wherePivot('is_primary', 1)->first()
        )->slug;

        $isSuperuser = $authUser->is_superuser;

        // Roles in hierarchical order
        $hierarchy = ['union', 'upazila', 'district', 'division'];
        $currentLevelIndex = array_search($authPrimaryRole, $hierarchy);
        $allowedRoles = array_slice($hierarchy, 0, $currentLevelIndex + 1);

        // Base query
        $users = User::query()
            ->select(['id', 'name', 'userid', 'photo', 'last_login', 'is_active'])
            ->when(request()->has('is_active'), fn($q) => $q->where('is_active', request()->is_active))
            ->when(
                in_array($authPrimaryRole, $hierarchy),
                function ($q) use ($authUser, $authPrimaryRole) {
                    return $q->where($authPrimaryRole . '_id', $authUser->{$authPrimaryRole . '_id'});
                }
            )
            ->when(
                in_array($authPrimaryRole, $hierarchy),
                function ($q) use ($allowedRoles) {
                    $q->whereHas('roles', function ($roleQuery) use ($allowedRoles) {
                        $roleQuery->whereIn('roles.slug', $allowedRoles)
                                ->where('role_user.is_primary', 1);
                    });
                }
            );

        // 🧨 Override filtering for Superuser, Admin, Central
        if ($isSuperuser || in_array($authPrimaryRole, ['admin', 'central'])) {
            $users = User::query()
                ->select(['id', 'name', 'userid', 'photo', 'last_login', 'is_active'])
                ->when(request()->has('is_active'), fn($q) => $q->where('is_active', request()->is_active))
                ->with(['roles' => fn($q) => $q->wherePivot('is_primary', 1)]);
        } else {
            $users = $users->with(['roles' => fn($q) => $q->wherePivot('is_primary', 1)]);
        }

        $users->get();
        // $users = User::all();


        $result = DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('photo', function ($row) {
                if ($row->photo) {
                    return "<img src='{$row->photo_path}' class='img-avatar img-avatar48' alt='Client Photo' />";
                }
                $firstChar = mb_substr($row->name, 0, 1, "UTF-8");
                return "<p class='profile-icon bg-info'>{$firstChar}</p>";
            })
            ->editColumn('name', function ($row) {
                return '<p class="font-w600 mb-0">
                            <a href="' . route('users.show', ['user' => $row->id]) . '">' . mb_substr($row->name, 0) . '</a>
                        </p>
                        <small class="text-muted mb-0">#' . $row->userid . '</small>';
            })
            ->addColumn('user_type', function ($row) {
                return $row->roles->count() ? $row->roles[0]->title : '-';
            })
            ->editColumn('last_login', function ($row) {
                return $row->last_login ? $row->last_login->format('d M Y h:i a') : '-';
            })
            ->addColumn('status', function ($row) {
                return $row->status;
            })
            ->addColumn('action', function ($row) use ($canView, $canEdit, $canActivation, $canPasswordReset) {
                $btn = '';
                if ($canView) {
                    $btn .= '<a href="' . route('users.show', ['user' => $row->id]) . '"
        style="display: inline-flex; align-items: center; justify-content: center; min-width: 2rem; height: 2rem; padding: 0 0.5rem; margin-right: 0.5rem; border-radius: 0.375rem; background-color: #3498db; color: white; text-decoration: none; font-size: 0.875rem; transition: all 0.2s ease; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
        onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 6px rgba(0,0,0,0.1)\'; this.style.opacity=\'0.9\';"
        onmouseout="this.style.transform=\'\'; this.style.boxShadow=\'0 1px 3px rgba(0,0,0,0.1)\'; this.style.opacity=\'1\';"
        title="View"><i class="fa fa-fw fa-eye"></i></a>';
                }

                if ($canEdit) {
                    $btn .= '<a href="' . route('users.edit', ['user' => $row->id]) . '"
        style="display: inline-flex; align-items: center; justify-content: center; min-width: 2rem; height: 2rem; padding: 0 0.5rem; margin-right: 0.5rem; border-radius: 0.375rem; background-color: #2ecc71; color: white; text-decoration: none; font-size: 0.875rem; transition: all 0.2s ease; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
        onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 6px rgba(0,0,0,0.1)\'; this.style.opacity=\'0.9\';"
        onmouseout="this.style.transform=\'\'; this.style.boxShadow=\'0 1px 3px rgba(0,0,0,0.1)\'; this.style.opacity=\'1\';"
        title="Edit"><i class="fa fa-fw fa-pen"></i></a>';
                }

                if ($canActivation) {
                    if ($row->is_active) {
                        $btn .= '<a href="' . route('users.deactivate', ['user' => $row->id]) . '"
            onClick="return confirmDeactivate()"
            style="display: inline-flex; align-items: center; justify-content: center; min-width: 2rem; height: 2rem; padding: 0 0.5rem; margin-right: 0.5rem; border-radius: 0.375rem; background-color: #e74c3c; color: white; text-decoration: none; font-size: 0.875rem; transition: all 0.2s ease; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
            onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 6px rgba(0,0,0,0.1)\'; this.style.opacity=\'0.9\';"
            onmouseout="this.style.transform=\'\'; this.style.boxShadow=\'0 1px 3px rgba(0,0,0,0.1)\'; this.style.opacity=\'1\';"
            title="Deactivate"><i class="fa fa-fw fa-user-lock"></i></a>';
                    } else {
                        $btn .= '<a href="' . route('users.activate', ['user' => $row->id]) . '"
            onClick="return confirmActivate()"
            style="display: inline-flex; align-items: center; justify-content: center; min-width: 2rem; height: 2rem; padding: 0 0.5rem; margin-right: 0.5rem; border-radius: 0.375rem; background-color: #2ecc71; color: white; text-decoration: none; font-size: 0.875rem; transition: all 0.2s ease; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
            onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 6px rgba(0,0,0,0.1)\'; this.style.opacity=\'0.9\';"
            onmouseout="this.style.transform=\'\'; this.style.boxShadow=\'0 1px 3px rgba(0,0,0,0.1)\'; this.style.opacity=\'1\';"
            title="Activate"><i class="fa fa-fw fa-user-check"></i></a>';
                    }
                }

                if ($canPasswordReset) {
                    $btn .= '<a href="' . route('users.password-reset', ['user' => $row->id]) . '"
        onClick="return confirmPasswordReset()"
        style="display: inline-flex; align-items: center; justify-content: center; min-width: 2rem; height: 2rem; padding: 0 0.5rem; border-radius: 0.375rem; background-color: #f39c12; color: white; text-decoration: none; font-size: 0.875rem; transition: all 0.2s ease; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
        onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 6px rgba(0,0,0,0.1)\'; this.style.opacity=\'0.9\';"
        onmouseout="this.style.transform=\'\'; this.style.boxShadow=\'0 1px 3px rgba(0,0,0,0.1)\'; this.style.opacity=\'1\';"
        title="Password Reset"><i class="fa fa-fw fa-fingerprint"></i></a>';
                }

                return '<div style="display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: flex-end;">' . $btn . '</div>';
            })
            ->rawColumns(['action', 'name', 'photo', 'status'])
            ->make(true);
        return $result;
    }

    public function passwordReset(User $user)
    {
        abort_unless(auth()->user()->can('user-password-reset'), Response::HTTP_UNAUTHORIZED);
        try {
            $user->password = Hash::make(env('USER_DEFAULT_PASSWORD'));
            $user->save();
            return back()->withSuccess('Employee password reset success');
        } catch (Exception $e) {
            return back()->withError('Employee password reset failed');
        }
    }

    
    public function activate(User $user)
    {
        abort_unless(auth()->user()->can('user-activation'), Response::HTTP_UNAUTHORIZED);
        try {
            $user->is_active = 1;
            $user->save();
            return back()->withSuccess('Employee activated successfully');
        } catch (Exception $e) {
            return back()->withError('Employee activation failed');
        }
    }

    public function deactivate(User $user)
    {
        abort_unless(auth()->user()->can('user-activation'), Response::HTTP_UNAUTHORIZED);
        try {
            $user->is_active = 0;
            $user->save();
            return back()->withSuccess('Employee deactivated successfully');
        } catch (Exception $e) {
            return back()->withError('Employee deactivation failed');
        }
    }

    public function setUserType($user_type)
    {
        session(['role_id' => $user_type]);
        session(['role' => Role::find($user_type)]);

        $user_id = auth()->id();
        Cache::forget("menus_{$user_id}");
        Cache::forget("user_permissions_{$user_id}");

        return redirect()->route('admin.dashboard.index')->withSuccess('Your current user type updated');
    }

    public function user_excel_download()
    {
        abort_unless(auth()->user()->can('user-activation'), Response::HTTP_UNAUTHORIZED);

        $users = User::query()
            ->where('is_active', 1)
            ->staff()->get();

        if (count($users) > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $prepared_data = array();

            foreach ($users as $key => $row) {

                $index = $key + 1;

                $temp = array();
                array_push($temp, $index);
                array_push($temp, $row->name);
                array_push($temp, $row->userid);
                array_push($temp, $row->email);
                array_push($temp, $row->gender_text);
                array_push($temp, date('d-m-Y', strtotime($row->dob)));
                array_push($temp, $row->blood_group_text);
                array_push($temp, $row->address);
                array_push($temp, $row->phone);
                array_push($temp, $row->em_contact_name);
                array_push($temp, $row->em_contact_relation);
                array_push($temp, $row->em_contact_phone);
                array_push($temp, date('d-m-Y', strtotime($row->joining_date)));
                array_push($prepared_data, $temp);
            }

            $generalHeader = array('SL', 'NAME', 'USER ID', 'EMAIL', 'GENDER', 'DATE OF BIRTH', 'BLOOD GROUP', 'ADDRESS', 'PHONE', 'EMERGENCY CONTACT NAME', 'EMERGENCY CONTACT RELATION', 'EMERGENCY CONTACT PHONE', 'JOINING DATE');
            $sheet->fromArray(array_values($generalHeader), NULL, 'A2')->getStyle('A2:M2')->getFont()->setBold(true);
            $sheet->fromArray($prepared_data, NULL, 'A3');

            $fileName = "User Data Report";
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $fileName . '_' . date('ymdhis') . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            die();

        } else {
            return back()->withError('No employee found');
        }
    }
    
    /**
 * Display the member request management page
 *
 * @return \Illuminate\Http\Response
 */
public function memberRequests()
{
    return view('users.member-requests');
}

/**
 * Get member requests for DataTables
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function getMemberRequests()
{
    // Permissions
    $canApprove = auth()->user()->can('user-update');
    $canReject = auth()->user()->can('user-password-reset');
    $canView = auth()->user()->can('user-view');

    $status = request()->has('status') ? request()->status : 'pending';

    $authUser = auth()->user();
    $userRole = optional($authUser->roles()->wherePivot('is_primary', 1)->first())->slug;
    
    $requests = User::query()
        ->select(['id', 'name', 'userid', 'phone', 'photo', 'created_at', 'status', 'division_id', 'district_id', 'upazila_id', 'union_id'])
        ->with([
            'division:id,bn_name',
            'district:id,bn_name',
            'upazila:id,bn_name',
            'union:id,bn_name',
        ])
        ->when($status !== 'all', function ($q) use ($status) {
            return $q->where('status', $status);
        })
        // Checking the role and filtering based on it
        ->when($userRole === 'division', function ($q) use ($authUser) {
            return $q->where('division_id', $authUser->division_id);
        })
        ->when($userRole === 'district', function ($q) use ($authUser) {
            return $q->where('district_id', $authUser->district_id);
        })
        ->when($userRole === 'upazila', function ($q) use ($authUser) {
            return $q->where('upazila_id', $authUser->upazila_id);
        })
        ->when($userRole === 'union', function ($q) use ($authUser) {
            return $q->where('union_id', $authUser->union_id);
        });
    
    $result = DataTables::of($requests)
        ->addIndexColumn()
        ->editColumn('photo', function ($row) {
            if ($row->photo) {
                return "<img src='{$row->photo_path}' class='img-avatar img-avatar48' alt='User Photo' />";
            }
            $firstChar = mb_substr($row->name, 0, 1, "UTF-8");
            return "<p class='profile-icon bg-info'>{$firstChar}</p>";
        })
        ->editColumn('name', function ($row) {
            return '<p class="font-w600 mb-0">
                    <a href="' . route('users.showMemberRequest', $row->id) . '">' . mb_substr($row->name, 0) . '</a>
                </p>
                <small class="text-muted mb-0">#' . $row->userid . '</small>';
        })
        ->addColumn('location', function ($row) {
            $location = [];
            if ($row->division) $location[] = $row->division->bn_name;
            if ($row->district) $location[] = $row->district->bn_name;
            if ($row->upazila) $location[] = $row->upazila->bn_name;
            if ($row->union) $location[] = $row->union->bn_name;
            
            return implode(', ', $location);
        })
        ->editColumn('created_at', function ($row) {
            return $row->created_at ? $row->created_at->format('d M Y') : '-';
        })
        ->editColumn('status', function ($row) {
            $statusClass = [
                'pending' => 'warning',
                'approved' => 'success',
                'rejected' => 'danger'
            ];
            
            $statusText = [
                'pending' => 'বিচারাধীন',
                'approved' => 'অনুমোদিত',
                'rejected' => 'প্রত্যাখ্যাত'
            ];
            
            $class = $statusClass[$row->membership_status] ?? 'secondary';
            $text = $statusText[$row->membership_status] ?? 'অজানা';
            
            return "<span class='badge badge-{$class}'>{$text}</span>";
        })
        ->addColumn('action', function ($row)  {
            $btn = '';
                $btn .= '<a href="' . route('users.show', $row->id) . '"
    style="display: inline-flex; align-items: center; justify-content: center; min-width: 2rem; height: 2rem; padding: 0 0.5rem; margin-right: 0.5rem; border-radius: 0.375rem; background-color: #3498db; color: white; text-decoration: none; font-size: 0.875rem; transition: all 0.2s ease; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
    onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 6px rgba(0,0,0,0.1)\'; this.style.opacity=\'0.9\';"
    onmouseout="this.style.transform=\'\'; this.style.boxShadow=\'0 1px 3px rgba(0,0,0,0.1)\'; this.style.opacity=\'1\';"
    title="বিস্তারিত দেখুন"><i class="fa fa-fw fa-eye"></i></a>';

            if ( $row->membership_status === 'pending') {
                $btn .= '<a href="' . route('users.approveMemberRequest', $row->id) . '"
    onClick="return confirmApprove()"
    style="display: inline-flex; align-items: center; justify-content: center; min-width: 2rem; height: 2rem; padding: 0 0.5rem; margin-right: 0.5rem; border-radius: 0.375rem; background-color: #2ecc71; color: white; text-decoration: none; font-size: 0.875rem; transition: all 0.2s ease; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
    onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 6px rgba(0,0,0,0.1)\'; this.style.opacity=\'0.9\';"
    onmouseout="this.style.transform=\'\'; this.style.boxShadow=\'0 1px 3px rgba(0,0,0,0.1)\'; this.style.opacity=\'1\';"
    title="অনুমোদন করুন"><i class="fa fa-fw fa-check"></i></a>';
            }

            if ( $row->membership_status === 'pending') {
                $btn .= '<button type="button"
    class="reject-btn"
    data-id="' . $row->id . '"
    style="display: inline-flex; align-items: center; justify-content: center; min-width: 2rem; height: 2rem; padding: 0 0.5rem; border-radius: 0.375rem; background-color: #e74c3c; color: white; text-decoration: none; font-size: 0.875rem; transition: all 0.2s ease; border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
    onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 6px rgba(0,0,0,0.1)\'; this.style.opacity=\'0.9\';"
    onmouseout="this.style.transform=\'\'; this.style.boxShadow=\'0 1px 3px rgba(0,0,0,0.1)\'; this.style.opacity=\'1\';"
    title="প্রত্যাখ্যান করুন"><i class="fa fa-fw fa-times"></i></button>';
            }

            return '<div style="display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: flex-end;">' . $btn . '</div>';
        })
        ->rawColumns(['action', 'name', 'photo', 'status'])
        ->make(true);
    
    return $result;
}

/**
 * Display the member request details
 *
 * @param int $id
 * @return \Illuminate\Http\Response
 */
public function showMemberRequest($id)
{
    $user = User::with(['division', 'district', 'upazila', 'union'])
        ->findOrFail($id);
    
    return view('users.show', compact('user'));
}

/**
 * Approve a member request
 *
 * @param int $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function approveMemberRequest($id)
{
    $user = User::findOrFail($id);
    
    if ($user->membership_status !== 'pending') {
        return redirect()->back()->with('error', 'শুধুমাত্র বিচারাধীন আবেদন অনুমোদন করা যাবে!');
    }
    
    $user->status = 'approved';
    $user->membership_id = 'MEM' . date('Y') . str_pad($user->id, 6, '0', STR_PAD_LEFT);
    $user->save();
    
    // You could add notification logic here
    
    return redirect()->back()->with('success', 'সদস্যতা আবেদন সফলভাবে অনুমোদিত হয়েছে!');
}

/**
 * Reject a member request
 *
 * @param \Illuminate\Http\Request $request
 * @param int $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function rejectMemberRequest(Request $request, $id)
{
    $request->validate([
        'rejection_reason' => 'required|string|max:500'
    ]);
    
    $user = User::findOrFail($id);
    
    if ($user->membership_status !== 'pending') {
        return redirect()->back()->with('error', 'শুধুমাত্র বিচারাধীন আবেদন প্রত্যাখ্যান করা যাবে!');
    }
    
    $user->status = 'rejected';
    $user->rejection_reason = $request->rejection_reason;
    $user->save();
    
    // You could add notification logic here
    
    return redirect()->back()->with('success', 'সদস্যতা আবেদন প্রত্যাখ্যান করা হয়েছে।');
}

}
