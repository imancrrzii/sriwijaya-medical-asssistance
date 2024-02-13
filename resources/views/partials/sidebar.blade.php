<div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" src="{{ asset('assets/images/TBMS.png') }}" alt="logo">
                <img class="logo-dark logo-img" src="{{ asset('assets/images/TBMS.png') }}" alt="logo-dark">
                <img class="logo-small logo-img logo-img-small" src="{{ asset('assets/images/TBMS.png') }}"
                    alt="logo-small">
            </a>
        </div>
        <div class="nk-menu-trigger me-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em
                    class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex"
                data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
    </div>
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    @can('admin-monitoring-all')
                        <li class="nk-menu-item">
                            <a href="{{ route('dashboard.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                                <span class="nk-menu-text">Dashboard</span>
                            </a>
                        </li>
                    @endcan
                    @can('admin-table')
                        <li class="nk-menu-item">
                            <a href="{{ route('patient.index', substr(Auth::user()->role, -1)) }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-plus-medi-fill"></em></span>
                                <span class="nk-menu-text">Pasien</span>
                            </a>
                        </li>
                    @endcan
                    @can('admin-monitoring-all')
                        <li class="nk-menu-item has-sub">
                            <a href="{{ route('patient.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-plus-medi-fill"></em></span>
                                <span class="nk-menu-text">Pasien</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </div>
    </div>
</div>
