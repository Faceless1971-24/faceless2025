<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'user_id', 'membership_id', 'full_name', 'father_name', 'mother_name',
        'date_of_birth', 'nid', 'phone', 'email', 'present_address', 'permanent_address',
        'division_id', 'district_id', 'upazila_id', 'union_id', 'educational_qualification',
        'profession', 'blood_group', 'photo', 'nid_scan', 'reference_name', 'reference_phone',
        'reference_membership_id', 'status', 'rejection_reason', 'approval_date', 'expiry_date',
        'membership_type', 'is_paid', 'payment_method', 'payment_reference', 'payment_amount',
        'payment_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function union()
    {
        return $this->belongsTo(Union::class);
    }
}
