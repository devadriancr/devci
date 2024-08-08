<?php

namespace App\Http\Livewire\ConsignmentInstruction;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowMcMh extends Component
{
    protected $listeners = ['show-mc-mh' => 'render'];

    public function render()
    {
        $mcmh = DB::table('consignment_data')
            ->orderBy('max_id', 'DESC')
            ->paginate(5);

        return view('livewire.consignment-instruction.show-mc-mh', ['mcmh' => $mcmh]);
    }
}
