<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function __construct()
    {
        view()->share('main_menu', 'admin console');
        view()->share('sub_menu', 'permissions');
        $this->middleware(['admin_console_access']);
    }

    public function index()
    {
        return view('admin_console.permissions.index', [
            'permissions' => Permission::latest('id')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string|required|max:100'
        ]);

        try {
            $validated['slug'] = Str::slug($validated['name']);
            Permission::create($validated);
            return back()->withSuccess('Permission saved successfully');
        } catch (Exception $e) {
            return back()->withError('Permission save failed');
        }
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'string|required|max:100'
        ]);

        try {
            Permission::where('id', $permission->id)->update($validated);
            return back()->withSuccess('Permission updated successfully');
        } catch (Exception $e) {
            return back()->withError('Permission update failed');
        }
    }

    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();
            return back()->withSuccess('Permission deleted successfully');
        } catch (Exception $e) {
            return back()->withError('Permission delete failed');
        }
    }
}
