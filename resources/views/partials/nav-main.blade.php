<ul class="nav flex-column quillsigner-sidebar-menu">
    <li class="nav-item{{ ($menuItem == 'home') ? ' quillsigner-active-item' : '' }}">
        <a class="nav-link" href="{{ route('get-home') }}">Home</a>
    </li>
    <li class="nav-item{{ ($menuItem == 'docs_tools') ? ' quillsigner-active-item' : '' }}">
        <a class="nav-link" href="{{ route('get-docs-tools') }}">Documents</a>
    </li>
    <li class="nav-item{{ ($menuItem == 'my_sigs') ? ' quillsigner-active-item' : '' }}">
        <a class="nav-link" href="{{ route('get-signatures') }}">Signatures</a>
    </li>
    <li class="nav-item{{ ($menuItem == 'profile') ? ' quillsigner-active-item' : '' }}">
        <a class="nav-link" href="{{ route('get-profile') }}">Profile</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}">Logout</a>
    </li>
</ul>