<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    public $message;
    public $type;
    public $class;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type = null, $message = null)
    {
        $this->type = $type;
        $this->message = $message;

        if (session()->has('success')) {
            $this->type = 'success';
            $this->message = session('success');
        } elseif (session()->has('error')) {
            $this->type = 'error';
            $this->message = session('error');
        } elseif (session()->has('warning')) {
            $this->type = 'warning';
            $this->message = session('warning');
        }

        $this->class = [
            'success' => 'success',
            'warning' => 'warning',
            'danger' => 'danger',
            'error' => 'danger',
            'primary' => 'primary',
            'info' => 'info'
        ][strtolower($this->type)] ?? 'info';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.alert');
    }
}
