<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SocialAccount;

class DashboardController extends Controller
{
    public function index()
    {
        $uid = auth()->id();
        $platforms = config('posting.platforms');
        $accounts = SocialAccount::where('user_id', $uid)->get()->keyBy('platform');

        $attention = [];
        foreach ($platforms as $key => $p) {
            $a = $accounts[$key] ?? null;
            if (! $a) $attention[] = ['warn', $p['label'].' belum terhubung.', route('accounts.index'), 'Hubungkan'];
            elseif ($a->isExpired()) $attention[] = ['bad', 'Token '.$p['label'].' sudah kedaluwarsa.', route('accounts.index'), 'Perbaiki'];
        }
        $failed = Post::with('targets')->where('user_id', $uid)->where('state', 'done')
            ->whereHas('targets', fn ($q) => $q->where('status', 'failed'))->latest()->limit(5)->get();
        foreach ($failed as $post) {
            $names = $post->targets->where('status', 'failed')->pluck('platform')->map(fn ($k) => $platforms[$k]['label'])->implode(', ');
            $attention[] = ['bad', 'Posting "'.mb_substr($post->title(), 0, 36).'" gagal di '.$names.'.', route('history.index', ['f' => 'bad']), 'Coba lagi'];
        }

        $next = Post::with('targets')->where('user_id', $uid)->where('state', 'scheduled')->orderBy('scheduled_at')->limit(3)->get();
        $recent = Post::with('targets')->where('user_id', $uid)->whereIn('state', ['processing', 'done'])->latest()->limit(3)->get();

        return view('dashboard', compact('platforms', 'accounts', 'attention', 'next', 'recent'));
    }
}
