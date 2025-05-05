<?php

namespace App\View\Components;

use App\Services\MenuService;
use Illuminate\View\Component;

class LeftNav extends Component
{
    public $menuService;
    public $menus;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
        $this->menus = $this->menuService->getMenusForUser(auth()->user());
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.left-nav');
    }
}
