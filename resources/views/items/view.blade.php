@extends('layouts.main', ['title' => $item->name])

@section('content')
    <div class="detail-card">
        <dl class="detail-list">
            <div><dt>SKU</dt><dd>{{ $item->sku }}</dd></div>
            <div><dt>Category</dt><dd><a href="{{ route('categories.view', $item->category) }}">{{ $item->category->name }}</a></dd></div>
            <div><dt>Quantity in stock</dt><dd>{{ $item->quantity }}</dd></div>
            <div><dt>Price</dt><dd>{{ number_format((float) $item->price, 2) }}</dd></div>
            @auth
                <div><dt>Reorder level</dt><dd>{{ $item->reorder_level }}</dd></div>
                <div><dt>Supplier</dt><dd><a href="{{ route('suppliers.view', $item->supplier) }}">{{ $item->supplier->name }}</a></dd></div>
            @endauth
        </dl>

        <div class="action-row">
            <a class="button button-secondary" href="{{ route('items.list') }}">Back to items</a>
            @can('update', $item)
                <a class="button" href="{{ route('items.update-form', $item) }}">Update item</a>
            @endcan
            @can('delete', $item)
                <form method="POST" action="{{ route('items.delete', $item) }}"
                    onsubmit="return confirm('Delete this item?');">
                    @csrf
                    <button class="button button-danger" type="submit">Delete item</button>
                </form>
            @endcan
        </div>
    </div>
@endsection
