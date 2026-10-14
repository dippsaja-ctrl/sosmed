@extends('layouts.auth')
@section('title', 'Buat akun')

@section('content')
<a href="{{ route('welcome') }}" class="back">&lsaquo; Back</a>

<section class="card">
    <h1 class="title title--dark">Create your Account</h1>
    <p class="subtitle">
        Kami siap membantumu mencapai puncak belajar.<br>
        <strong>Are you ready?</strong>
    </p>

    <form method="POST" action="{{ route('register.store') }}" novalidate>
        @csrf

        <div class="field">
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   placeholder="Nama lengkap" autocomplete="name" required autofocus
                   @class(['is-invalid' => $errors->has('name')])>
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   placeholder="nama@gmail.com" autocomplete="email" required
                   @class(['is-invalid' => $errors->has('email')])>
            @error('email') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <div class="password">
                <input id="password" type="password" name="password"
                       placeholder="Minimal 8 karakter" autocomplete="new-password" required
                       @class(['is-invalid' => $errors->has('password')])>
                <button type="button" class="password__toggle" data-toggle="password" aria-label="Tampilkan password">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn btn--block">GET STARTED</button>
    </form>

    @include('partials.social')

    <p class="switch">Sudah punya akun? <a href="{{ route('login') }}" class="link">Log in</a></p>
</section>

@include('partials.toggle-script')
@endsection
