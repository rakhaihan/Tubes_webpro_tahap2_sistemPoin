<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Poin')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-" crossorigin="anonymous">
    <style>
        .sidebar .nav-link.active-menu { background-color: #0d3b66; color: #ffffff !important; }
        .sidebar .nav-link.active-menu:hover { color: #ffffff !important; }
    </style>
</head>
<body>
<div class="d-flex">
    <aside class="bg-primary text-white vh-100 p-3 sidebar" style="width:260px;position:fixed;">
        <div class="d-flex flex-column h-100">
            <div>
                <h4 class="text-white">Sistem Poin</h4>
                <hr class="border-light">
            </div>

            <nav class="flex-grow-1">
                <ul class="nav flex-column">
                    @auth
                        @php $role = auth()->user()->role; @endphp
                        <li class="nav-item mb-1"><a class="nav-link text-white {{ request()->is('admin') || request()->is('guru') || request()->is('siswa') ? 'active-menu rounded' : '' }}" href="{{ $role === 'admin' ? url('/admin') : ($role === 'guru' ? url('/guru') : url('/siswa')) }}">Dashboard</a></li>
                        
                        {{-- Admin & Guru: Data Murid --}}
                        @if(in_array($role, ['admin', 'guru']))
                        <li class="nav-item mb-1"><a class="nav-link text-white {{ request()->is('students*') ? 'active-menu rounded' : '' }}" href="{{ route('students.index') }}">Data Murid</a></li>
                        @endif

                        {{-- Admin & Guru: Catatan & Laporan --}}
                        @if(in_array($role, ['admin', 'guru', 'siswa']))
                        <li class="nav-item mb-1"><a class="nav-link text-white {{ request()->is('pelanggaran*') ? 'active-menu rounded' : '' }}" href="{{ route('pelanggaran.index') }}">Catatan & Laporan</a></li>
                        @endif

                        {{-- Admin Only: Sanksi & Pembinaan --}}
                        @if($role === 'admin')
                        <li class="nav-item mb-1"><a class="nav-link text-white {{ request()->is('pembinaan*') ? 'active-menu rounded' : '' }}" href="{{ route('pembinaan.index') }}">Sanksi & Pembinaan</a></li>
                        @endif
                    @endauth
                </ul>
            </nav>
                @auth
                    <div class="small mb-2">{{ auth()->user()->name }} ({{ auth()->user()->username }})</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-light w-100">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light w-100">Login</a>
                @endauth
            </div>
        </div>
    </aside>

    <main style="margin-left:260px;flex:1;padding:24px;">
        @yield('content')
    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-" crossorigin="anonymous"></script>
</body>
</html>
