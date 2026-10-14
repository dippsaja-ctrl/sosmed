@extends('layouts.auth')
@section('title', 'Selamat datang')

@section('content')
<section class="welcome">
    <h1 class="title">WELCOME <span aria-hidden="true">👋</span></h1>
    <p class="welcome__text">
        Hi there!<br>
        Silakan masuk atau buat akun baru untuk mulai belajar.
    </p>

    <div class="welcome__actions">
        <a href="{{ route('login') }}" class="btn">LOG IN</a>
        <a href="{{ route('register') }}" class="btn">CREATE ACCOUNT</a>
    </div>
</section>
@endsection
