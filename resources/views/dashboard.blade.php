@extends('layouts.auth')
@section('title', 'Dashboard')

@section('content')
    <h1 class="title">HELLO, {{ strtoupper(auth()->user()->name) }} 👋</h1>
    <p class="lead">Kamu berhasil masuk. Selamat belajar!</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn">LOG OUT</button>
    </form>
@endsection
