<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="fal fa-arrow-left"></i>
        </a>
        {{ __('Menu Chính') }}
        <a href="#" class="sidebar-mobile-expand">
            <i class="fal fa-expand"></i>
            <i class="fal fa-compress"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->


    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user-material">

            <div class="collapse" id="user-nav">
                <ul class="nav nav-sidebar">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="icon-comment-discussion"></i>
                            <span>{{ __('Thông báo') }}</span>
                            <span class="badge bg-success-400 badge-pill align-self-center ml-auto">3</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link" onclick="$('#logout-form').submit()">
                            <i class="fal fa-sign-out"></i>
                            <span>{{ __('Đăng xuất') }}</span>
                        </a>
                        <form id="logout-form" method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">
                        {{ __('Menu') }}
                        <a href="{{ route('admin.dashboards') }}"
                           class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block menu-nav">
                            <i class="fal fa-bars"></i>
                        </a>
                    </div>
                    <i class="fal fa-bars navbar-nav-link sidebar-control sidebar-main-toggle"
                       title="{{ __('Menu') }}"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.dashboards') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboards*') ? 'active' : null }}">
                        <i class="fal fa-home"></i>
                        <span>
                            {{ __('Dashboard') }}
                        </span>
                    </a>
                </li>

                @can('tasks.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.tasks.index') }}"
                           class="nav-link {{ request()->routeIs('admin.tasks*') ? 'active' : null }}">
                            <i class="fal fa-copyright"></i>
                            <span>
                            {{ __("Danh sách case") }}
                        </span>
                        </a>
                    </li>
                @endcan

            <!-- Report -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">{{ __('Báo cáo') }}</div>
                    <i class="fal fa-horizontal-rule" title="{{ __('Báo cáo') }}"></i>
                </li>
                <li class="nav-item nav-item-submenu {{ request()->routeIs('admin.reports*') ? 'nav-item-expanded nav-item-open' : null }}">
                    <a href="#" class="nav-link"><i class="fal fa-user"></i> <span>{{ __('Báo cáo') }}</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="{{ __('Báo cáo') }}">
                        <li class="nav-item">
                            <a href="{{ route('admin.reports.month') }}"
                               class="nav-link @if(request()->routeIs('admin.reports.month'))active @endif">{{ __('Báo cáo tháng') }}</a>
                        </li>
                        @can('roles.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.reports.customer') }}"
                                   class="nav-link @if(request()->routeIs('admin.reports.customer'))active @endif">{{ __('Báo cáo khách hàng') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.reports.employee') }}"
                                   class="nav-link @if(request()->routeIs('admin.reports.employee'))active @endif">{{ __('Báo cáo nhân viên') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.reports.salary') }}"
                                   class="nav-link @if(request()->routeIs('admin.reports.salary'))active @endif">{{ __('Lương và năng lực') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>

                <!-- System -->
                @canany(['admins.view', 'menus.index', 'log-activities.index', 'admins.view'])
                    <li class="nav-item-header">
                        <div class="text-uppercase font-size-xs line-height-xs">{{ __('Hệ thống') }}</div>
                        <i class="fal fa-horizontal-rule" title="{{ __('Hệ thống') }}"></i></li>
                @endcan
                @canany(['admins.view'])
                    <li class="nav-item nav-item-submenu {{ request()->routeIs('admin.admins*') || request()->routeIs('admin.roles*') ? 'nav-item-expanded nav-item-open' : null }}">
                        <a href="#" class="nav-link"><i class="fal fa-user"></i> <span>{{ __('Tài khoản') }}</span></a>
                        <ul class="nav nav-group-sub" data-submenu-title="{{ __('Tài khoản') }}">
                            @can('admins.view')
                                <li class="nav-item"><a href="{{ route('admin.admins.index') }}"
                                                        class="nav-link @if(request()->routeIs('admin.admins*'))active @endif">{{ __('Tài khoản') }}</a>
                                </li>
                            @endcan
                            @can('roles.view')
                                <li class="nav-item"><a href="{{ route('admin.roles.index') }}"
                                                        class="nav-link @if(request()->routeIs('admin.roles*'))active @endif">{{ __('Vai trò') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
