@extends('layouts.main', ['title' => 'Low-stock report'])

@section('content')
    <div class="content-toolbar">
        <div>
            <p class="section-kicker">INVENTORY HEALTH</p>
            <p class="section-description">Items are low on stock when their quantity is at or below the reorder level.</p>
        </div>
        <a class="button" href="{{ route('reports.low-stock.pdf') }}">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" /></svg>
            Download PDF
        </a>
    </div>

    @include('reports._table')
@endsection
