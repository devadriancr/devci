<?php

namespace App\Http\Livewire\ConsignmentInstruction;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowMcMh extends Component
{
    use WithPagination;

    public $scanEnabled = false;

    protected $listeners = ['show-mc-mh' => 'render', 'scan-enabled' => 'render'];

    public function render()
    {
        $mcmh = DB::table('consignment_data')
            ->orderBy('max_id', 'DESC')
            ->paginate(5);

        return view('livewire.consignment-instruction.show-mc-mh', compact('mcmh'));
    }

    public function startScanning() {
        $this->scanEnabled = true;

        $this->emit('scan-enabled');
    }

    public function finishScanning() {
        $this->scanEnabled = false;
        session()->forget('scan_count');

        $this->emit('scan-enabled');
        $this->emit('count-clean');
    }
}
