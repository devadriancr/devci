<?php

namespace App\Http\Livewire\ConsignmentInstruction;

use App\Imports\ConsignmentMcMhImport;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ShowMcMh extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $scanEnabled = false;
    public $excelFile;
    public $importSummary;
    public $importErrors = [];

    protected $listeners = ['show-mc-mh' => 'render', 'scan-enabled' => 'render'];

    protected $rules = [
        'excelFile' => 'required|file|mimes:xlsx,xls,csv|max:10240',
    ];

    protected $messages = [
        'excelFile.required' => 'Debe seleccionar un archivo Excel.',
        'excelFile.mimes' => 'El archivo debe ser de tipo xlsx, xls o csv.',
        'excelFile.max' => 'El archivo no puede pesar más de 10 MB.',
    ];

    public function render()
    {
        $mcmh = DB::table('consignment_data')
            ->orderBy('max_id', 'DESC')
            ->simplePaginate(5);

        return view('livewire.consignment-instruction.show-mc-mh', compact('mcmh'));
    }

    public function importExcel()
    {
        $this->reset(['importSummary', 'importErrors']);

        $this->validate();

        $import = new ConsignmentMcMhImport();

        Excel::import($import, $this->excelFile);

        $this->importSummary = "Registros procesados: {$import->total} | Importados: {$import->imported} | Duplicados: {$import->duplicated} | Errores: " . count($import->errors);
        $this->importErrors = $import->errors;

        $this->reset(['excelFile']);

        $this->emit('show-mc-mh');
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
