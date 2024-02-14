<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Product::all();
        // Select only the columns you want
        $products = Product::select('id', 'name', 'detail', 'price', 'date', 'quantity')->get();

        // Format the date in DD/MM/YYYY format
        $formattedProducts = $products->map(function ($product) {
            $product['date'] = $product['date'] ? Carbon::parse($product['date'])->format('d/m/Y') : '';
            return $product;
        });

        return $formattedProducts;
    }

    public function headings(): array
    {
        return [
            'Sr.',
            'Name',
            'Detail',
            'Price',
            'Date',
            'Quantity',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Make the first row (headings) bold
        $sheet->getStyle('1')->getFont()->setBold(true);
    }
}
