<?php

namespace App\Http\Controllers\LaptopManagement;

use App\Models\Laptop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LaptopController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /** Validation rules shared by store() and update(). */
    private function rules($ignoreId = null)
    {
        return [
            'brand'         => 'required|string|max:255',
            'model'         => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:laptops,serial_number' . ($ignoreId ? ',' . $ignoreId : ''),
            'processor'     => 'nullable|string|max:255',
            'ram_gb'        => 'nullable|integer|min:1|max:1024',
            'storage_gb'    => 'nullable|integer|min:1|max:100000',
            'price'         => 'required|numeric|min:0',
            'currency'      => 'required|string|max:10',
            'purchase_date' => 'nullable|date',
            'status'        => 'nullable|in:' . implode(',', Laptop::STATUSES),
        ];
    }

    public function index()
    {
        $laptops = Laptop::with('user')->latest()->get();

        return view('Laptop-Management.Laptops.list', compact('laptops'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['user_id'] = auth()->user()->id;

        // Status is optional on the form; a new laptop starts as available.
        if (empty($data['status'])) {
            $data['status'] = 'available';
        }

        Laptop::create($data);

        return redirect()->route('laptop.index')
            ->with('success', 'Laptop created successfully.');
    }

    public function edit($id)
    {
        $laptop = Laptop::findOrFail(decrypt($id));

        return view('Laptop-Management.Laptops.edit', compact('laptop'));
    }

    public function update(Request $request, $id)
    {
        $laptop = Laptop::findOrFail(decrypt($id));

        $validator = Validator::make($request->all(), $this->rules($laptop->id));

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Blank status on the edit form means "leave it as it is".
        if (empty($data['status'])) {
            unset($data['status']);
        }

        $laptop->update($data);

        return redirect()->route('laptop.index')
            ->with('success', 'Laptop updated successfully.');
    }

    public function destroy($id)
    {
        Laptop::findOrFail(decrypt($id))->delete();

        return redirect()->route('laptop.index')
            ->with('success', 'Laptop deleted successfully.');
    }
}
