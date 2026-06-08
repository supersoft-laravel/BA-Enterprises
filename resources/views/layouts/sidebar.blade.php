<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo">
                <img style="height: 35px;" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="{{ env('APP_NAME') }}">
            </span>
            {{-- <span class="app-brand-text demo menu-text fw-bold">{{\App\Helpers\Helper::getCompanyName()}}</span> --}}
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{ __('Dashboard') }}</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('dashboard.stats') ? 'active' : '' }}">
            <a href="{{ route('dashboard.stats') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{ __('Dashboard Stats') }}</div>
            </a>
        </li>

        <!-- Apps & Pages -->
        <li class="menu-header small">
            <span class="menu-header-text">{{ __('Apps & Pages') }}</span>
        </li>
        @can(['view case'])
            <li class="menu-item {{ request()->routeIs('dashboard.cases.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.cases.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-car"></i> {{-- Vehicle case --}}
                    <div>{{ __('Cases') }}</div>
                </a>
            </li>
        @endcan

        {{-- @can(['view transfer'])
            <li class="menu-item {{ request()->routeIs('dashboard.transfers.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.transfers.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-arrows-exchange"></i>
                    <div>{{ __('Transfers') }}</div>
                </a>
            </li>
        @endcan

        @can(['view alteration'])
            <li class="menu-item {{ request()->routeIs('dashboard.alterations.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.alterations.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div>{{ __('Alterations') }}</div>
                </a>
            </li>
        @endcan

        @can(['view tax'])
            <li class="menu-item {{ request()->routeIs('dashboard.taxes.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.taxes.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-receipt-tax"></i>
                    <div>{{ __('Taxes') }}</div>
                </a>
            </li>
        @endcan

        @can(['view insurance'])
            <li class="menu-item {{ request()->routeIs('dashboard.insurances.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.insurances.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-shield-check"></i>
                    <div>{{ __('Insurances') }}</div>
                </a>
            </li>
        @endcan

        @can(['view permit'])
            <li class="menu-item {{ request()->routeIs('dashboard.permits.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.permits.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-file-certificate"></i>
                    <div>{{ __('Permits') }}</div>
                </a>
            </li>
        @endcan

        @can(['view fitness'])
            <li class="menu-item {{ request()->routeIs('dashboard.fitness.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.fitness.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-heart-rate-monitor"></i>
                    <div>{{ __('Fitness Certificates') }}</div>
                </a>
            </li>
        @endcan --}}
        @can(['view billing'])
            <li class="menu-item {{ request()->routeIs('dashboard.billings.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.billings.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-receipt"></i>
                    <div>{{__('Billings')}}</div>
                </a>
            </li>
        @endcan
        @can(['view payment'])
            <li class="menu-item {{ request()->routeIs('dashboard.payments.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.payments.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-coin"></i>
                    <div>{{__('Payments')}}</div>
                </a>
            </li>
        @endcan
        @canany(['view user', 'view archived user', 'view staff'])
            <li
                class="menu-item {{ request()->routeIs('dashboard.user.*') || request()->routeIs('dashboard.archived-user.*') || request()->routeIs('dashboard.other-users.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div>{{ __('Users') }}</div>
                </a>
                <ul class="menu-sub">
                    {{-- @can(['view user'])
                        <li class="menu-item {{ request()->routeIs('dashboard.other-users.*') ? 'active' : '' }}">
                            <a href="{{route('dashboard.other-users.index')}}" class="menu-link">
                                <div>{{__('Users')}}</div>
                            </a>
                        </li>
                    @endcan --}}
                    @can(['view staff'])
                        <li class="menu-item {{ request()->routeIs('dashboard.user.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.user.index') }}" class="menu-link">
                                <div>{{ __('Staff') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can(['view archived user'])
                        <li class="menu-item {{ request()->routeIs('dashboard.archived-user.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.archived-user.index') }}" class="menu-link">
                                <div>{{ __('Archived Users') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @canany(['view role', 'view permission'])
            <li
                class="menu-item {{ request()->routeIs('dashboard.roles.*') || request()->routeIs('dashboard.permissions.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    {{-- <i class="menu-icon tf-icons ti ti-settings"></i> --}}
                    <i class="menu-icon tf-icons ti ti-shield-lock"></i>
                    <div>{{ __('Roles & Permissions') }}</div>
                </a>
                <ul class="menu-sub">
                    @can(['view role'])
                        <li class="menu-item {{ request()->routeIs('dashboard.roles.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.roles.index') }}" class="menu-link">
                                <div>{{ __('Roles') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can(['view permission'])
                        <li class="menu-item {{ request()->routeIs('dashboard.permissions.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.permissions.index') }}" class="menu-link">
                                <div>{{ __('Permissions') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can(['view setting'])
            <li class="menu-item {{ request()->routeIs('dashboard.setting.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.setting.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div>{{ __('Settings') }}</div>
                </a>
            </li>
        @endcan
    </ul>
</aside>
