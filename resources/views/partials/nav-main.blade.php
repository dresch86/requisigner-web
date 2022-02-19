<ul class="nav flex-column requisigner-sidebar-menu">
    <li class="nav-item{{ ($menuItem == 'home') ? ' requisigner-active-item' : '' }}">
        <a class="nav-link" href="{{ route('get-home') }}">Home</a>
    </li>
    @if (auth()->user()->superadmin == 1)
    <li class="nav-item{{ ($menuItem == 'admin_tools') ? ' requisigner-active-item' : '' }}">
        <a class="nav-link" href="{{ route('get-admin-tools') }}">Admin</a>
    </li>
    @endif
    <li class="nav-item{{ ($menuItem == 'docs_tools') ? ' requisigner-active-item' : '' }}">
        <a class="nav-link" href="{{ route('get-docs-tools') }}">Documents</a>
    </li>
    <li class="nav-item{{ ($menuItem == 'my_sigs') ? ' requisigner-active-item' : '' }}">
        <a class="nav-link" href="{{ route('get-signatures') }}">Signatures</a>
    </li>
    <li class="nav-item{{ ($menuItem == 'profile') ? ' requisigner-active-item' : '' }}">
        <a class="nav-link" href="{{ route('get-profile') }}">Profile</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}">Logout</a>
    </li>
</ul>