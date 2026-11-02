@extends('layouts.main', ['title' => $supplier->name])

@section('content')
    <div class="detail-card">
        <dl class="detail-list">
            <div><dt>Supplier code</dt><dd>{{ $supplier->code }}</dd></div>
            <div><dt>Phone</dt><dd>{{ $supplier->phone }}</dd></div>
            <div><dt>Email</dt><dd><a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a></dd></div>
            <div><dt>Items supplied</dt><dd>{{ $supplier->items_count }}</dd></div>
        </dl>

        <div class="action-row">
            <a class="button button-secondary" href="{{ route('suppliers.list') }}">Back to suppliers</a>
            @can('update', $supplier)
                <a class="button" href="{{ route('suppliers.update-form', $supplier) }}">Edit supplier</a>
            @endcan
            @can('delete', $supplier)
                <form method="POST" action="{{ route('suppliers.delete', $supplier) }}"
                    onsubmit="return confirm('Delete this supplier? Suppliers with items cannot be deleted.');">
                    @csrf
                    <button class="button button-danger" type="submit">Delete supplier</button>
                </form>
            @endcan
        </div>
    </div>
@endsection
