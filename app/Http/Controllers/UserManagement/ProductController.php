<?php

namespace App\Http\Controllers\UserManagement;

use App\Events\ProductCreated;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use App\Models\Plan;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Products; // Add this import statement

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }
    //-----------------------------------------------------------------------------------------------------------------------------



    public function index()
    {
        $products = Product::with('user')->get();     // gets products by latest arrangement
        return view('Product-Management.Products.list', compact('products'))->with('i', (request()->input('page', 1) - 1) * 5);
    }


    public function store(Request $request)
    {
            // validates the name and detail
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'detail' => 'required',
                'price' => 'required|numeric',
                'date' => 'nullable|date',
                'quantity' => 'nullable|integer',
                'currency' => 'required',
            ]);

            // prints the follwing statement if validation fails
            if ($validator->fails()) {
                return redirect()->back()->with('warning', 'Cannot add duplicate product');
            }

            // it will store the validated data into the database
            $request->merge(['user_id' => auth()->user()->id]);

            Product::create($request->all());

            $data = ['name' => $request->name, 'detail' => $request->detail, 'price' => $request->price,  'creator' => auth()->user()->name];

            //event called here....
            // event(new ProductCreated($data));

            // redirect back to the list page after
            return redirect()->route('product.index')
                ->with('success', 'Product created successfully.');
        // }
    }

    public function edit($id)
    {
        $id = decrypt($id);   // decrypts the id after encryption in the blade file
        $product = Product::find($id);  // feed the product variable into the blade file
        return view('Product-Management.Products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
            'price' => 'required|numeric',
            'date' => 'nullable|date',
            'quantity' => 'nullable|integer',
            'currency' => 'required',

        ]);
        Product::find($id)->update(
            [
                'name' => $request->name,
                'detail' => $request->detail,
                'price' => $request->price,
                'date' => $request->date,
                'quantity' => $request->quantity,
                'currency' => $request->currency,
            ]
        );
        return redirect()->route('product.index')
            ->with('success', 'Product updated successfully');
    }


    public function destroy($id)
    {
        $id = decrypt($id);
        Product::find($id)->delete();   // delete the several id through the method
        return redirect()->route('product.index')
            ->with('success', 'Product deleted successfully');
    }

    public function exportproduct()
    {
        return Excel::download(new ProductExport, 'product.xlsx');
    }

    public function importproduct(Request $request)
    {
        // dd('import');

        Excel::import(new ProductImport, $request->file('file'));
        return redirect()->back();
    }

    public function downloadpdf()
    {
        $avatarUrl = public_path('/assets/images/shopify-logo.png');
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $type = pathinfo($avatarUrl, PATHINFO_EXTENSION);
        $avatarData = file_get_contents($avatarUrl, false, stream_context_create($arrContextOptions));
        $avatarBase64Data = base64_encode($avatarData);
        $base64 = 'data:image/' . $type . ';base64,' . $avatarBase64Data;

        $nerdflow = public_path('images/nerdflow.jfif');

        $product = Product::all();
        $totalprice = $product->sum('price');
        $randomNumber = mt_rand(100000000, 999999999);

        $pdf = Pdf::loadView('pdf.pdf', compact('product', 'base64', 'totalprice', 'randomNumber', 'nerdflow'));
        return $pdf->download('webappfix.pdf');
    }
}
