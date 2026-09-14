@extends('layouts.master')
@section('title')
    @lang('translation.grid-js')
@endsection
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/gridjs/gridjs.min.css') }}">
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Laptops
        @endslot
        @slot('title')
            Edit Laptop
        @endslot
    @endcomponent

    @include('partials.session')
    <!-- Row starts -->
    <div class="row">

        <!-- Column starts -->
        <div class="col-lg-12">

            <!-- start card -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 flex-grow-1">Edit Laptop</h4>
                    <a href="{{ route('laptop.index') }}"
                        class="btn btn-outline-success waves-effect waves-light px-4 py-2">Go to Laptop List</a>
                </div>

                <!-- start card-body -->
                <div class="card-body">
                    <form action="{{ route('laptop.update', encrypt($laptop->id)) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand"
                                value="{{ old('brand', $laptop->brand) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control" id="model" name="model"
                                value="{{ old('model', $laptop->model) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="serial_number" name="serial_number"
                                value="{{ old('serial_number', $laptop->serial_number) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="processor" class="form-label">Processor</label>
                            <input type="text" class="form-control" id="processor" name="processor"
                                value="{{ old('processor', $laptop->processor) }}">
                        </div>
                        <div class="mb-3">
                            <label for="ram_gb" class="form-label">RAM (GB)</label>
                            <input type="number" class="form-control" id="ram_gb" name="ram_gb" min="1"
                                max="1024" value="{{ old('ram_gb', $laptop->ram_gb) }}">
                        </div>
                        <div class="mb-3">
                            <label for="storage_gb" class="form-label">Storage (GB)</label>
                            <input type="number" class="form-control" id="storage_gb" name="storage_gb" min="1"
                                max="100000" value="{{ old('storage_gb', $laptop->storage_gb) }}">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" class="form-control" id="price" name="price"
                                value="{{ old('price', $laptop->price) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="currency" class="form-label">Currency</label>
                            <select class="form-select" id="currency" name="currency" required>
                                @foreach (['USD', 'PKR', 'EUR', 'GBP', 'AED', 'INR', 'CNY', 'AUD', 'CAD'] as $cur)
                                    <option value="{{ $cur }}" {{ old('currency', $laptop->currency) === $cur ? 'selected' : '' }}>{{ $cur }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control" id="purchase_date" name="purchase_date"
                                value="{{ old('purchase_date', $laptop->purchase_date) }}">
                        </div>
                        <div class="mb-3">
                            <label for="laptop_status" class="form-label">Status</label>
                            <select class="form-select" id="laptop_status" name="status">
                                <option value="">Leave unchanged</option>
                                @foreach (\App\Models\Laptop::STATUSES as $state)
                                    <option value="{{ $state }}" {{ old('status', $laptop->status) === $state ? 'selected' : '' }}>
                                        {{ ucfirst($state) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success waves-effect waves-light mt-3">Update</button>
                    </form>
                </div><!-- end card-body -->

            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/gridjs/gridjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/gridjs.init.js') }}"></script>

    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
