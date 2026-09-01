<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.pos')]
class Terminal extends Component
{
    public function render()
    {
        return view('livewire.pos.terminal');
    }
}
