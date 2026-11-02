@extends('layouts.main', ['title' => 'Items'])

@section('content')
    <div class="content-toolbar">
        <div>
            <p class="section-kicker">ITEMS</p>
            <p class="section-description">All items and their stock quantity.</p>
        </div>
        @can('create', \App\Models\Item::class)
            <a class="button" href="{{ route('items.create-form') }}">Add item</a>
        @endcan
    </div>

    @if ($items->isEmpty())
        <div class="empty-state">No items have been added yet.</div>
    @else
        <div class="table-card table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>ITEM</th>
                        <th>CATEGORY</th>
                        @auth
                            <th>SUPPLIER</th>
                        @endauth
                        <th>QUANTITY</th>
                        @auth
                            <th>STATUS</th>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td><a href="{{ route('items.view', $item) }}">{{ $item->sku }}</a></td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category->name }}</td>
                            @auth
                                <td>{{ $item->supplier->name }}</td>
                            @endauth
                            <td>{{ $item->quantity }}</td>
                            @auth
                                <td>
                                    {{-- สถานะสต็อก: หมด / ใกล้หมด / ปกติ --}}
                                    @if ($item->quantity == 0)
                                        <span class="status-badge status-out-of-stock">Out of stock</span>
                                    @elseif ($item->quantity <= $item->reorder_level)
                                        <span class="status-badge status-low-stock">Low stock</span>
                                    @else
                                        <span class="status-badge status-in-stock">In stock</span>
                                    @endif
                                </td>
                            @endauth
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $items->links() }}
    @endif
@endsection
