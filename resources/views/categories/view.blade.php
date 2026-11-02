@extends('layouts.main', ['title' => $category->name])

@section('content')
    <div class="detail-card">
        <dl class="detail-list">
            <div><dt>Category code</dt><dd>{{ $category->code }}</dd></div>
            <div><dt>Description</dt><dd>{{ $category->description ?: 'No description provided.' }}</dd></div>
            <div><dt>Items in category</dt><dd>{{ $category->items_count }}</dd></div>
        </dl>

        <div class="action-row">
            <a class="button button-secondary" href="{{ route('categories.list') }}">Back to categories</a>
            @can('update', $category)
                <a class="button" href="{{ route('categories.update-form', $category) }}">Edit category</a>
            @endcan
            @can('delete', $category)
                <form method="POST" action="{{ route('categories.delete', $category) }}"
                    onsubmit="return confirm('Delete this category? Categories with items cannot be deleted.');">
                    @csrf
                    <button class="button button-danger" type="submit">Delete category</button>
                </form>
            @endcan
        </div>
    </div>
@endsection
