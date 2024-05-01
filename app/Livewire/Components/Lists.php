<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Lists extends Component
{
    public $data, $attrs, $icon, $section, $buttons;

    protected $paginationTheme = 'bootstrap';
    
    public function mount(string $icon, string $section, array $attr, array $data, array $buttons)
    {
        $this->attrs = $attr;
        $this->data = $data;
        $this->icon = $icon;
        $this->section = $section;
        $this->buttons = $buttons;
    }

    public function render()
    {
        return view('livewire.components.lists');
    }

    public function __set($name, $value)
    {
        // Crea dinámicamente una propiedad pública con el nombre $name y le asigna el valor $value
        $this->$name = $value;
    }
    

    public function showFormEdit($model_name, $id)
    {
        $this->dispatch('showFormModal', $model_name, $id);
    }
}
