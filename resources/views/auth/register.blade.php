@extends('layouts.auth')
@section('title', 'Create account')

@section('content')
    <h1 class="title">Create your Account</h1>
    <p class="lead">We're here to help you reach the peaks of learning.<br><strong>Are you ready?</strong></p>

    <form method="POST" action="{{ route('register') }}" class="form" novalidate>
        @csrf

        <label for="name">Name:</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}"
               placeholder="full name" autocomplete="name" required autofocus>
        @error('name') <p class="error">{{ $message }}</p> @enderror

        <label for="email">Email:</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}"
               placeholder="name@gmail.com" autocomplete="email" required>
        @error('email') <p class="error">{{ $message }}</p> @enderror

        <label for="password">Password:</label>
        <div class="password">
            <input id="password" type="password" name="password" placeholder="minimal 8 karakter"
                   autocomplete="new-password" required>
            <button type="button" class="eye" data-toggle="password" aria-label="Tampilkan password">👁</button>
        </div>
        @error('password') <p class="error">{{ $message }}</p> @enderror

        <button type="submit" class="btn">GET STARTED</button>
    </form>

    @include('auth._social')

    <p class="switch">Sudah punya akun? <a href="{{ route('login') }}">Log in</a></p>
    <script src="{{ asset('js/auth.js') }}"></script>
@endsection
