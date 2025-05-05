<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Exception;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct()
    {
        view()->share('main_menu', 'admin console');
        view()->share('sub_menu', 'menu');
        $this->middleware(['admin_console_access']);
    }

    public function index()
    {
        return view('admin_console.menus.index', [
            'menus' => Menu::with('parent_menu')->withCount('sub_menus')->latest('id')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'string|required|max:100',
            'route_name' => 'string|nullable|max:200',
            'menu_icon' => 'string|nullable|max:50',
            'menu_order' => 'integer|nullable',
            'parent_menu_id' => 'integer|nullable',
            'is_active' => 'integer|required',
        ]);
        try {
            Menu::create($validated);
            return back()->withSuccess('Menu saved successfully');
        } catch (Exception $e) {
            return back()->withError('Menu save failed');
        }
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => 'string|required|max:100',
            'route_name' => 'string|nullable|max:200',
            'menu_icon' => 'string|nullable|max:50',
            'menu_order' => 'integer|nullable',
            'parent_menu_id' => 'integer|nullable',
            'is_active' => 'integer|required'
        ]);

        try {
            Menu::where('id', $menu->id)->update($validated);
            return back()->withSuccess('Menu updated successfully');
        } catch (Exception $e) {
            return back()->withError('Menu update failed');
        }
    }

    public function destroy(Menu $menu)
    {
        try {
            $menu->delete();
            return back()->withSuccess('Menu deleted successfully');
        } catch (Exception $e) {
            return back()->withError('Menu delete failed');
        }
    }
}
