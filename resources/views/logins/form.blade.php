@extends('layouts.main', ['title' => 'Staff login'])

@section('content')
    <div class="form-card narrow-card">
        <p class="muted">Sign in with your StockWise staff account.</p>

        <form method="POST" action="{{ route('authentication') }}" class="stacked-form">
            @csrf

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required>
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror

            <button class="button" type="submit">Login</button>
        </form>
    </div>
@endsection
