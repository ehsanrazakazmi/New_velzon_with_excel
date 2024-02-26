
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
                <h2><sup><small>$</small></sup>2<span class="fs-13 text-muted">
                        /{{ $hafta->billing_method }}</span></h2>
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
                    <a onclick="getPlan('{{ $basic->plan_id }}')"
                        class="btn btn-soft-secondary w-100 waves-effect waves-light">Sign up now</a>
                </div>
            </div>
        </div>
    </div>
</div>
