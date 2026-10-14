@extends('layouts.auth')
@section('title', 'Dashboard')

@section('content')
<section class="welcome">
    <h1 class="title">Halo, {{ auth()->user()->name }}!</h1>
    <p class="welcome__text">Kamu berhasil masuk.</p>
    <form method="POST" action="{{ route('logout') }}" class="welcome__actions">
        @csrf
        <button type="submit" class="btn">LOG OUT</button>
    </form>
</section>
@endsection
