@extends('layouts.main', ['title' => 'Categories'])

@section('content')
    <div class="content-toolbar">
        <div>
            <p class="section-kicker">INVENTORY ORGANIZATION</p>
            <p class="section-description">Browse the categories used to organize inventory.</p>
        </div>
        @can('create', \App\Models\Category::class)
            <a class="button" href="{{ route('categories.create-form') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14" /></svg>
                Add category
            </a>
        @endcan
    </div>

    @if ($categories->isEmpty())
        <div class="empty-state">No categories have been added yet.</div>
    @else
        <div class="table-card table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>CODE</th>
                        <th>CATEGORY</th>
                        <th>DESCRIPTION</th>
                        <th>ITEMS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td><a href="{{ route('categories.view', $category) }}">{{ $category->code }}</a></td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->description ?: '—' }}</td>
                            <td>{{ $category->items_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $categories->links() }}
    @endif
@endsection
