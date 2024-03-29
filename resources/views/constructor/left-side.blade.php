<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('constructor_language_index') }}" class="brand-link">
        <span class="brand-text font-weight-light">
            &nbsp;<i class="fas fa-laptop-code"></i>&nbsp;&nbsp;&nbsp;
            Page Constructor
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block">Oleksii Popov</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-header">Core</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i>&nbsp;
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('constructor_language_index') }}" class="nav-link {{ route('constructor_language_index', [], false) === '/' . request()->path() ? 'active' : ''}}">
                        <i class="fas fa-language"></i>&nbsp;
                        <p>Languages</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-cog"></i>&nbsp;
                        <p>Settings</p>
                    </a>
                </li>

                <li class="nav-header">Content</li>

                <li class="nav-item">
                    <a href="{{ route('constructor_page_index') }}" class="nav-link {{ route('constructor_page_index', [], false) === '/' . request()->path() ? 'active' : ''}}">
                        <i class="fas fa-cubes"></i>&nbsp;
                        <p>Page Types</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('constructor_seo_index') }}" class="nav-link {{ route('constructor_seo_index', [], false) === '/' . request()->path() ? 'active' : ''}}">
                        <i class="fas fa-link"></i>&nbsp;
                        <p>SEO</p>
                    </a>
                </li>

                <li class="nav-header">Visual</li>


                <li class="nav-item">
                    <a href="{{ route('constructor_template_index') }}" class="nav-link {{ route('constructor_template_index', [], false) === '/' . request()->path() ? 'active' : ''}}">
                        <i class="fas fa-paint-roller"></i>&nbsp;
                        <p>Themes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('constructor_localization_index') }}" class="nav-link {{ route('constructor_localization_index', [], false) === '/' . request()->path() ? 'active' : ''}}">
                        <i class="fas fa-language"></i>&nbsp;
                        <p>Localization</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>