@extends('layouts.main', ['title' => 'Update item'])
 
@section('content')
    <div class="form-card">
        <form action="{{ route('items.update', $item) }}" method="post" class="stacked-form">
            @csrf
 
            <label for="sku">SKU</label>
            <input id="sku" name="sku" value="{{ old('sku', $item->sku) }}" maxlength="64" required>
            @error('sku')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="name">Name</label>
            <input id="name" name="name" value="{{ old('name', $item->name) }}" maxlength="255" required>
            @error('name')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" required>
                <option value="">Select a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $item->category_id) == $category->id)>
                        {{ $category->name }} ({{ $category->code }})
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="supplier_id">Supplier</label>
            <select id="supplier_id" name="supplier_id" required>
                <option value="">Select a supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected(old('supplier_id', $item->supplier_id) == $supplier->id)>
                        {{ $supplier->name }} ({{ $supplier->code }})
                    </option>
                @endforeach
            </select>
            @error('supplier_id')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="quantity">Quantity</label>
            <input id="quantity" name="quantity" type="number" min="0" step="1" value="{{ old('quantity', $item->quantity) }}" required>
            @error('quantity')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="reorder_level">Reorder level</label>
            <input id="reorder_level" name="reorder_level" type="number" min="0" step="1" value="{{ old('reorder_level', $item->reorder_level) }}" required>
            @error('reorder_level')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <label for="price">Price</label>
            <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $item->price) }}" required>
            @error('price')
                <span class="field-error">{{ $message }}</span>
            @enderror
 
            <div class="action-row">
                <button class="button" type="submit">Save changes</button>
                <a class="button button-secondary" href="{{ route('items.list') }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
 