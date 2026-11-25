<aside class="sidebar p-3 d-flex flex-column">
    <a href="dashboard.html" class="brand mb-4"><i class="bi bi-wallet2"></i><span>Expense<br>Tracker</span></a>
    <ul class="nav flex-column gap-1 flex-grow-1">
        <li><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i
                    class="bi bi-grid"></i>Dashboard</a></li>
        <li><a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }} "
                href="{{ route('categories.index') }}"><i class="bi bi-tags"></i>Categories</a></li>
    </ul>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="nav-link text-danger d-flex gap-2"><i class="bi bi-box-arrow-left"></i>Logout</button>
    </form>
</aside>
