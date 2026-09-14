@extends('layouts.master')
@section('title')
    Set your password
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Account
        @endslot
        @slot('title')
            Set your password
        @endslot
    @endcomponent

    @include('partials.session')

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Choose your own password</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        You are signed in as <strong>{{ auth()->user()->email }}</strong>. Replace the temporary
                        password you were sent with one only you know.
                    </p>

                    <form action="{{ route('password.set.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New password</label>
                            <input type="password" class="form-control" id="new_password" name="password"
                                placeholder="At least 8 characters" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm new password</label>
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="password_confirmation" placeholder="Repeat the password" required>
                        </div>
                        <button type="submit" id="save-password" class="btn btn-success">Save password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
