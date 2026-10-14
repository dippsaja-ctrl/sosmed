@props([
    'name' => 'caption',
    'label' => 'Caption',
    'value' => '',
    'rows' => 6,
    'placeholder' => 'Tulis caption posting Anda...',
])

{{--
    Textarea caption dengan penghitung karakter.
    - Mendengarkan event "platforms-changed" dari <x-platform-checklist> untuk
      mengikuti batas karakter platform terpendek yang dipilih.
    - Mengirim event "caption-state" ({ over: bool }) agar form induk bisa
      menonaktifkan tombol kirim saat caption kelewat panjang.
--}}
<div x-data="{
        text: @js(old($name, $value)),
        max: null,
        get over() { return this.max !== null && this.text.length > this.max },
     }"
     x-effect="$dispatch('caption-state', { over: over })"
     @platforms-changed.window="max = $event.detail.maxLength">

    <div class="flex items-baseline justify-between">
        <x-input-label :for="$name" :value="$label" />
        <span class="text-xs tabular-nums"
              :class="over ? 'font-semibold text-red-600' : 'text-gray-500 dark:text-gray-400'"
              x-text="max === null ? text.length : `${text.length} / ${max}`"
              aria-live="polite"></span>
    </div>

    <textarea id="{{ $name }}"
              name="{{ $name }}"
              rows="{{ $rows }}"
              x-model="text"
              placeholder="{{ $placeholder }}"
              {{ $attributes->class([
                  'mt-1 block w-full rounded-md border-gray-300 shadow-sm',
                  'focus:border-indigo-500 focus:ring-indigo-500',
                  'dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300',
              ]) }}
              :class="over && 'border-red-500 focus:border-red-500 focus:ring-red-500'"
              :aria-invalid="over"></textarea>

    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="max !== null" x-cloak>
        Batas karakter mengikuti platform terpendek yang Anda pilih.
    </p>

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>