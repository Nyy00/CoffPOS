# Fix Google OAuth "redirect_uri_mismatch" Error

## Masalah
Error: `redirect_uri_mismatch` - Google OAuth tidak bisa login karena redirect URI tidak sesuai.

## Penyebab
1. **Environment Variables di Railway belum diset dengan benar**
2. **Google Cloud Console belum dikonfigurasi dengan domain Railway yang benar**
3. **Redirect URI tidak sesuai antara aplikasi dan Google Console**

## Solusi Langkah-demi-Langkah

### Step 1: Update Environment Variables di Railway

1. **Buka Railway Dashboard**
   - Login ke [railway.app](https://railway.app)
   - Pilih project CoffPOS Anda

2. **Buka Tab Variables**
   - Klik tab "Variables" di project dashboard

3. **Tambahkan/Update Variables Berikut:**
   ```env
   APP_URL=https://coffpos.up.railway.app
   GOOGLE_CLIENT_ID=your-actual-google-client-id
   GOOGLE_CLIENT_SECRET=your-actual-google-client-secret
   GOOGLE_REDIRECT_URL=https://coffpos.up.railway.app/auth/google/callback
   ```

   **PENTING**: Ganti `your-actual-google-client-id` dan `your-actual-google-client-secret` dengan credentials asli dari Google Cloud Console.

### Step 2: Konfigurasi Google Cloud Console

1. **Buka Google Cloud Console**
   - Pergi ke [console.cloud.google.com](https://console.cloud.google.com)
   - Pilih project Anda atau buat project baru

2. **Enable APIs**
   - Pergi ke "APIs & Services" → "Library"
   - Cari dan enable "Google+ API" atau "Google Identity Services API"

3. **Konfigurasi OAuth Consent Screen**
   - Pergi ke "APIs & Services" → "OAuth consent screen"
   - Pilih "External" user type
   - Isi informasi aplikasi:
     - App name: `CoffPOS`
     - User support email: email Anda
     - Developer contact: email Anda
   - Di "Authorized domains", tambahkan: `up.railway.app`
   - Save dan continue

4. **Buat OAuth 2.0 Credentials**
   - Pergi ke "APIs & Services" → "Credentials"
   - Klik "Create Credentials" → "OAuth 2.0 Client IDs"
   - Pilih "Web application"
   - Konfigurasi:
     - **Name**: `CoffPOS Production`
     - **Authorized JavaScript origins**:
       ```
       https://coffpos.up.railway.app
       ```
     - **Authorized redirect URIs**:
       ```
       https://coffpos.up.railway.app/auth/google/callback
       ```
   - Klik "Create"
   - **COPY** Client ID dan Client Secret yang dihasilkan

### Step 3: Update Railway Variables dengan Credentials Asli

1. **Kembali ke Railway Dashboard**
2. **Update Variables dengan credentials dari Step 2:**
   ```env
   GOOGLE_CLIENT_ID=1234567890-abcdefghijklmnop.apps.googleusercontent.com
   GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijklmnopqrstuvwxyz
   ```

3. **Pastikan Variables Lain Sudah Benar:**
   ```env
   APP_URL=https://coffpos.up.railway.app
   APP_ENV=production
   APP_DEBUG=false
   ```

### Step 4: Redeploy dan Test

1. **Railway akan otomatis redeploy** setelah Anda update variables
2. **Tunggu deployment selesai** (biasanya 2-3 menit)
3. **Test Google Login:**
   - Buka `https://coffpos.up.railway.app/login`
   - Klik "Sign in with Google"
   - Seharusnya redirect ke Google tanpa error

## Troubleshooting

### Jika Masih Error "redirect_uri_mismatch":

1. **Cek URL yang Exact di Error Message**
   - Pastikan URL di error message sama persis dengan yang di Google Console
   - Perhatikan `http` vs `https`, trailing slash, dll.

2. **Double-check Google Console Configuration**
   - Pastikan redirect URI: `https://coffpos.up.railway.app/auth/google/callback`
   - Pastikan tidak ada typo atau spasi extra

3. **Cek Railway Variables**
   - Pastikan `GOOGLE_REDIRECT_URL` sama persis dengan yang di Google Console
   - Pastikan tidak ada spasi di awal/akhir variable

### Jika Error "This app isn't verified":

Ini normal untuk aplikasi baru. User bisa:
1. Klik "Advanced"
2. Klik "Go to CoffPOS (unsafe)"
3. Lanjutkan proses login

Untuk menghilangkan warning ini, submit app untuk verification di Google Console (opsional).

### Debug Endpoint

Tambahkan route ini untuk debug (hapus setelah selesai):

```php
// Di routes/web.php
Route::get('/debug/google-oauth', function () {
    return response()->json([
        'app_url' => config('app.url'),
        'google_client_id' => config('services.google.client_id') ? 'Set (' . substr(config('services.google.client_id'), 0, 10) . '...)' : 'Not Set',
        'google_client_secret' => config('services.google.client_secret') ? 'Set' : 'Not Set',
        'google_redirect_url' => config('services.google.redirect'),
        'current_url' => request()->url(),
    ]);
});
```

Akses: `https://coffpos.up.railway.app/debug/google-oauth`

## Checklist

- [ ] Google Cloud Console project dibuat/dipilih
- [ ] Google+ API atau Google Identity API di-enable
- [ ] OAuth consent screen dikonfigurasi
- [ ] OAuth 2.0 credentials dibuat dengan redirect URI yang benar
- [ ] Railway environment variables diupdate dengan credentials asli
- [ ] Application di-redeploy
- [ ] Google login di-test dan berhasil

## Expected Flow

1. User klik "Sign in with Google" di `/login`
2. Redirect ke Google OAuth dengan correct client_id
3. User authorize di Google
4. Google redirect kembali ke `/auth/google/callback`
5. Aplikasi process callback dan login user
6. User di-redirect ke dashboard sesuai role

Setelah mengikuti langkah-langkah ini, Google OAuth seharusnya bekerja dengan normal.