@php
    // Semua nilai dikirim dari controller; default 0 supaya halaman tetap tampil sebelum data ada.
    $stats = array_merge([
        'accounts'  => 0,   // jumlah akun terhubung
        'posts'     => 0,   // total posting
        'scheduled' => 0,   // posting terjadwal
        'followers' => 0,   // total pengikut
    ], $stats ?? []);

    $summary = [
        ['label' => 'Akun terhubung', 'value' => $stats['accounts']],
        ['label' => 'Total posting',  'value' => $stats['posts']],
        ['label' => 'Terjadwal',      'value' => $stats['scheduled']],
        ['label' => 'Pengikut',       'value' => $stats['followers']],
    ];

    $connectedPlatforms = collect($connectedPlatforms ?? []);
    $allPlatforms = ['facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'youtube' => 'YouTube'];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Ringkasan statistik --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <dl class="grid grid-cols-2 lg:grid-cols-4 divide-y divide-gray-100 dark:divide-gray-700 lg:divide-y-0 lg:divide-x">
                    @foreach ($summary as $item)
                        <div class="p-6 {{ $loop->index % 2 === 1 ? 'border-l border-gray-100 dark:border-gray-700 lg:border-l-0' : '' }}">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">{{ $item['label'] }}</dt>
                            <dd class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ number_format($item['value'], 0, ',', '.') }}
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- Status koneksi --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Status koneksi</h3>
                    <a href="{{ route('social-accounts.index') }}"
                        class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        Kelola akun
                    </a>
                </div>

                <ul class="mt-4 divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($allPlatforms as $key => $label)
                        @php $isConnected = $connectedPlatforms->contains($key); @endphp
                        <li class="flex items-center justify-between py-3">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                            <span class="inline-flex items-center gap-2 text-sm {{ $isConnected ? 'text-green-700 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                                <span class="h-2 w-2 rounded-full {{ $isConnected ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                                {{ $isConnected ? 'Terhubung' : 'Belum terhubung' }}
                            </span>
                        </li>
                    @endforeach
                </ul>

                @if ($connectedPlatforms->isEmpty())
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                        Belum ada akun yang terhubung. Hubungkan akun pertamamu untuk mulai melihat statistik.
                    </p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
