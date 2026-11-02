<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockWise - {{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/home.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/table.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/form.css') }}" />
</head>

<body>
    <div class="app-shell">

        {{-- แถบเมนูด้านซ้าย --}}
        <aside class="sidebar">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark">SW</span>
                <span class="brand-copy">
                    <strong>StockWise</strong>
                    <small>INVENTORY</small>
                </span>
            </a>

            <p class="sidebar-section-label">MENU</p>
            <nav class="sidebar-nav">
                <a @class(['sidebar-link', 'active' => Route::is('home')]) href="{{ route('home') }}">Home</a>
                <a @class(['sidebar-link', 'active' => Route::is('items.*')]) href="{{ route('items.list') }}">Items</a>
                <a @class(['sidebar-link', 'active' => Route::is('categories.*')]) href="{{ route('categories.list') }}">Categories</a>
                @auth
                    <a @class(['sidebar-link', 'active' => Route::is('suppliers.*')]) href="{{ route('suppliers.list') }}">Suppliers</a>
                    <a @class(['sidebar-link', 'active' => Route::is('reports.*')]) href="{{ route('reports.low-stock') }}">Low stock</a>
                @endauth
            </nav>

            <div class="sidebar-bottom">
                <div class="sidebar-divider"></div>

                @auth
                    <form action="{{ route('logout') }}" method="post" id="app-form-logout">
                        @csrf
                    </form>

                    <div class="profile-card">
                        <span class="avatar">{{ mb_substr(\Auth::user()->name, 0, 1) }}</span>
                        <span class="profile-copy">
                            <strong>{{ \Auth::user()->name }}</strong>
                            <small>{{ \Auth::user()->role }}</small>
                        </span>
                    </div>
                    <button class="button button-secondary button-small" type="submit"
                        form="app-form-logout">Logout</button>
                @endauth

                @guest
                    <a @class(['sidebar-link', 'active' => Route::is('login')]) href="{{ route('login') }}">Login</a>
                @endguest
            </div>
        </aside>

        {{-- ส่วนเนื้อหาด้านขวา --}}
        <div class="main-panel">
            <header class="topbar">
                <div class="topbar-heading">
                    <span class="topbar-eyebrow">STOCKWISE</span>
                    <h1>{{ $title }}</h1>
                </div>

            </header>

            <main class="page-shell">
                <div class="page-content">
                    {{-- แจ้งเตือนจาก session (สำเร็จ / ผิดพลาด) --}}
                    <div class="notifications">
                        @session('status')
                            <div class="flash flash-success" role="status">{{ $value }}</div>
                        @endsession

                        @error('alert')
                            <div class="flash flash-error" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    @yield('content')
                </div>
            </main>

            <footer class="site-footer">
                <span>&#xA9; StockWise Inventory</span>
                <span>Term Project 1/2569</span>
            </footer>
        </div>

    </div>
</body>

</html>
