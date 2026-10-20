<!DOCTYPE html>
<html lang="id">
<body style="font-family: Arial, sans-serif; background:#f4f4f5; padding:24px;">
    <div style="max-width:420px; margin:auto; background:#fff; border-radius:8px; padding:24px;">
        <h2 style="margin-top:0;">
            {{ $purpose === 'register' ? 'Verifikasi Email' : 'Reset Password' }}
        </h2>
        <p>Gunakan kode berikut:</p>
        <p style="font-size:32px; font-weight:bold; letter-spacing:8px; text-align:center; margin:24px 0;">
            {{ $code }}
        </p>
        <p>Kode berlaku selama {{ $ttl }} menit. Jangan bagikan kode ini ke siapa pun.</p>
        <p style="color:#888; font-size:12px;">Abaikan email ini jika kamu tidak merasa memintanya.</p>
    </div>
</body>
</html>