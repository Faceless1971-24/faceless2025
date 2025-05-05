<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('user-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'string|required|email',
            'gender' => 'integer',
            'dob' => 'date|nullable',
            'blood_group' => 'integer|nullable',
            'address' => 'nullable|string',
            'post_code' => 'nullable|string',
            'phone' => 'required|string',
            'photo' => 'mimes:png,jpg,jpeg|max:2048|nullable',
            'district_id' => 'required|integer',
            'division_id' => 'required|integer',
            "upazila_id" => 'required|integer',
            "union_id" => 'required|integer',

            'em_contact_name' => 'nullable|string',
            'em_contact_relation' => 'nullable|string',
            'em_contact_phone' => 'nullable|string',
            'em_contact_email' => 'string|nullable|email',

            'employee_department_id' => 'integer|nullable',
            
            'joining_date' => 'date|nullable',
            'duration' => 'integer|nullable',
            'last_educational_qual' => 'string|nullable',
            'designation_id' => 'integer|nullable',
            'user_type_id' => 'integer|required',
            'supervisor_id' => 'integer|nullable',

        ];
    }
}
