<?php

namespace App\Livewire\Kds;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.kds')]
class Display extends Component
{
    public function render()
    {
        return view('livewire.kds.display');
    }
}
