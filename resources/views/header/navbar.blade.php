<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top">
    <div class="container-fluid">
        <div class="d-flex ms-auto">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-semibold px-3 py-2 d-flex align-items-center" href="#"
                        sid="profileDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="{{ asset('storage/' . Auth::user()->image) }}"
                            class="rounded-circle border shadow-sm me-2" width="30" height="30" alt="Profile">
                        <span>{{ Auth::user()->name }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3" aria-labelledby="profileDropdown">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile.show', Auth::user()->id) }}">
                                <i class="bi bi-person-circle me-2"></i>View Profile
                            </a>
                        </li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="m-0">
                                @csrf
                                <button type="button" class="dropdown-item py-2 text-danger fw-semibold"
                                    onclick="confirmLogout()">
                                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="bg-dark text-white shadow vh-100 position-fixed top-0 start-0"
    style="width:220px; z-index:1030; overflow-y:auto;">
    <div>
        <h5 class="fw-bold m-0">
            @if (Auth::user()->role === 'admin')
                <a class="navbar-brand d-flex align-items-center justify-content-center fw-bold text-white mt-3">
                    <img src="/vnnovate.png" height="40" width="40" class="me-2 rounded" alt="Vnnovate Logo" />
                    Admin's Panel
                </a>
            @else
                <a class="navbar-brand d-flex align-items-center justify-content-center fw-bold text-white mt-3">
                    <img src="/vnnovate.png" height="40" width="40" class="me-2 rounded" alt="Vnnovate Logo" />
                    User's Panel
                </a>
            @endif
        </h5>
    </div>

    <div class="p-3">
        <ul class="nav flex-column">

            @if (Auth::user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link rounded fw-semibold text-light {{ request()->routeIs('auth.homes', 'auth.registerhome') ? 'active bg-primary' : '' }}"
                        href="{{ route('auth.homes') }}">
                        <i class="bi bi-house"></i> Home
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link px-3 rounded fw-semibold text-light {{ request()->routeIs('auth.registerhome', 'auth.homes') ? 'active bg-primary' : '' }}"
                        href="{{ route('auth.registerhome') }}">
                        <i class="bi bi-house"></i> Home
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link me-3 px-3 rounded fw-semibold text-light {{ request()->routeIs('user.index', 'user.show', 'user.create', 'user.edit') ? 'active bg-primary' : '' }}"
                    href="{{ route('user.index') }}">
                    <i class="bi bi-people-fill"></i> User
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link px-3 rounded fw-semibold text-light {{ request()->routeIs('att.index') ? 'active bg-primary' : '' }}"
                    href="{{ route('att.index') }}">
                    <i class="bi bi-file-earmark-person-fill"></i> Attendance
                </a>
            </li>

            @if (Auth::user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link px-3 rounded fw-semibold text-light {{ request()->routeIs('leave.index') ? 'active bg-primary' : '' }}"
                        href="{{ route('leave.index') }}">
                        <i class="bi bi-calendar2-check-fill"></i> Leaves
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link px-3 rounded fw-semibold text-light {{ request()->routeIs('leave.index') ? 'active bg-primary' : '' }}"
                        href="{{ route('leave.index') }}">
                        <i class="bi bi-calendar2-check-fill"></i> Make Leave
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a class="nav-link me-3 px-3 rounded fw-semibold text-light {{ request()->routeIs('todo.index') ? 'active bg-primary' : '' }}"
                    href="{{ route('todo.index') }}">
                    <i class="bi bi-person-lines-fill"></i> To-Do
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link me-3 rounded fw-semibold text-light {{ request()->routeIs('des.index') ? 'active bg-primary' : '' }}"
                    href="{{ route('des.index') }}">
                    <i class="bi bi-database"></i> Designation
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link me-3 rounded fw-semibold text-light {{ request()->routeIs('dep.index') ? 'active bg-primary' : '' }}"
                    href="{{ route('dep.index') }}">
                    <i class="bi bi-card-list"></i> Department
                </a>
            </li>
        </ul>
    </div>
</div>
