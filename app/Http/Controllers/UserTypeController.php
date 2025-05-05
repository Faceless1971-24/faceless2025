<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserTypeController extends Controller
{
    public function __construct()
    {
        view()->share('main_menu', 'Admin Console');
        view()->share('sub_menu', 'user types');
        $this->middleware(['admin_console_access']);
    }

    public function index()
    {
        return view('admin_console.user_types.index', [
            'user_types' => Role::latest('id')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'string|required|max:100',
            'is_active' => 'integer|required',
        ]);

        try {
            $validated['slug'] = Str::slug($validated['title']);
            Role::create($validated);
            return back()->withSuccess('User type saved successfully');
        } catch (Exception $e) {
            return back()->withError('User type save failed');
        }
    }

    public function update(Request $request, $user_type)
    {
        $validated = $request->validate([
            'title' => 'string|required|max:100',
            'is_active' => 'integer|required',
        ]);

        try {
            $validated['slug'] = Str::slug($validated['title']);
            $role = Role::findOrFail($user_type);
            $role->title = $validated['title'];
            $role->is_active = $validated['is_active'];
            $role->save();
            return back()->withSuccess('User type updated successfully');
        } catch (Exception $e) {
            return back()->withError('User type update failed');
        }
    }

    public function destroy($user_type)
    {
        try {
            Role::find($user_type)->delete();
            return back()->withSuccess('User type deleted successfully');
        } catch (Exception $e) {
            return back()->withError('User type delete failed');
        }
    }

    public function config($user_type)
    {
        $userType = Role::findOrFail($user_type);
        $menus = Menu::active()->oldest('menu_order')->get();
        $permissions = Permission::latest('id')->get();

        $user_type_menus = DB::table('menu_role')
            ->where('role_id', $userType->id)
            ->select('menu_id')
            ->get()
            ->pluck('menu_id');

        $user_type_permissions = DB::table('permission_role')
            ->where('role_id', $userType->id)
            ->select('permission_id')
            ->get()
            ->pluck('permission_id');

        return view(
            'admin_console.user_types.config',
            compact('userType', 'menus', 'permissions', 'user_type_menus', 'user_type_permissions')
        );
    }

    public function updateMenus(Request $request, $user_type)
    {
        DB::beginTransaction();
        try {
            $role = Role::findOrFail($user_type);
            $role->menus()->detach();
            $role->menus()->attach($request->role_menus);

            DB::commit();
            return back()->withSuccess('User type menus saved Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withError('User type menus update failed');
        }
    }

    public function updatePermissions(Request $request, $user_type)
    {
        DB::beginTransaction();
        try {
            $role = Role::findOrFail($user_type);
            $role->permissions()->detach();
            $role->permissions()->attach($request->role_permissions);

            DB::commit();
            return back()->withSuccess('User type permissions saved Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withError('User type permissions save failed');
        }
    }
}
