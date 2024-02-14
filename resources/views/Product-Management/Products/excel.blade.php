@extends('layouts.master')
@section('title') @lang('translation.starter')  @endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') Excel @endslot
@slot('title') Prodduct  @endslot
@endcomponent
<!-- Default File Input Example -->
<div>
    <form class="form" method="POST" enctype="multipart/form-data" action="{{route('import-product')}}">
        @csrf
        <label for="formFile" class="form-label">Products data:</label>
        <input class="form-control" type="file" name="file" id="formFile">
        <div class="mt-5">
            <button type="submit" class="btn btn-info">Submit</button>

            <a href="{{route('export-product')}}" class="btn btn-primary float-right">Export Excel</a>
        </div>
    </form>
</div>
@endsection
@section('script')
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
