@extends('layouts.main', ['title' => 'Create category'])
 
@section('content')
    <div class="form-card">
        <form action="{{ route('categories.create') }}" method="post" class="stacked-form">
            @csrf
 
            <label for="code">Category code</label>
            <input id="code" name="code" value="{{ old('code') }}" maxlength="64" required>
            @error('code')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="name">Name</label>
            <input id="name" name="name" value="{{ old('name') }}" maxlength="255" required>
            @error('name')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" maxlength="2000">{{ old('description') }}</textarea>
            @error('description')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <div class="action-row">
                <button class="button" type="submit">Create category</button>
                <a class="button button-secondary" href="{{ route('categories.list') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
 