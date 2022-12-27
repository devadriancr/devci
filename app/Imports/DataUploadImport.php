<?php

namespace App\Imports;

use App\Models\Input;
use Maatwebsite\Excel\Concerns\ToModel;

class DataUploadImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Input([
            //
        ]);
    }
}
