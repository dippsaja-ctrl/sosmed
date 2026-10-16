<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $platforms = (array) $this->input('platforms', []);

        return [
            'platforms'   => ['required', 'array', 'min:1'],
            'platforms.*' => ['distinct', Rule::in(array_keys(config('social.platforms')))],
            'type'        => ['required', Rule::in(['single', 'carousel'])],
            'caption'     => ['nullable', 'string', 'max:2200'],

            // Wajib kalau YouTube termasuk tujuan
            'title'       => [Rule::requiredIf(in_array('youtube', $platforms, true)), 'nullable', 'string', 'max:100'],

            'media'       => ['required', 'array', 'min:1', 'max:10'],
            'media.*'     => ['file', 'mimetypes:image/jpeg,image/png,video/mp4,video/quicktime', 'max:512000'], // KB
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul wajib diisi jika memposting ke YouTube.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            if ($v->errors()->isNotEmpty()) {
                return; // aturan dasar gagal, tidak perlu cek lanjutan
            }

            $platforms = $this->input('platforms');
            $files     = $this->file('media');
            $count     = count($files);
            $images    = collect($files)->filter(fn ($f) => str_starts_with($f->getMimeType(), 'image/'))->count();
            $videos    = $count - $images;

            // Konsistensi tipe vs jumlah media
            if ($this->input('type') === 'single' && $count !== 1) {
                $v->errors()->add('media', 'Postingan tunggal harus berisi tepat 1 media.');
            }
            if ($this->input('type') === 'carousel' && $count < 2) {
                $v->errors()->add('media', 'Carousel membutuhkan minimal 2 media.');
            }

            // TikTok: blokir foto statis
            if (in_array('tiktok', $platforms, true) && $images > 0) {
                $v->errors()->add('media', 'TikTok tidak menerima foto statis. Hapus gambar atau hilangkan TikTok dari tujuan.');
            }

            // TikTok: hanya 1 video
            if (in_array('tiktok', $platforms, true) && $videos > 1) {
                $v->errors()->add('media', 'TikTok hanya menerima 1 video per postingan.');
            }

            // YouTube: hanya 1 video
            if (in_array('youtube', $platforms, true) && ($videos !== 1 || $count !== 1)) {
                $v->errors()->add('media', 'YouTube membutuhkan tepat 1 video (tanpa foto, tanpa carousel).');
            }

            // Instagram: carousel maksimal 10 item
            if (in_array('instagram', $platforms, true) && $count > 10) {
                $v->errors()->add('media', 'Instagram maksimal 10 item per carousel.');
            }
        });
    }
}