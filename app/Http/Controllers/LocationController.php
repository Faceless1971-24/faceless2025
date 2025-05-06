<?php

// app/Http/Controllers/LocationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;

class LocationController extends Controller
{
    public function getDistricts($divisionIds)
    {
        $divisionIdsArray = explode(',', $divisionIds);

        $districts = District::whereIn('division_id', $divisionIdsArray)
            ->select('id', 'bn_name')
            ->get()
            ->pluck('bn_name', 'id')
            ->toArray();

        return response()->json($districts);
    }

    public function getUpazilas($districtIds)
    {
        $districtIdsArray = explode(',', $districtIds);

        $upazilas = Upazila::whereIn('district_id', $districtIdsArray)
            ->select('id', 'bn_name')
            ->get()
            ->pluck('bn_name', 'id')
            ->toArray();

        return response()->json($upazilas);
    }

    public function getUnions($upazilaIds)
    {
        $upazilaIdsArray = explode(',', $upazilaIds);

        $unions = Union::whereIn('upazilla_id', $upazilaIdsArray)
            ->pluck('bn_name', 'id')
            ->toArray();


        return response()->json($unions);
    }

    
}
