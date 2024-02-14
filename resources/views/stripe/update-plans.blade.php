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
            Stripe
        @endslot
    @endcomponent
    @include('partials.session')

    <div class="row justify-content-center">
        <div class="col-xl-9">
            <div class="row">
                @if ($basic)
                    <div class="col-lg-4">
                        <div class="card pricing-box">
                            <div class="card-body p-4 m-2">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold">Basic Plan</h5>
                                        <p class="text-muted mb-0">For Startup</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-light rounded-circle text-primary">
                                            <i class="ri-book-mark-line fs-20"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-4">
                                    <h2><sup><small>$</small></sup>2<span class="fs-13 text-muted"> /{{$hafta->billing_method}}</span></h2>
                                </div>
                                <hr class="my-4 text-muted">
                                <div>
                                    <ul class="list-unstyled text-muted vstack gap-3">
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    Upto <b>3</b> Projects
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    Upto <b>299</b> Customers
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    Scalable Bandwidth
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>5</b> FTP Login
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-danger me-1">
                                                    <i class="ri-close-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>24/7</b> Support
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-danger me-1">
                                                    <i class="ri-close-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Unlimited</b> Storage
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-danger me-1">
                                                    <i class="ri-close-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    Domain
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="mt-4">
                                        <a href="{{ route('plans.update.subscription', $basic->plan_id) }}"
                                            class="btn btn-soft-secondary w-100 waves-effect waves-light">Sign up now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!--end col-->

                @if ($professional)
                    <div class="col-lg-4">
                        <div class="card pricing-box ribbon-box right">
                            <div class="card-body p-4 m-2">
                                <div class="ribbon-two ribbon-two-danger"><span>Popular</span></div>
                                <div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1 fw-semibold">Pro Business</h5>
                                            <p class="text-muted mb-0">Professional plans</p>
                                        </div>
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-light rounded-circle text-primary">
                                                <i class="ri-medal-line fs-20"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-4">
                                        <h2><sup><small>$</small></sup> 5<span class="fs-13 text-muted"> /{{$mahina->billing_method}}</span></h2>
                                    </div>
                                </div>
                                <hr class="my-4 text-muted">
                                <div>
                                    <ul class="list-unstyled vstack gap-3 text-muted">
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    Upto <b>15</b> Projects
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Unlimited</b> Customers
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    Scalable Bandwidth
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>12</b> FTP Login
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1">
                                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>24/7</b> Support
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-danger me-1">
                                                    <i class="ri-close-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <b>Unlimited</b> Storage
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-danger me-1">
                                                    <i class="ri-close-circle-fill fs-15 align-middle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    Domain
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="mt-4">
                                        <a href="{{ route('plans.update.subscription', $professional->plan_id) }}"
                                            class="btn btn-success w-100 waves-effect waves-light">Get
                                            started</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!--end col-->
                @if ($enterprise)


                <div class="col-lg-4">
                    <div class="card pricing-box">
                        <div class="card-body p-4 m-2">
                            <div>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold">Platinum Plan</h5>
                                        <p class="text-muted mb-0">Enterprise Businesses</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-light rounded-circle text-primary">
                                            <i class="ri-stack-line fs-20"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <h2><sup><small>$</small></sup>10<span class="fs-13 text-muted"> /{{$saal->billing_method}}</span></h2>
                                </div>
                            </div>
                            <hr class="my-4 text-muted">
                            <div>
                                <ul class="list-unstyled vstack gap-3 text-muted">
                                    <li>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-success me-1">
                                                <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <b>Unlimited</b> Projects
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-success me-1">
                                                <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <b>Unlimited</b> Customers
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-success me-1">
                                                <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                Scalable Bandwidth
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-success me-1">
                                                <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <b>Unlimited</b> FTP Login
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-success me-1">
                                                <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <b>24/7</b> Support
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-success me-1">
                                                <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <b>Unlimited</b> Storage
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 text-success me-1">
                                                <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                Domain
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="mt-4">
                                    <a href="{{ route('plans.update.subscription', $enterprise->plan_id) }}"
                                        class="btn btn-soft-secondary w-100 waves-effect waves-light">Get started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('assets/js/pages/pricing.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
