<?php

namespace App\Http\Livewire\ConsignmentInstruction;

use App\Models\Input;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\Location;
use App\Models\TransactionType;
use App\Models\YH003;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateMcMh extends Component
{
    public $code_qr;
    public $count = 0;
    public $processing = false;
    public $successMessage;
    public $warningMessage;

    protected $listeners = [
        'count-clean' => 'render',
    ];

    protected $rules = [
        'code_qr' => [
            'required',
            'string',
            'min:30',
            'max:35'
        ],
    ];

    protected $messages = [
        'code_qr.required' => 'El campo Código de Barras es obligatorio.',
        'code_qr.string' => 'El campo Código de Barras debe ser una cadena de texto.',
        'code_qr.max' => 'El campo Código de Barras no puede tener más de 35 caracteres.',
        'code_qr.min' => 'El campo Código de Barras debe tener al menos 30 caracteres.',
    ];

    public function save()
    {
        $this->reset(['successMessage', 'warningMessage']);

        $this->validate();

        $this->processing = true;

        $code = strtoupper($this->code_qr);

        $no_order = substr($code, 0, 7);
        $serial = substr($code, 0, 10);
        $part_no = substr($code, 10, 10);
        $snp = substr($code, 20, 6);
        $supplier = substr($code, 26, 5);
        $type = substr($code, 31, 2);

        $input = Input::findExistingInput($supplier, $serial, $snp, $type, $no_order);

        if ($input) {
            $this->reset(['code_qr']);
            $this->processing = false;
            $this->warningMessage = 'Material Registrado Anteriormente';
            return;
        }

        $item = Item::where('item_number', 'LIKE', $part_no . '%')->firstOrFail();
        $transaction = TransactionType::where('code', 'LIKE', 'U3')->firstOrFail();
        $location = Location::where('code', 'LIKE', 'L60%')->firstOrFail();

        $input = Input::create([
            'no_order' => $no_order,
            'supplier' => $supplier,
            'serial' => $serial,
            'item_id' => $item->id,
            'item_quantity' => $snp,
            'type_consignment' => $type,
            'transaction_type_id' => $transaction->id,
            'location_id' => $location->id,
            'user_id' => Auth::id()
        ]);

        YH003::store($item, $supplier, $serial, $snp);

        Inventory::updateInventory($item->id, $location->id, $snp);

        $this->reset(['code_qr']);

        $this->emit('show-mc-mh');

        $this->successMessage = 'El Registro del Material se Hizo Correctamente.';
        $this->processing = false;

        $this->count++;
    }

    public function render()
    {
        return view('livewire.consignment-instruction.create-mc-mh');
    }

    public function countClean()
    {
        $this->reset(['count']);
    }
}
