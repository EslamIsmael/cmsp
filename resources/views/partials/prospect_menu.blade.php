<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    {{-- <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ trans('panel.site_title') }}</span>
    </a> --}}

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="{{ $prospect->addedBy->photo ? $prospect->addedBy->photo->url : '' }}" class="img-thumbnail my-4">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if($prospect)
                    <li class="nav-item h1">
                        {{ $prospect->addedBy->name }}
                    </li>
                    <li class="nav-item h2">
                        {{ $prospect->addedBy->phone }}
                    </li>
                    <li class="nav-item h3">
                        {{ $prospect->addedBy->email }}
                    </li>
                @endif
                <li class="nav-item mt-4">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon">

                            </i>
                            <p>{{ trans('global.logout') }}</p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>