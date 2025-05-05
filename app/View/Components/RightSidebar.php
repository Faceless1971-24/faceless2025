<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RightSidebar extends Component
{
    // public $userRoles;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->userRoles = session('roles') ?? [];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.right-sidebar');
    }
}
