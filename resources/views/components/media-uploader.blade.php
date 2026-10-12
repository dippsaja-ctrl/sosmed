@props([
    'name' => 'media',
    'label' => 'Foto atau video',
    'accept' => 'image/*,video/*',
    'max' => 10,
])

{{-- Uploader multi-file dengan drag & drop, pratinjau, dan tombol hapus per file. --}}
<div x-data="mediaUploader({{ (int) $max }})">

    <x-input-label :for="$name" :value="$label" />

    <input x-ref="input"
           id="{{ $name }}"
           type="file"
           name="{{ $name }}[]"
           accept="{{ $accept }}"
           multiple
           class="sr-only"
           @change="add($event.target.files)">

    <button type="button"
            @click="$refs.input.click()"
            @dragover.prevent="drag = true"
            @dragleave.prevent="drag = false"
            @drop.prevent="drag = false; add($event.dataTransfer.files)"
            :class="drag ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-500/10' : 'border-gray-300 dark:border-gray-600'"
            class="mt-1 flex w-full flex-col items-center justify-center gap-1 rounded-xl border-2 border-dashed px-4 py-8 text-center transition
                   hover:border-indigo-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
        <svg class="h-8 w-8 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 16V4m0 0L7.5 8.5M12 4l4.5 4.5M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/>
        </svg>
        <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">Unggah media</span>
        <span class="text-xs text-gray-500 dark:text-gray-400">
            Klik atau seret file ke sini. Maksimal {{ $max }} file.
        </span>
    </button>

    <p class="mt-2 text-sm text-red-600" x-show="error" x-text="error" x-cloak role="alert"></p>

    <ul class="mt-3 grid grid-cols-2 gap-3 sm:grid-cols-4" x-show="items.length" x-cloak>
        <template x-for="(item, i) in items" :key="item.url">
            <li class="group relative overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                <template x-if="!item.isVideo">
                    <img :src="item.url" :alt="item.file.name" class="h-24 w-full object-cover">
                </template>
                <template x-if="item.isVideo">
                    <video :src="item.url" class="h-24 w-full bg-black object-cover" muted preload="metadata"></video>
                </template>

                <div class="px-2 py-1 text-xs text-gray-600 dark:text-gray-400">
                    <p class="truncate" x-text="item.file.name"></p>
                    <p x-text="formatSize(item.file.size)"></p>
                </div>

                <button type="button"
                        @click="remove(i)"
                        class="absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-black/60 text-white hover:bg-black/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                        :aria-label="`Hapus ${item.file.name}`">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M5.3 5.3a1 1 0 0 1 1.4 0L10 8.6l3.3-3.3a1 1 0 1 1 1.4 1.4L11.4 10l3.3 3.3a1 1 0 0 1-1.4 1.4L10 11.4l-3.3 3.3a1 1 0 0 1-1.4-1.4L8.6 10 5.3 6.7a1 1 0 0 1 0-1.4z"/>
                    </svg>
                </button>
            </li>
        </template>
    </ul>

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
    <x-input-error :messages="$errors->get($name . '.*')" class="mt-2" />
</div>

@once
    <script>
        window.mediaUploader = (max) => ({
            items: [],
            drag: false,
            error: '',

            add(fileList) {
                this.error = '';
                for (const file of fileList) {
                    if (this.items.length >= max) {
                        this.error = `Maksimal ${max} file.`;
                        break;
                    }
                    if (!/^(image|video)\//.test(file.type)) {
                        this.error = 'Hanya foto atau video yang bisa diunggah.';
                        continue;
                    }
                    this.items.push({
                        file,
                        url: URL.createObjectURL(file),
                        isVideo: file.type.startsWith('video/'),
                    });
                }
                this.sync();
            },

            remove(index) {
                URL.revokeObjectURL(this.items[index].url);
                this.items.splice(index, 1);
                this.error = '';
                this.sync();
            },

            // Samakan isi <input type="file"> dengan daftar yang tampil di layar
            sync() {
                const transfer = new DataTransfer();
                this.items.forEach(item => transfer.items.add(item.file));
                this.$refs.input.files = transfer.files;
            },

            formatSize(bytes) {
                if (bytes < 1024 * 1024) return Math.max(1, Math.round(bytes / 1024)) + ' KB';
                return (bytes / 1024 / 1024).toFixed(1) + ' MB';
            },
        });
    </script>
@endonce