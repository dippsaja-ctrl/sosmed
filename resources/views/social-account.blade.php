@php
    // Ikon memakai {cls} sebagai placeholder ukuran, diganti saat dirender.
    $platforms = [
        'facebook' => [
            'label'  => 'Facebook',
            'desc'   => 'Posting ke Halaman Facebook kamu.',
            'tile'   => 'bg-[#1877F2]',
            'button' => 'bg-[#1877F2] hover:bg-[#166FE5] focus:ring-[#1877F2]',
            'icon'   => '<svg class="{cls}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        ],
        'instagram' => [
            'label'  => 'Instagram',
            'desc'   => 'Bagikan foto dan Reels ke akun bisnis atau kreator.',
            'tile'   => 'bg-[linear-gradient(45deg,#f9ce34,#ee2a7b,#6228d7)]',
            'button' => 'bg-[linear-gradient(45deg,#f9ce34,#ee2a7b,#6228d7)] hover:brightness-110 focus:ring-pink-500',
            'icon'   => '<svg class="{cls}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>',
        ],
        'tiktok' => [
            'label'  => 'TikTok',
            'desc'   => 'Unggah video pendek langsung dari dashboard.',
            'tile'   => 'bg-black',
            'button' => 'bg-black hover:bg-neutral-800 focus:ring-neutral-800',
            'icon'   => '<svg class="{cls}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
        ],
        'youtube' => [
            'label'  => 'YouTube',
            'desc'   => 'Publikasikan video ke channel YouTube kamu.',
            'tile'   => 'bg-[#FF0000]',
            'button' => 'bg-[#FF0000] hover:bg-[#CC0000] focus:ring-red-600',
            'icon'   => '<svg class="{cls}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
        ],
    ];

    // $accounts dikirim dari controller: koleksi akun dengan kolom 'platform' dan 'username'.
    $connected = collect($accounts ?? [])->keyBy('platform');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Akun media sosial
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-6 rounded-md bg-green-50 dark:bg-green-900/30 px-4 py-3 text-sm text-green-800 dark:text-green-300">
                    {{ session('status') }}
                </div>
            @endif

            <p class="mb-6 px-4 sm:px-0 text-sm text-gray-600 dark:text-gray-400">
                Hubungkan akun yang ingin kamu kelola. Kamu bisa memutuskan koneksi kapan saja.
            </p>

            <div class="grid grid-cols-1 gap-4 px-4 sm:px-0 sm:grid-cols-2">
                @foreach ($platforms as $key => $p)
                    @php $account = $connected->get($key); @endphp

                    <div class="flex flex-col bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                        <div class="flex items-start gap-4">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl text-white {{ $p['tile'] }}">
                                {!! str_replace('{cls}', 'h-6 w-6', $p['icon']) !!}
                            </span>

                            <div class="min-w-0">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $p['label'] }}</h3>

                                @if ($account)
                                    <p class="mt-1 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                        <span class="truncate">Terhubung sebagai {{ data_get($account, 'username') }}</span>
                                    </p>
                                @else
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $p['desc'] }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6">
                            @if ($account)
                                <form method="POST" action="{{ route('social-accounts.destroy', $key) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 shadow-sm transition hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                        Putuskan koneksi
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('social-accounts.redirect', $key) }}"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-md px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 {{ $p['button'] }}">
                                    {!! str_replace('{cls}', 'h-5 w-5', $p['icon']) !!}
                                    Hubungkan Akun {{ $p['label'] }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
