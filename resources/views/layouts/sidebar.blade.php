<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('root') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('root') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>Menu</span></li>


                <!-- User Management -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-user-fill"></i> <span>User Management</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            @can('Role list')
                                <li class="nav-item">
                                    <a href="{{ route('index.page') }}"
                                        class="nav-link {{ request()->is('roles/*') ? 'active' : '' }}">Role</a>
                                </li>
                            @endcan

                            @can('User list')
                                <li class="nav-item">
                                    <a href="{{ route('user.index') }}"
                                        class="nav-link {{ request()->is('user/*') ? 'active' : '' }}">User</a>
                                </li>
                            @endcan

                        </ul>
                    </div>
                </li>
                @can('Product list')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarDashboards1" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-product-hunt-fill"></i> <span>Product Management</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarDashboards1">
                            <ul class="nav nav-sm flex-column">


                                <li class="nav-item">
                                    <a href="{{ route('product.index') }}"
                                        class="nav-link {{ request()->is('product/*') ? 'active' : '' }}">Products</a>
                                </li>




                            </ul>
                        </div>
                    </li>
                @endcan

                @can('Laptop list')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarLaptops" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarLaptops">
                            <i class="ri-macbook-line"></i> <span>Laptop Management</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarLaptops">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('laptop.index') }}"
                                        class="nav-link {{ request()->is('laptop/*') ? 'active' : '' }}">Laptops</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDashboards2" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-money-dollar-box-line"></i> <span>Stripe Payment</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards2">
                        <ul class="nav nav-sm flex-column">


                            <li class="nav-item">
                                <a href="{{ route('plans.all') }}"
                                    class="nav-link {{ request()->is('plans/*') ? 'active' : '' }}">Choose your Plans</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('subscriptions.all') }}"
                                    class="nav-link {{ request()->is('subscriptions/*') ? 'active' : '' }}">View your Subscriptions</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('plans.create') }}"
                                    class="nav-link {{ request()->is('plan/*') ? 'active' : '' }}">Create new Plans</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('invoices.all') }}"
                                    class="nav-link {{ request()->is('plan/*') ? 'active' : '' }}">Invoices</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li class="nav-item" style="margin-top: 350px; color:aliceblue; list-style-type: none;">
                    <a href="{{ route('plans.all.update') }}" class="nav-link menu-link"   role="button"
                    > <i class="ri-refresh-line"></i> <span>Update Plan</span></a>
                </li>
            </ul>
        </div>
                {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDashboards4" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarDashboards"><i class="ri-money-dollar-box-line"></i> <span>Update Plan</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarDashboards4">
                    <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="{{ route('plans.all.update') }}" class="nav-link {{ request()->is('plans/*') ? 'active' : '' }}">Update Plan</a>
                    </li>
                    </ul>
                </div>
                </li> --}}
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>

</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
