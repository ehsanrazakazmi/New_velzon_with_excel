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
            Users
        @endslot
        @slot('title')
            Edit Users
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
                    <h4 class="card-title mb-0 flex-grow-1">Edit New Product</h4>
                    <a href="{{ route('product.index') }}" class="btn btn-outline-success waves-effect waves-light px-4 py-2 bg-green-700 hover:bg-green-500 text-slate-100 rounded-md">Go to Product Index</a>
                </div>

                <!-- start card-body -->
                <div class="card-body">
                    <form action="{{ route('product.update', encrypt($product->id)) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-floating">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" name="name" value="{{ $product->name }}" class="form-control" placeholder="Name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="detail" class="form-label">Details</label>
                                <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail" required>{{ $product->detail }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" class="form-control rounded-pill" id="price" name="price" placeholder="Enter Price" value="{{ $product->price }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantity">Quantity</label>
                                <br>
                                <input type="number" id="quantity" name="quantity" min="1" max="1000" value="{{$product->quantity}}">
                            </div>
                            <div class="mb-3">
                                <label for="currency" class="form-label">Currency</label>
                                <input type="text" class="form-control rounded-pill" id="currency" name="currency" placeholder="USD|GBP|PKR|AED|" value="{{ $product->currency}}" required>
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control rounded-pill" id="date" name="date" placeholder="Select Date" value="{{ $product->date }}">
                            </div>  
                            <button type="submit" class="btn rounded-pill btn-success waves-effect waves-light mt-3">Update</button>
                        </div>
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
