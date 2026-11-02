@extends('layouts.main', ['title' => 'Create supplier'])
 
@section('content')
    <div class="form-card">
        <form action="{{ route('suppliers.create') }}" method="post" class="stacked-form">
            @csrf
 
            <label for="code">Supplier code</label>
            <input id="code" name="code" value="{{ old('code') }}" maxlength="64" required>
            @error('code')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="name">Name</label>
            <input id="name" name="name" value="{{ old('name') }}" maxlength="255" required>
            @error('name')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="phone">Phone</label>
            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" maxlength="32" required>
            @error('phone')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255" required>
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <div class="action-row">
                <button class="button" type="submit">Create supplier</button>
                <a class="button button-secondary" href="{{ route('suppliers.list') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
 