# Testing Email Verification Feature

## Quick Test Guide

### Step 1: Start Laravel Server
```bash
cd /Users/arbysixteen/RBDEV/run-dev-event
php artisan serve
```

### Step 2: Test Registration
1. Open browser: `http://127.0.0.1:8000/pendaftaran`
2. Fill in the form:
   - Nama Lengkap: Test User
   - Email: test@example.com
   - Kategori Lomba: 5K
   - Nomor WhatsApp: 08123456789
   - Ukuran Baju: M
   - Password: test123
   - Konfirmasi Password: test123
3. Click "Daftar Sekarang"
4. Should see success message: "Pendaftaran berhasil! Email verifikasi telah dikirim..."
5. You'll be redirected to login page after 5 seconds

### Step 3: Check Email Log
```bash
# Open the log file
tail -f storage/logs/laravel.log

# Look for the verification email content
# You should see HTML content with verification link
# Example: http://127.0.0.1:8000/verify-email/{token}
```

### Step 4: Extract Verification Link
From the log file, find the verification URL that looks like:
```
http://127.0.0.1:8000/verify-email/XXXXbase64encodedtokenXXXX
```

Copy this entire URL.

### Step 5: Test Login WITHOUT Verification
1. Go to: `http://127.0.0.1:8000/peserta/login`
2. Enter:
   - Email: test@example.com
   - Password: test123
3. Click "Login dengan Email"
4. Should see error: "Email belum diverifikasi..."
5. Should see button: "Kirim Ulang Email Verifikasi"

### Step 6: Test Resend Verification Email
1. Click "Kirim Ulang Email Verifikasi" button
2. Button should change to "Mengirim..."
3. Should see success message
4. Check log file again to see new verification email

### Step 7: Verify Email
1. Paste the verification URL from Step 4 into browser
2. Should redirect to `/peserta/login`
3. Should see success message: "Email berhasil diverifikasi! Silakan login."

### Step 8: Test Login WITH Verification
1. Go to: `http://127.0.0.1:8000/peserta/login`
2. Enter:
   - Email: test@example.com
   - Password: test123
3. Click "Login dengan Email"
4. Should succeed! ✅
5. Should redirect to: `/peserta/dashboard`

### Step 9: Test Error Messages

#### Test "Akun tidak ditemukan"
1. Go to login page
2. Enter:
   - Email: nonexistent@example.com
   - Password: anything
3. Should see: "Akun tidak ditemukan. Silakan daftar terlebih dahulu."

#### Test "Password salah"
1. Go to login page
2. Enter:
   - Email: test@example.com (verified email)
   - Password: wrongpassword
3. Should see: "Password salah. Silakan periksa kembali password Anda."

## Expected Results Summary

| Test Case | Expected Result | Status |
|-----------|----------------|--------|
| Register new user | Success + email sent | ✅ |
| Check email log | Verification email in log | ✅ |
| Login before verify | Error + resend button | ✅ |
| Resend verification | Email sent again | ✅ |
| Click verification link | Success + redirect to login | ✅ |
| Login after verify | Success + redirect to dashboard | ✅ |
| Login with wrong email | "Akun tidak ditemukan" | ✅ |
| Login with wrong password | "Password salah" | ✅ |

## Troubleshooting

### Issue: "Class 'App\Http\Controllers\EmailVerificationController' not found"
**Solution:**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Issue: Email not appearing in log
**Solution:**
```bash
# Check .env file
MAIL_MAILER=log

# Clear cache
php artisan config:clear

# Check log file exists
ls -la storage/logs/laravel.log
```

### Issue: Verification link gives 404
**Solution:**
```bash
# Check route is registered
php artisan route:list | grep verify

# Should see: GET|HEAD verify-email/{token}
```

### Issue: "CSRF token mismatch"
**Solution:**
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Restart server
php artisan serve
```

## Manual API Testing (Optional)

### Test Registration API
```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "namaLengkap": "API Test User",
    "email": "apitest@example.com",
    "kategoriLomba": "10K",
    "nomorWA": "08123456789",
    "ukuranBaju": "L",
    "password": "apitest123",
    "password_confirmation": "apitest123"
  }'
```

Expected response:
```json
{
  "success": true,
  "message": "Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi.",
  "data": {
    "participantId": "RD...",
    "uid": "...",
    "emailSent": true
  }
}
```

### Test Resend Verification API
```bash
curl -X POST http://127.0.0.1:8000/api/resend-verification \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "apitest@example.com"
  }'
```

Expected response:
```json
{
  "success": true,
  "message": "Email verifikasi telah dikirim ulang. Silakan cek inbox Anda."
}
```

## Firebase Console Check

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select project: `run-event-cbb9c`
3. Go to **Authentication** → **Users**
4. Find your test user
5. Check **Email verified** column:
   - Should be ❌ before verification
   - Should be ✅ after verification

## Database Check

1. Go to Firebase Console
2. Go to **Realtime Database**
3. Navigate to: `participants/{uid}`
4. Check fields:
   - `emailVerified`: should be `false` initially
   - `emailVerified`: should be `true` after verification
   - `emailVerifiedAt`: timestamp when verified

## Success Criteria

✅ All tests pass without errors
✅ Email verification logs appear in laravel.log
✅ Login blocked before verification
✅ Login succeeds after verification
✅ Error messages display correctly
✅ Resend button works
✅ Firebase Auth shows emailVerified = true
✅ Realtime Database shows emailVerified = true

---

**Ready to Test!** Follow the steps above to verify all functionality works correctly.
