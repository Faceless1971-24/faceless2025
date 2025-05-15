<?php

namespace App\Models;

use App\Http\Controllers\EmployeeOnLeaveTodayController;
use App\Permissions\PermissionTrait;
use App\Traits\CommonAttributes;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, CommonAttributes, PermissionTrait, HasApiTokens;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'userid',
        'email',
        'gender',
        'dob',
        'password',
        'blood_group',
        'address',
        'permanent_address', // ✅ Add this
        'post_code',
        'phone',
        'photo',
        'nid_scan', // ✅ Add this
        'nid', // ✅ Add this
        'father_name', // ✅ Add this
        'mother_name', // ✅ Add this
        'union_id',
        'upazila_id',
        'district_id',
        'division_id',
        'em_contact_name',
        'em_contact_relation',
        'em_contact_phone',
        'em_contact_email',
        'joining_date',
        'duration',
        'ending_date',
        'last_educational_qual',
        'educational_qualification', // ✅ Add this
        'profession', // ✅ Add this
        'supervisor_id',
        'employment_type',
        'is_active',
        'designation_id',
        'status',
        'is_superuser',
        'is_admin',
    ];



    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'dob' => 'date',
        'joining_date' => 'date',
        'ending_date' => 'date',
    ];

    public function hasPermission($permission)
    {
        // Admin users have all permissions
        if ($this->isAdmin()) {
            return true;
        }

        // Regular permission check for non-admin users
        return $this->permissions()->where('name', $permission)->exists();
    }

    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, Role::class, 'id', 'id', null, null)
            ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
            ->join('role_user', 'roles.id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $this->id)
            ->select('permissions.*')
            ->distinct();
    }



    public function isAdmin()
    {
        return $this->is_superuser == 1 || $this->is_admin == 1;
    }

    public function isFrontendUser()
    {
        return $this->status == 1; // Assuming status 1 means active
    }

    public function scopeStaff($query)
    {
        return $query->where('is_superuser', 0);
    }

    public function getUserPhotoAttribute()
    {
        return $this->photo && file_exists("storage/{$this->photo}")
            ? asset("storage/{$this->photo}")
            : '';
    }


    public function getMembershipStatusAttribute()
    {
        return strtolower(trim($this->attributes['status'] ?? ''));
    }


    public function getGenderTextAttribute()
    {
        return $this->gender == 1 ? 'Male' : ($this->gender == 2 ? 'Female' : 'Other');
    }

    public function getTypeTextAttribute()
    {
        return $this->employment_type == 1 ? 'Permanent' : ($this->employment_type == 2 ? 'Intern' : ($this->employment_type == 3 ? 'Probational' : ($this->employment_type == 4 ? 'Part-Time' : 'Contractual')));
    }

    public function getAgeAttribute()
    {
        return $this->dob ? Carbon::parse($this->dob)->diffInYears(Carbon::now()) : '0';
    }

    public function getJobTimeAttribute()
    {
        return $this->joining_date ? Carbon::parse($this->joining_date)->diffInYears(Carbon::now()) : '0';
    }


    public function getPhotoPathAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return null;
    }


    public function getNameWithCodeAttribute()
    {
        return "{$this->name} ($this->userid)";
    }

    public function getBloodGroupTextAttribute()
    {
        return $this->blood_group
            ? Config::get('constants.blood_groups')[$this->blood_group]
            : 'N/A';
    }


    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }


    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function union()
    {
        return $this->belongsTo(Union::class);
    }


    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
            ->withPivot('is_primary');
    }

    public function supervisor_of_user()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function users_of_supervisor()
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }


    public function company_setting()
    {
        return $this->hasOne(CompanySetting::class, 'ta_da_supervisor_id');
    }


    public function department()
    {
        return $this->belongsTo(EmployeeDepartment::class, 'employee_department_id');
    }

}
