//resources/views/subscription_success.blade.php
@extends('layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') Pages @endslot
@slot('title') Starter  @endslot
@endcomponent
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
   
                <div class="card-body">
   
                    <div class="alert alert-success">
                        Subscription purchase successfully!
                    </div>
   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection