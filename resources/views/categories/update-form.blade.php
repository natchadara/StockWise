@extends('layouts.main', ['title' => 'Update category'])
 
@section('content')
    <div class="form-card">
        <form action="{{ route('categories.update', $category) }}" method="post" class="stacked-form">
            @csrf
 
            <label for="code">Category code</label>
            <input id="code" name="code" value="{{ old('code', $category->code) }}" maxlength="64" required>
            @error('code')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="name">Name</label>
            <input id="name" name="name" value="{{ old('name', $category->name) }}" maxlength="255" required>
            @error('name')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" maxlength="2000">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <div class="action-row">
                <button class="button" type="submit">Save changes</button>
                <a class="button button-secondary" href="{{ route('categories.list') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
 