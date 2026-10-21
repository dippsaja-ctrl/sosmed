<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\CarbonPeriod;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $uid = auth()->id();
        $view = $request->query('view') === 'cal' ? 'cal' : 'list';
        $keyword = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $base = Post::with('targets')
            ->where('user_id', $uid)
            ->where('mode', 'schedule')
            ->whereNotIn('state', ['draft']);

        if (mb_strlen($keyword) >= 2) {
            $base->where(function ($query) use ($keyword) {
                $query->where('caption', 'like', "%{$keyword}%")
                    ->orWhereHas('targets', function ($targets) use ($keyword) {
                        $targets->where('platform', 'like', "%{$keyword}%");
                    });
            });
        }

        if (in_array($status, ['scheduled', 'processing', 'done', 'canceled'], true)) {
            $base->where('state', $status);
        }

        if ($view === 'list') {
            $upcoming = (clone $base)
                ->whereIn('state', ['scheduled', 'processing'])
                ->orderBy('scheduled_at')
                ->get();

            $past = (clone $base)
                ->whereIn('state', ['done', 'canceled'])
                ->orderByDesc('scheduled_at')
                ->limit(30)
                ->get();

            return view('schedule.index', compact(
                'view',
                'upcoming',
                'past',
                'keyword',
                'status'
            ));
        }

        try {
            $month = Carbon::createFromFormat(
                'Y-m-d',
                ($request->query('m', now()->format('Y-m'))) . '-01'
            )->startOfDay();
        } catch (\Throwable) {
            $month = now()->startOfMonth();
        }

        $start = $month->copy()->startOfWeek(Carbon::MONDAY);
        $end = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $byDay = (clone $base)
            ->whereBetween('scheduled_at', [$start, $end])
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy(fn ($post) => $post->scheduled_at?->format('Y-m-d'));

        $days = CarbonPeriod::create($start, $end);
        $selected = $request->query('d', now()->format('Y-m-d'));

        return view('schedule.index', [
            'view' => $view,
            'month' => $month,
            'days' => $days,
            'byDay' => $byDay,
            'selected' => $selected,
            'prev' => $month->copy()->subMonth()->format('Y-m'),
            'next' => $month->copy()->addMonth()->format('Y-m'),
            'keyword' => $keyword,
            'status' => $status,
        ]);
    }
}
