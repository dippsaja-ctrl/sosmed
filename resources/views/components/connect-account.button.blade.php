@props([
    'platform',            // facebook | instagram | tiktok | youtube
    'href' => '#',
    'connected' => false,
])

@php
    $platform = strtolower($platform);

    $themes = [
        'facebook'  => ['label' => 'Facebook',  'class' => 'bg-[#1877F2] hover:bg-[#1468d6] focus-visible:ring-[#1877F2]'],
        'instagram' => ['label' => 'Instagram', 'class' => 'bg-gradient-to-tr from-yellow-500 via-pink-600 to-purple-700 hover:brightness-110 focus-visible:ring-pink-500'],
        'tiktok'    => ['label' => 'TikTok',    'class' => 'bg-black hover:bg-neutral-800 focus-visible:ring-neutral-900 dark:ring-1 dark:ring-white/20'],
        'youtube'   => ['label' => 'YouTube',   'class' => 'bg-[#e00000] hover:bg-[#c40000] focus-visible:ring-red-600'],
    ];

    $theme = $themes[$platform] ?? ['label' => ucfirst($platform), 'class' => 'bg-gray-800 hover:bg-gray-700 focus-visible:ring-gray-800'];
@endphp

<a href="{{ $href }}"
   {{ $attributes->class([
        'group inline-flex w-full items-center justify-between gap-3 rounded-xl px-4 py-3',
        'text-sm font-semibold text-white shadow-sm transition',
        'focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-800',
        $theme['class'],
   ]) }}>
    <span class="flex items-center gap-3">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/15">
            <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                @switch($platform)
                    @case('facebook')
                        <path fill="currentColor" d="M13.5 22v-8h2.7l.4-3.2h-3.1V8.9c0-.9.3-1.5 1.6-1.5h1.7V4.5c-.3 0-1.3-.1-2.4-.1-2.4 0-4.1 1.5-4.1 4.2v2.4H7.5V14h2.8v8h3.2z"/>
                        @break
                    @case('instagram')
                        <rect x="3.5" y="3.5" width="17" height="17" rx="5" fill="none" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="12" r="3.8" fill="none" stroke="currentColor" stroke-width="2"/>
                        <circle cx="17.2" cy="6.8" r="1.1" fill="currentColor"/>
                        @break
                    @case('tiktok')
                        <path fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" d="M14 3v11.5a3.5 3.5 0 1 1-3.5-3.5M14 3c.4 2.4 2 4 4.5 4.2"/>
                        @break
                    @case('youtube')
                        <path fill="currentColor" fill-rule="evenodd" d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4A2.5 2.5 0 0 0 2.4 7.2C2 8.8 2 12 2 12s0 3.2.4 4.8a2.5 2.5 0 0 0 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8c.4-1.6.4-4.8.4-4.8s0-3.2-.4-4.8zM10 15V9l5 3z"/>
                        @break
                @endswitch
            </svg>
        </span>

        <span class="text-left leading-tight">
            <span class="block">{{ $connected ? 'Terhubung' : 'Hubungkan Akun' }}</span>
            <span class="block text-xs font-normal text-white/80">{{ $theme['label'] }}</span>
        </span>
    </span>

    @if ($connected)
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0z" clip-rule="evenodd"/>
        </svg>
    @else
        <svg class="h-5 w-5 shrink-0 transition group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M7.3 4.3a1 1 0 0 1 1.4 0l5 5a1 1 0 0 1 0 1.4l-5 5a1 1 0 0 1-1.4-1.4L11.6 10 7.3 5.7a1 1 0 0 1 0-1.4z" clip-rule="evenodd"/>
        </svg>
    @endif
</a>