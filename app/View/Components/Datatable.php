<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Datatable extends Component
{
    public $id;
    public $class;
    public $columns;
    public $url;

    public $buttons;
    public $searching;
    public $paging;
    public $noDataInit; // if true then initially load datatable with no data

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($columns, $url, $id='dt-table', $class = '', $buttons = false, $searching = true, $paging = true, $noDataInit = false)
    {
        $this->id = $id;
        $this->columns = $columns;
        $this->class = $class;
        $this->url = $url;

        $this->buttons = $buttons;
        $this->searching = $searching;
        $this->paging = $paging;
        $this->noDataInit = $noDataInit;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.datatable');
    }
}
