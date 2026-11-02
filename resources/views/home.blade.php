@extends('layouts.main', ['title' => 'Home'])
 
@section('content')
    <div class="welcome-row">
        <div>
            <p class="section-kicker">STOCKWISE</p>
            <h2 class="section-title">Inventory Management</h2>
            <p class="section-description">Choose a menu below to view inventory data.</p>
        </div>
        <span class="date-chip">{{ date('d/m/Y') }}</span>
    </div>
 
    <div class="dashboard-grid">
        {{-- ทุกคนเห็น --}}
        <a class="dashboard-card" href="{{ route('items.list') }}">
            <span class="dashboard-card-label">ITEMS</span>
            <strong>Items</strong>
            <span class="dashboard-card-description">View all items and their stock quantity.</span>
            <span class="dashboard-card-link">View items</span>
        </a>
 
        <a class="dashboard-card" href="{{ route('categories.list') }}">
            <span class="dashboard-card-label">CATEGORIES</span>
            <strong>Categories</strong>
            <span class="dashboard-card-description">View item categories.</span>
            <span class="dashboard-card-link">View categories</span>
        </a>
 
        {{-- เห็นเฉพาะคนที่ login แล้ว --}}
        @auth
            <a class="dashboard-card" href="{{ route('suppliers.list') }}">
                <span class="dashboard-card-label">SUPPLIERS</span>
                <strong>Suppliers</strong>
                <span class="dashboard-card-description">View supplier contact information.</span>
                <span class="dashboard-card-link">View suppliers</span>
            </a>
 
            <a class="dashboard-card dashboard-card-alert" href="{{ route('reports.low-stock') }}">
                <span class="dashboard-card-label">LOW STOCK</span>
                <strong>Low-stock report</strong>
                <span class="dashboard-card-description">Items at or below reorder level. Can download as PDF.</span>
                <span class="dashboard-card-link">View report</span>
            </a>
        @endauth
 
        {{-- เห็นเฉพาะคนที่ยังไม่ login --}}
        @guest
            <a class="dashboard-card" href="{{ route('login') }}">
                <span class="dashboard-card-label">LOGIN</span>
                <strong>Login</strong>
                <span class="dashboard-card-description">Login to view suppliers and the low-stock report.</span>
                <span class="dashboard-card-link">Login</span>
            </a>
        @endguest
    </div>
@endsection
 