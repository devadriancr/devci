<?php

namespace App\Http\Livewire;

use App\Exports\ItemReportExport;
use App\Models\Input;
use App\Models\Item;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ItemReport extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedPartNumbers = [];
    public $startDate;
    public $endDate;

    public function render()
    {
        $itemClasses = ['S1'];

        $partNumbers = Item::where('item_number', 'like', '%' . $this->search . '%')
            ->whereHas('itemClass', function ($query) use ($itemClasses) {
                $query->whereIn('code', $itemClasses);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.item-report', compact('partNumbers'));
    }

    // public function toggleSelection($partNumberId)
    // {
    //     $item = Item::find($partNumberId);

    //     if ($item) {
    //         $key = array_search($item->item_number, $this->selectedPartNumbers);

    //         if ($key === false) {
    //             $this->selectedPartNumbers[] = $item->item_number;
    //         } else {
    //             unset($this->selectedPartNumbers[$key]);
    //         }
    //     }
    // }

    public function clearSelection()
    {
        $this->selectedPartNumbers = [];
        $this->reset('startDate', 'endDate');
    }

    public function exportSelected()
    {
        $this->validate([
            'startDate' => 'required',
            'endDate' => 'required',
        ], [
            'startDate.required' => 'La fecha de inicio es obligatoria.',
            'endDate.required' => 'La fecha final es obligatoria.',
        ]);

        $query = Input::query()
            ->select(
                'items.id as id_item',
                'inputs.supplier',
                'inputs.serial',
                'items.item_number',
                'inputs.item_quantity',
                'inputs.type_consignment',
                'containers.code as container_code',
                'transaction_types.code as transaction_code',
                'transaction_types.name as transaction_name',
                'locations.code as location_code',
                'locations.name as location_name',
                'inputs.created_at'
            )
            ->join('items', 'inputs.item_id', '=', 'items.id')
            ->join('transaction_types', 'inputs.transaction_type_id', '=', 'transaction_types.id')
            ->join('locations', 'inputs.location_id', '=', 'locations.id')
            ->join('users', 'inputs.user_id', '=', 'users.id')
            ->leftJoin('containers', 'inputs.container_id', '=', 'containers.id')
            ->whereIn('items.id', $this->selectedPartNumbers);

        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::parse($this->startDate)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = Carbon::parse($this->endDate)->endOfDay()->format('Y-m-d H:i:s');

            $query->whereBetween('inputs.created_at', [$startDate, $endDate]);
        }

        $selectedItems = $query->orderBy('inputs.created_at', 'desc')->get();

        return Excel::download(new ItemReportExport($selectedItems), 'report_' . date('dmYHis') . '.xlsx');
    }
}
