@extends('layouts.app')
@section('title', 'Edit jadwal')
@section('content')
<form method="POST" action="{{ route('posts.update', $post) }}" class="card" style="max-width:620px">
    @csrf @method('PUT')
    <div class="field">
        <label class="f" for="scheduled_at">Tanggal dan jam terbit</label>
        <input class="in" type="datetime-local" id="scheduled_at" name="scheduled_at"
               value="{{ old('scheduled_at', $post->scheduled_at->format('Y-m-d\TH:i')) }}" min="{{ now()->addMinute()->format('Y-m-d\TH:i') }}" required>
    </div>
    <div class="field">
        <label class="f" for="caption">Caption</label>
        <textarea class="in" id="caption" name="caption" rows="4">{{ old('caption', $post->caption) }}</textarea>
    </div>
    @if($post->targets->contains('platform', 'youtube'))
        <div class="field">
            <label class="f" for="yt_title">Judul YouTube</label>
            <input class="in" id="yt_title" name="yt_title" value="{{ old('yt_title', $post->yt_meta['title'] ?? '') }}" maxlength="100">
        </div>
    @endif
    <div class="field">
        <span class="f">Platform</span>
        <div class="row">
            @foreach($platforms as $key => $p)
                <label class="row" style="gap:6px">
                    <input type="checkbox" name="targets[]" value="{{ $key }}" @checked(in_array($key, old('targets', $post->targets->pluck('platform')->all())))> {{ $p['label'] }}
                </label>
            @endforeach
        </div>
    </div>
    <p class="hint">Jadwal hanya bisa diubah selama belum terbit. Media tidak bisa diganti. Untuk mengganti media, batalkan jadwal lalu buat posting baru.</p>
    <div class="row"><button class="btn primary" type="submit">Simpan perubahan</button><a class="btn" href="{{ route('schedule.index') }}">Kembali</a></div>
</form>
@endsection
