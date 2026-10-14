{{--
    Halaman Form Posting.
    Variabel opsional dari controller:
      $connected : array platform yang sudah terhubung, mis. ['facebook', 'youtube'].
                   Jika tidak dikirim, semua platform dianggap tersedia.
--}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Buat Posting
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            <form method="POST"
                  action="{{ Route::has('posts.store') ? route('posts.store') : '#' }}"
                  enctype="multipart/form-data"
                  x-data="{
                      selected: [],
                      over: false,
                      mode: @js(old('publish_mode', 'now')),
                  }"
                  @platforms-changed="selected = $event.detail.selected"
                  @caption-state.window="over = $event.detail.over"
                  class="space-y-8 rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">
                @csrf

                <x-platform-checklist :connected="$connected ?? null" />

                <x-caption-input />

                <x-media-uploader :max="10" />

                {{-- Waktu tayang (akan dijadikan komponen di tahap berikutnya) --}}
                <fieldset>
                    <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">Waktu tayang</legend>

                    <div class="mt-3 flex flex-wrap gap-6 text-sm text-gray-700 dark:text-gray-300">
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="publish_mode" value="now" x-model="mode"
                                   class="border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Posting sekarang
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="radio" name="publish_mode" value="schedule" x-model="mode"
                                   class="border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Jadwalkan
                        </label>
                    </div>

                    <div class="mt-3" x-show="mode === 'schedule'" x-cloak>
                        <x-input-label for="scheduled_at" value="Tanggal dan jam" />
                        <x-text-input id="scheduled_at" name="scheduled_at" type="datetime-local"
                                      class="mt-1 block w-full sm:w-72"
                                      :value="old('scheduled_at')" />
                        <x-input-error :messages="$errors->get('scheduled_at')" class="mt-2" />
                    </div>
                </fieldset>

                <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}"
                       class="text-sm text-gray-600 underline hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                        Batal
                    </a>

                    <x-primary-button x-bind:disabled="selected.length === 0 || over">
                        <span x-text="mode === 'schedule' ? 'Jadwalkan posting' : 'Posting sekarang'"></span>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>