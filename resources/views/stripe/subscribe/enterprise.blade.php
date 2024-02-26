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
                    <h2><sup><small>$</small></sup>10<span class="fs-13 text-muted">
                            /{{ $saal->billing_method }}</span></h2>
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
                    <a onclick="getPlan('{{ $enterprise->plan_id }}')"
                        class="btn btn-soft-secondary w-100 waves-effect waves-light">Sign up now</a>
                </div>
            </div>
        </div>
    </div>
</div>
