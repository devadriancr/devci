<?php

namespace App\Http\Livewire;

use App\Exports\ItemReportExport;
use App\Models\Input;
use App\Models\Item;
use App\Models\Location;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ItemReport extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedPartNumbers = [];
    public $selectedLocations = [];
    public $startDate;
    public $endDate;

    public function render()
    {
        $itemClasses = ['S1'];

        $locations = Location::query()->select('id', 'code', 'name')->whereIn('code', ['L60', 'L61', 'L12'])->get();

        $partNumbers = Item::where('item_number', 'like', '%' . $this->search . '%')
            ->whereHas('itemClass', function ($query) use ($itemClasses) {
                $query->whereIn('code', $itemClasses);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.item-report', ['partNumbers' => $partNumbers, 'locations' => $locations]);
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

    function selectAll()
    {
        $itemClasses = ['S1'];

        $items = Item::query()
            ->where('item_number', 'like', '%' . $this->search . '%')
            ->whereHas('itemClass', function ($query) use ($itemClasses) {
                $query->whereIn('code', $itemClasses);
            })
            ->pluck('id')
            ->toArray();

        if (count($this->selectedPartNumbers) !== count($items)) {
            $this->selectedPartNumbers =  $items;
        } else {
            $this->selectedPartNumbers = [];
        }
    }

    public function clearSelection()
    {
        $this->selectedPartNumbers = [];
        $this->selectedLocations = [];
        $this->reset('startDate', 'endDate');
    }

    public function exportSelected()
    {
        $this->validate(
            [
                'startDate' => 'required',
                'endDate' => 'required',
                'selectedPartNumbers' => 'required',
                'selectedLocations' => 'required'
            ],
            [
                'startDate.required' => 'La fecha de inicio es obligatoria.',
                'endDate.required' => 'La fecha final es obligatoria.',
                'selectedPartNumbers.required' => 'El número de parte es obligatorio',
                'selectedLocations.required' => 'La locación es obligatorio'
            ]
        );

        $startDate = Carbon::parse($this->startDate)->startOfDay()->format('Ymd H:i:s.v');
        $endDate = Carbon::parse($this->endDate)->endOfDay()->format('Ymd H:i:s.v');

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
            ->whereIn('items.id', $this->selectedPartNumbers)
            ->whereIn('locations.id', $this->selectedLocations)
            ->whereBetween('inputs.created_at', [$startDate, $endDate])
            ->orderBy('inputs.created_at', 'desc')
            ->get();

        return Excel::download(new ItemReportExport($query), 'REPORT_' . date('dmYHis') . '.xlsx');
    }
}
