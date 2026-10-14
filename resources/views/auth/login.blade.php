@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
<a href="{{ route('welcome') }}" class="back">&lsaquo; Back</a>

<section class="card">
    <h1 class="title title--dark">Welcome Back</h1>
    <p class="subtitle">
        Lanjutkan perjalanan belajarmu.<br>
        <em>Jalanmu sudah menunggu.</em>
    </p>

    <form method="POST" action="{{ route('login.store') }}" novalidate>
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   placeholder="nama@gmail.com" autocomplete="email" required autofocus
                   @class(['is-invalid' => $errors->has('email')])>
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <div class="password">
                <input id="password" type="password" name="password"
                       placeholder="••••••••" autocomplete="current-password" required
                       @class(['is-invalid' => $errors->has('password')])>
                <button type="button" class="password__toggle" data-toggle="password" aria-label="Tampilkan password">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="row">
            <label class="check">
                <input type="checkbox" name="remember" value="1"> Remember me
            </label>
            <a href="#" class="link">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn--block">LOG IN</button>
    </form>

    @include('partials.social')

    <p class="switch">Belum punya akun? <a href="{{ route('register') }}" class="link">Create account</a></p>
</section>

@include('partials.toggle-script')
@endsection
