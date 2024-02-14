<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row['date']);

        // return new Product([
        //     'name'=> $row[0],
        //     'detail'=> $row[1],
        //     'price'=> $row[2],
        //     'date'=> $row[3],
        //     'quantity'=> $row[4],
        // ]);
        $serialDate = $row['date'];
        $convertedDate = date('Y-m-d', strtotime('1900-01-01 +' . ($serialDate - 2) . ' days'));
        // $convertedDate = Date::excelToDateTimeObject($row['date'])->format('d/m/Y');
        
        return new Product([
            'name'=> $row['name'],
            'detail'=> $row['detail'],
            'price'=> $row['price'],
            // 'date'=> $row['date'],
            'date'=> $convertedDate ,
            'quantity'=> $row['quantity'],
        ]);
    }
}
