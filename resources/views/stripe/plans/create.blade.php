@extends('layouts.master')
@section('title')
    @lang('translation.starter')
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Plans
        @endslot
        @slot('title')
            Create
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4><b>Create Plans</b></h4>
                    <a href="{{ route('plans.all') }}"
                        class="btn btn-outline-success waves-effect waves-light px-4 py-2 bg-green-700 hover:bg-green-500 text-slate-100 rounded-md">Go
                        to Plan</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('plans.store') }}">
                        @csrf
                        <div class="form-group">
                            <label>Plan Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control" placeholder="Enter amount">
                        </div>
                        <div class="form-group">
                            <label>Currency</label>
                            <input type="text" name="currency" class="form-control" placeholder="Enter currency">
                        </div>
                        <div class="form-group">
                            <label>Interval Count</label>
                            <input type="number" name="interval_count" class="form-control" placeholder="Enter count">
                        </div>
                        <div class="form-group">
                            <label>Billing Period</label>
                            <select name="billing_period" class="form-control">
                                <option disabled selected>Choose billing method</option>
                                <option value="week">Weekly</option>
                                <option value="month">Monthly</option>
                                <option value="year">Yearly</option>
                            </select>
                        </div><br>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
