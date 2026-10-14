@extends('layouts.auth')
@section('title', 'Log in')

@section('content')
    <h1 class="title">Welcome Back</h1>
    <p class="lead">Ready to continue your learning journey?<br>Your path is right here</p>

    <form method="POST" action="{{ route('login') }}" class="form" novalidate>
        @csrf

        <label for="email">Email:</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}"
               placeholder="name@gmail.com" autocomplete="email" required autofocus>
        @error('email') <p class="error">{{ $message }}</p> @enderror

        <label for="password">Password:</label>
        <div class="password">
            <input id="password" type="password" name="password" autocomplete="current-password" required>
            <button type="button" class="eye" data-toggle="password" aria-label="Tampilkan password">👁</button>
        </div>
        @error('password') <p class="error">{{ $message }}</p> @enderror

        <div class="row">
            <label class="check"><input type="checkbox" name="remember"> Remember me</label>
            <a href="#" class="link">Forgot password?</a>
        </div>

        <button type="submit" class="btn">LOG IN</button>
    </form>

    @include('auth._social')

    <p class="switch">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
    <script src="{{ asset('js/auth.js') }}"></script>
@endsection
