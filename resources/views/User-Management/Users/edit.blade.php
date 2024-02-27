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

    <!-- Row start -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 flex-grow-1">Edit New User</h4>
                    <a href="{{ route('user.index') }}"
                        class="btn btn-outline-success waves-effect waves-light px-4 py-2 bg-green-700 hover:bg-green-500 text-slate-100 rounded-md">Go
                        to Users Index</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.update', encrypt($user->id)) }}">
                        @method('PATCH')
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Name" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password:</label>
                                    <input type="password" name="password" placeholder="Password" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="confirm-password" class="form-label">Confirm Password:</label>
                                    <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="iconInput" class="form-label">Email:</label>
                                    <div class="form-icon">
                                        <input type="text" name="email" value="{{ old('email', $user->email) }}" placeholder="example@gmail.com" class="form-control form-control-icon" id="iconInput">
                                        <i class="ri-mail-unread-line"></i>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="roles" class="form-label">Role:</label>
                                    <select name="roles[]" class="form-control">
                                        @foreach($roles as $roleId => $roleName)
                                            <option value="{{ $roleId }}" {{ in_array($roleId, $userRole) ? 'selected' : '' }}>
                                                {{ $roleName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn rounded-pill btn-success waves-effect waves-light mt-3">Update</button>
                    </form>
                                    </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <!-- end row -->
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/gridjs/gridjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/gridjs.init.js') }}"></script>

    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
