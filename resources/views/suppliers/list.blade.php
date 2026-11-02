@extends('layouts.main', ['title' => 'Suppliers'])

@section('content')
    <div class="content-toolbar">
        <div>
            <p class="section-kicker">SUPPLY PARTNERS</p>
            <p class="section-description">Supplier details are available to signed-in staff.</p>
        </div>
        @can('create', \App\Models\Supplier::class)
            <a class="button" href="{{ route('suppliers.create-form') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14" /></svg>
                Add supplier
            </a>
        @endcan
    </div>

    @if ($suppliers->isEmpty())
        <div class="empty-state">No suppliers have been added yet.</div>
    @else
        <div class="table-card table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>CODE</th>
                        <th>SUPPLIER</th>
                        <th>PHONE</th>
                        <th>EMAIL</th>
                        <th>ITEMS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td><a href="{{ route('suppliers.view', $supplier) }}">{{ $supplier->code }}</a></td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->phone }}</td>
                            <td><a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a></td>
                            <td>{{ $supplier->items_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $suppliers->links() }}
    @endif
@endsection
