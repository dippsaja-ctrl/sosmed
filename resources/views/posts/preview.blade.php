@extends('layouts.app')
@section('title', 'Pratinjau sebelum ' . ($post->mode === 'now' ? 'posting' : 'dijadwalkan'))
@section('content')
<section class="card">
    <h3>Ringkasan</h3>
    <p>
        @if($post->mode === 'now')
            Akan dikirim <b>sekarang</b> ke:
        @else
            Akan terbit <b>{{ $post->scheduled_at->translatedFormat('l, j F Y H:i') }}</b> di:
        @endif
        @foreach($post->targets as $t)@include('partials.platform-chip', ['key' => $t->platform]) @endforeach
    </p>
</section>

<div class="grid2">
    @foreach($post->targets as $t)
        @include('partials.preview-card', ['post' => $post, 'platform' => $t->platform, 'accounts' => $accounts])
    @endforeach
</div>

<div class="row" style="margin-top:16px">
    <form method="POST" action="{{ route('posts.confirm', $post) }}">@csrf
        <button class="btn primary" type="submit">{{ $post->mode === 'now' ? 'Posting sekarang' : 'Jadwalkan' }}</button>
    </form>
    <form method="POST" action="{{ route('posts.discard', $post) }}" onsubmit="return confirm('Buang draf ini?')">@csrf @method('DELETE')
        <button class="btn" type="submit">Buang dan edit ulang</button>
    </form>
</div>
@endsection
