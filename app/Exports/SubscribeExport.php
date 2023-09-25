<?php

namespace App\Exports;

use App\Subscribe;
use Maatwebsite\Excel\Concerns\FromCollection;

class SubscribeExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       // return Subscribe::all();
        return Subscribe::select('id','name','companyname','email','phonecompany','phone','job','industrialsector')->get();
    }
}
