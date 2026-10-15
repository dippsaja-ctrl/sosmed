<?php

namespace App\Support;

/**
 * Validasi konten per platform (rasio, jumlah slide, ukuran, durasi, panjang caption).
 * Mengembalikan array: ['umum' => [...pesan], 'instagram' => [...pesan], ...]
 */
class PostValidator
{
    private const MB = 1048576;

    public static function check(string $type, array $targets, array $media, string $caption, array $yt): array
    {
        $errors = [];

        // Jenis file harus cocok dengan jenis konten.
        foreach ($media as $i => $m) {
            if ($type === 'video' && $m['kind'] !== 'video') $errors['umum'][] = 'Jenis Video/Reels membutuhkan file video.';
            if ($type !== 'video' && $m['kind'] !== 'image') $errors['umum'][] = 'Jenis Foto/Carousel membutuhkan file gambar.';
        }
        if (isset($errors['umum'])) {
            $errors['umum'] = array_values(array_unique($errors['umum']));
            return $errors;
        }

        foreach ($targets as $pk) {
            $r = config("posting.platforms.$pk");
            $e = [];

            if (! isset($r['types'][$type])) {
                $errors[$pk] = [$r['label'].' tidak mendukung jenis konten ini.'];
                continue;
            }

            if ($type === 'video') {
                $v = $media[0];
                if ($v['size'] > $r['video_mb'] * self::MB) {
                    $e[] = 'Ukuran video '.self::size($v['size']).' melebihi batas '.$r['video_mb'].' MB.';
                }
                if (($v['duration'] ?? null) !== null && ($v['duration'] < $r['video_sec'][0] || $v['duration'] > $r['video_sec'][1])) {
                    $e[] = 'Durasi '.$v['duration'].' detik di luar batas '.$r['video_sec'][0].' sampai '.$r['video_sec'][1].' detik.';
                }
            } else {
                if ($type === 'photo' && count($media) !== 1) $e[] = 'Foto tunggal hanya boleh 1 gambar.';
                if ($type === 'carousel' && (count($media) < $r['slides'][0] || count($media) > $r['slides'][1])) {
                    $e[] = "Carousel butuh {$r['slides'][0]} sampai {$r['slides'][1]} slide (sekarang ".count($media).').';
                }
                foreach ($media as $i => $m) {
                    $label = $type === 'carousel' ? 'Slide '.($i + 1) : 'Foto';
                    if (! empty($r['photo_mb']) && $m['size'] > $r['photo_mb'] * self::MB) {
                        $e[] = "$label: ".self::size($m['size'])." melebihi batas {$r['photo_mb']} MB.";
                    }
                    if (! empty($r['jpeg_only']) && ($m['mime'] ?? '') !== 'image/jpeg') {
                        $e[] = "$label: {$r['label']} hanya menerima JPEG.";
                    }
                    if (! empty($r['ratio']) && $m['width'] && $m['height']) {
                        $q = $m['width'] / $m['height'];
                        if ($q < $r['ratio'][0] || $q > $r['ratio'][1]) {
                            $e[] = "$label: rasio ".number_format($q, 2)." di luar {$r['ratio'][0]} sampai {$r['ratio'][1]}.";
                        }
                    }
                }
            }

            if ($pk === 'youtube') {
                $title = trim($yt['title'] ?? '');
                if ($title === '') $e[] = 'Judul YouTube wajib diisi.';
                elseif (mb_strlen($title) > 100) $e[] = 'Judul YouTube maksimal 100 karakter.';
            } elseif (mb_strlen($caption) > $r['caption_max']) {
                $e[] = 'Caption '.mb_strlen($caption)." karakter, batas {$r['caption_max']}.";
            }

            if ($e) $errors[$pk] = $e;
        }

        return $errors;
    }

    private static function size(int $b): string
    {
        return $b >= self::MB ? round($b / self::MB, 1).' MB' : max(1, round($b / 1024)).' KB';
    }
}
