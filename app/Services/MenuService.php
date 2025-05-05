<?php
namespace App\Services;

use App\Models\CompanySetting;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    public function getMenusForUser(User $user)
    {
        return Cache::remember("menus_{$user->id}", 60, function () use ($user) {
            if ($user->is_superuser) {
                $menus = Menu::active()->orderBy('menu_order', 'asc')->get();
            } else {
                // $role_id = session('role_id');
                $role = session()->has('role') ? session('role') : null; $supervisor = $role->id;
                // Role::find($role_id);
                $menus = $role ? $role->menus()->active()->orderBy('menu_order', 'asc')->get() : [];
            }
            // dd($menus);

            $preparedData = collect([]);
            $default_supervisor = $user->company_setting;
            foreach ($menus as $menu) {
                if (!$menu->parent_menu_id) {
                    $preparedData->push($menu);
                    $menu->main_menu = true;
                    $sub_menus = collect([]);
                    foreach ($menus as $sub_menu) { 
                        if ($menu->id === $sub_menu->parent_menu_id) {
                            if($user->is_superuser){ 
                                $sub_menus->push($sub_menu); 
                            }
                            elseif($sub_menu->route_name == 'bill.request'){
                                if($default_supervisor !=null){
                                    $sub_menus->push($sub_menu);
                                }
                                else if($role->slug == 'supervisor' || $role->slug == 'accounts'){  
                                    $sub_menus->push($sub_menu);
                                }
                            } 
                            elseif($sub_menu->route_name != 'bill.request') {
                            $sub_menus->push($sub_menu);
                            }
                        }
                    }
                    $menu->sub_menus = $sub_menus;
                }
            }

            return $preparedData;
        });
    }
}
