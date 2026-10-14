@extends('layouts.auth')
@section('title', 'Welcome')

@section('content')
    <h1 class="title">WELCOME <span class="wave">👋</span></h1>
    <p class="lead">Hi there!<br>the choice is yours, Login or create an account.</p>

    <a class="btn" href="{{ route('login') }}">LOG IN</a>
    <a class="btn" href="{{ route('register') }}">CREATE ACCOUNT</a>
@endsection
