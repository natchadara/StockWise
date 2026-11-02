@extends('layouts.main', ['title' => 'Update supplier'])
 
@section('content')
    <div class="form-card">
        <form action="{{ route('suppliers.update', $supplier) }}" method="post" class="stacked-form">
            @csrf
 
            <label for="code">Supplier code</label>
            <input id="code" name="code" value="{{ old('code', $supplier->code) }}" maxlength="64" required>
            @error('code')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="name">Name</label>
            <input id="name" name="name" value="{{ old('name', $supplier->name) }}" maxlength="255" required>
            @error('name')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="phone">Phone</label>
            <input id="phone" name="phone" type="tel" value="{{ old('phone', $supplier->phone) }}" maxlength="32" required>
            @error('phone')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $supplier->email) }}" maxlength="255" required>
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <div class="action-row">
                <button class="button" type="submit">Save changes</button>
                <a class="button button-secondary" href="{{ route('suppliers.list') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
 