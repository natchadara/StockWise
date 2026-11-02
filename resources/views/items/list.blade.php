@extends('layouts.main', ['title' => 'Inventory'])

@section('content')
    <div class="page-intro-row">
        <div>
            <p class="section-kicker">PRODUCT MANAGEMENT</p>
            <h2 class="section-title">Product catalog</h2>
            <p class="section-description">Browse the current catalog and stock availability.</p>
        </div>
        @can('create', \App\Models\Item::class)
            <a class="button" href="{{ route('items.create-form') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add item
            </a>
        @endcan
    </div>

    @if ($items->isEmpty())
        <div class="empty-state">
            <span class="empty-state-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m12 3 9 5-9 5-9-5 9-5Z" />
                    <path d="m3 12 9 5 9-5M3 16l9 5 9-5" />
                </svg>
            </span>
            <strong>No items found</strong>
            <span>There are no items to display right now.</span>
        </div>
    @else
        <div class="table-card">
            <div class="table-toolbar">
                <div class="table-toolbar-heading">
                    <strong>All inventory</strong>
                    <span>{{ $items->total() }} {{ \Illuminate\Support\Str::plural('item', $items->total()) }}</span>
                </div>
                <div class="table-filters">
                    <label class="search-control">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-4-4" />
                        </svg>
                        <span class="visually-hidden">Search inventory</span>
                        <input id="item-search" type="search" placeholder="Search inventory..." autocomplete="off">
                    </label>
                    <label class="filter-control">
                        <span class="visually-hidden">Filter by category</span>
                        <select id="category-filter">
                            <option value="">All categories</option>
                            @foreach ($items->getCollection()->pluck('category.name')->unique()->sort() as $categoryName)
                                <option value="{{ $categoryName }}">{{ $categoryName }}</option>
                            @endforeach
                        </select>
                    </label>
                    @auth
                        <label class="filter-control">
                            <span class="visually-hidden">Filter by stock status</span>
                            <select id="stock-filter">
                                <option value="">All stock levels</option>
                                <option value="in-stock">In stock</option>
                                <option value="low-stock">Low stock</option>
                                <option value="out-of-stock">Out of stock</option>
                            </select>
                        </label>
                    @endauth
                </div>
            </div>

            <div class="table-wrap">
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>ITEM</th>
                            <th>CATEGORY</th>
                            @auth
                                <th>SUPPLIER</th>
                            @endauth
                            <th>QUANTITY</th>
                            @auth
                                <th>STATUS</th>
                            @endauth
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="inventory-rows">
                        @foreach ($items as $item)
                            @php
                                $stockStatus =
                                    $item->quantity === 0
                                        ? 'out-of-stock'
                                        : ($item->quantity <= $item->reorder_level
                                            ? 'low-stock'
                                            : 'in-stock');
                            @endphp
                            <tr data-name="{{ strtolower($item->name . ' ' . $item->sku) }}"
                                data-category="{{ $item->category->name }}"
                                @auth
data-stock-status="{{ $stockStatus }}" @endauth>
                                <td>
                                    <a class="item-name" href="{{ route('items.view', $item) }}">{{ $item->name }}</a>
                                    <span class="item-sku">{{ $item->sku }}</span>
                                </td>
                                <td><span class="category-chip">{{ $item->category->name }}</span></td>
                                @auth
                                    <td>{{ $item->supplier->name }}</td>
                                @endauth
                                <td class="quantity-cell">{{ number_format($item->quantity) }}</td>
                                @auth
                                    <td>
                                        <span class="status-badge status-{{ $stockStatus }}">
                                            <span class="status-dot"></span>
                                            {{ match ($stockStatus) {
                                                'out-of-stock' => 'Out of stock',
                                                'low-stock' => 'Low stock',
                                                default => 'In stock',
                                            } }}
                                        </span>
                                    </td>
                                @endauth
                                <td class="table-action-cell">
                                    <a class="table-action" href="{{ route('items.view', $item) }}"
                                        aria-label="View {{ $item->name }}">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="m9 18 6-6-6-6" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="filter-empty" id="filter-empty" hidden>No items match these filters.</p>
            <div class="table-pagination">{{ $items->links() }}</div>
        </div>
    @endif

    @if (!$items->isEmpty())
        <script>
            const searchInput = document.querySelector('#item-search');
            const categoryFilter = document.querySelector('#category-filter');
            const stockFilter = document.querySelector('#stock-filter');
            const inventoryRows = [...document.querySelectorAll('#inventory-rows tr')];
            const filterEmpty = document.querySelector('#filter-empty');

            function filterInventory() {
                const searchTerm = searchInput.value.trim().toLowerCase();
                const category = categoryFilter.value.toLowerCase();
                const stockStatus = stockFilter ? stockFilter.value : '';
                let visibleRows = 0;

                for (const row of inventoryRows) {
                    const matchesSearch = row.dataset.name.includes(searchTerm);
                    const matchesCategory = !category || row.dataset.category.toLowerCase() === category;
                    const matchesStock = !stockStatus || row.dataset.stockStatus === stockStatus;
                    const isVisible = matchesSearch && matchesCategory && matchesStock;

                    row.hidden = !isVisible;
                    visibleRows += Number(isVisible);
                }

                filterEmpty.hidden = visibleRows > 0;
            }

            searchInput.addEventListener('input', filterInventory);
            categoryFilter.addEventListener('change', filterInventory);
            if (stockFilter) {
                stockFilter.addEventListener('change', filterInventory);
            }
        </script>
    @endif
@endsection
