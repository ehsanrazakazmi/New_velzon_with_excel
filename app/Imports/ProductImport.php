<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProductImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // $serialDate = $row['date'];
        // $convertedDate = date('Y-m-d', strtotime('1900-01-01 +' . ($serialDate - 2) . ' days'));
        return new Product([
            'name' => $row['name'],
            'detail' => $row['detail'],
            'price' => $row['price'],
            // 'date'=> $row['date'],
            // 'date' => $convertedDate,
            'quantity' => $row['quantity'],
        ]);
    }
}
