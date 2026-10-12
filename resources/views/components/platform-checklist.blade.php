@props([
    'name' => 'platforms',
    'label' => 'Posting ke',
    'selected' => [],
    'connected' => null,   // null = semua platform tersedia
    'platforms' => [
        'facebook'  => ['label' => 'Facebook',  'limit' => 63206],
        'instagram' => ['label' => 'Instagram', 'limit' => 2200],
        'tiktok'    => ['label' => 'TikTok',    'limit' => 2200],
        'youtube'   => ['label' => 'YouTube',   'limit' => 5000],
    ],
])

@php
    $limits = collect($platforms)->map(fn ($p) => $p['limit'] ?? null)->all();
@endphp

{{--
    Daftar centang platform tujuan.
    Mengirim event "platforms-changed" ({ selected: [], maxLength: int|null })
    setiap pilihan berubah, supaya <x-caption-input> dan form induk ikut bereaksi.
--}}
<fieldset x-data="{
              selected: @js(old($name, $selected)),
              limits: @js($limits),
              get maxLength() {
                  const values = this.selected.map(p => this.limits[p]).filter(Boolean);
                  return values.length ? Math.min(...values) : null;
              },
          }"
          x-effect="$dispatch('platforms-changed', { selected: selected, maxLength: maxLength })">

    <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</legend>

    <div class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4">
        @foreach ($platforms as $key => $platform)
            @php $available = $connected === null || in_array($key, $connected); @endphp

            <label @class([
                'relative flex flex-col rounded-xl border border-gray-300 p-3 text-sm transition',
                'has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50',
                'has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-indigo-500',
                'dark:border-gray-600 dark:has-[:checked]:bg-indigo-500/10',
                'cursor-pointer' => $available,
                'cursor-not-allowed opacity-50' => ! $available,
            ])>
                <input type="checkbox"
                       name="{{ $name }}[]"
                       value="{{ $key }}"
                       x-model="selected"
                       class="sr-only"
                       @disabled(! $available)>

                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $platform['label'] }}</span>
                <span class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                    {{ $available ? 'Siap digunakan' : 'Belum terhubung' }}
                </span>
            </label>
        @endforeach
    </div>

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</fieldset>