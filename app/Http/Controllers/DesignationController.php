<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\LeaveSlot;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DesignationController extends Controller
{

    public function __construct()
    {
        view()->share('main_menu', 'settings');
        view()->share('sub_menu', 'designation');
    }

    public function index()
    {
        abort_unless(auth()->user()->can('designation_manage'), Response::HTTP_UNAUTHORIZED);
        return view('settings.designation.index', [
            'designations' => Designation::latest('id')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string|required|max:100'
        ]);

        try {
            Designation::create($validated);
            return back()->withSuccess('Designation saved successfully');
        } catch (Exception $e) {
            return back()->withError('Designation save failed');
        }
    }

    public function update(Request $request, Designation $designation)
    {
        $validated = $request->validate([
            'name' => 'string|required|max:100'
        ]);

        try {
            Designation::where('id', $designation->id)->update($validated);
            return back()->withSuccess('Designation updated successfully');
        } catch (Exception $e) {
            return back()->withError('Designation update failed');
        }
    }

    public function destroy(Designation $designation)
    {
        try {
            $designation->delete();
            return back()->withSuccess('Designation delete successfully');
        } catch (Exception $e) {
            return back()->withError('Designation delete failed');
        }
    }
}
