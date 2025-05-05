<?php

namespace App\Services;

use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;

class LocationService
{
    public function getDistrictsByDivision($divisionId)
    {
        return Division::with('districts')->find($divisionId)?->districts ?? [];
    }

    public function getUpazilasByDistrict($districtId)
    {
        return District::with('upazilas')->find($districtId)?->upazilas ?? [];
    }

    public function getUnionsByUpazila($upazilaId)
    {
        return Upazila::with('unions')->find($upazilaId)?->unions ?? [];
    }

    public function getFullHierarchy($divisionId)
    {
        return Division::with('districts.upazilas.unions')->find($divisionId);
    }
}
