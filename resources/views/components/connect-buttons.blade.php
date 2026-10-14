{{-- Pemakaian: @include('partials.connect-buttons', ['connected' => ['facebook', 'youtube']]) --}}
@php $connected = $connected ?? []; @endphp

<section class="rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Hubungkan akun media sosial</h2>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        Pilih platform yang ingin Anda hubungkan. Anda akan diarahkan ke halaman izin platform tersebut.
    </p>

    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
        @foreach (['facebook', 'instagram', 'tiktok', 'youtube'] as $platform)
            <x-connect-account-button
                :platform="$platform"
                :connected="in_array($platform, $connected)"
                :href="Route::has('social.redirect') ? route('social.redirect', $platform) : '#'" />
        @endforeach
    </div>
</section>