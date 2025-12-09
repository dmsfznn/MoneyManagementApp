# SMTP Transport Exception Troubleshooting Guide

## Error yang Terjadi
```
Symfony\Component\Mailer\Exception\TransportException
vendor\symfony\mailer\Transport\Smtp\EsmtpTransport.php:269
```

## 🔍 Penyebab Umum dan Solusi

### 1. **Authentication Failed (Autentikasi Gagal)**
**Penyebab:**
- Password salah atau menggunakan password biasa (bukan App Password)
- Username/email salah
- 2FA tidak aktif

**Solusi:**
#### Gmail:
1. Aktifkan 2FA: https://myaccount.google.com/security
2. Buat App Password: https://myaccount.google.com/apppasswords
3. Gunakan App Password (16 karakter) di .env:
```env
MAIL_USERNAME=gmail-anda@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop  # App Password, bukan password Gmail
```

#### Yahoo:
1. Aktifkan 2FA: https://login.yahoo.com/account/security
2. Buat App Password: https://login.yahoo.com/account/security/app-passwords
3. Gunakan App Password di .env:
```env
MAIL_USERNAME=yahoo-anda@yahoo.com
MAIL_PASSWORD=your-yahoo-app-password
```

### 2. **Connection Refused**
**Penyebab:**
- SMTP host salah
- Port salah atau diblokir firewall
- Tidak ada koneksi internet

**Solusi:**
```env
# Gmail SMTP
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587  # atau 465 untuk SSL

# Yahoo SMTP
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587  # atau 465 untuk SSL

# Outlook SMTP
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
```

### 3. **TLS/SSL Error**
**Penyebab:**
- Konfigurasi enkripsi salah
- SSL certificate tidak valid

**Solusi:**
```env
# Untuk port 587 gunakan TLS
MAIL_ENCRYPTION=tls

# Untuk port 465 gunakan SSL
MAIL_ENCRYPTION=ssl
```

## 🛠️ Langkah-Langkah Debugging

### Step 1: Akses Email Debug Tool
Buka: `http://localhost:8000/debug/email`

### Step 2: Test Connection
1. Lihat "Current Configuration"
2. Periksa hasil "Connection Test"
3. Jika gagal, lihat "Possible Solutions"

### Step 3: Test Email
1. Masukkan email Anda di "Test Email Address"
2. Klik "Send Test Email"
3. Periksa error yang muncul

### Step 4: Coba Konfigurasi Alternatif

#### Gmail Configuration Options:
```env
# Option 1: TLS (Recommended)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls

# Option 2: SSL
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

#### Yahoo Configuration Options:
```env
# Option 1: TLS (Recommended)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls

# Option 2: SSL
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

## 📋 Checklist Konfigurasi Email

### Gmail ✅
- [ ] 2FA aktif di Google Account
- [ ] App password dibuat (16 karakter)
- [ ] Konfigurasi .env:
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_ENCRYPTION=tls
  MAIL_USERNAME=gmail-anda@gmail.com
  MAIL_PASSWORD=app-password-disini
  MAIL_FROM_ADDRESS=gmail-anda@gmail.com
  ```
- [ ] Clear cache: `php artisan config:clear && php artisan config:cache`

### Yahoo ✅
- [ ] 2FA aktif di Yahoo Account
- [ ] App password dibuat
- [ ] Konfigurasi .env:
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.mail.yahoo.com
  MAIL_PORT=587
  MAIL_ENCRYPTION=tls
  MAIL_USERNAME=yahoo-anda@yahoo.com
  MAIL_PASSWORD=app-password-disini
  MAIL_FROM_ADDRESS=yahoo-anda@yahoo.com
  ```

### Outlook ✅
- [ ] 2FA aktif di Microsoft Account
- [ ] App password dibuat
- [ ] Konfigurasi .env:
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=smtp-mail.outlook.com
  MAIL_PORT=587
  MAIL_ENCRYPTION=tls
  MAIL_USERNAME=outlook-anda@outlook.com
  MAIL_PASSWORD=app-password-disini
  MAIL_FROM_ADDRESS=outlook-anda@outlook.com
  ```

## 🚨 Error Messages dan Solusi

### "Connection refused"
- Cek internet connection
- Verify SMTP host dan port
- Check firewall/antivirus
- Coba port alternatif

### "Authentication failed"
- Gunakan App Password (bukan password biasa)
- Cek username/email
- Pastikan 2FA aktif

### "TLS/SSL error"
- Gunakan `tls` untuk port 587
- Gunakan `ssl` untuk port 465
- Coba tanpa enkripsi (untuk testing)

### "Timeout"
- Cek koneksi internet
- Coba SMTP server alternatif
- Increase timeout

## 🔄 Testing Commands

```bash
# Clear configuration cache
php artisan config:clear
php artisan config:cache

# Test email functionality
php artisan tinker
>>> Mail::raw('Test email', function($m) { $m->to('your-email@gmail.com')->subject('Test'); });

# Check logs
tail -f storage/logs/laravel.log
```

## 📞 Support

Jika semua langkah di atas masih gagal:
1. Check logs: `storage/logs/laravel.log`
2. Akses debug tool: `http://localhost:8000/debug/email`
3. Contact support dengan error details

## 🎯 Quick Fix Checklist

1. **Enable 2FA** di email provider Anda
2. **Create App Password** (jangan gunakan password biasa)
3. **Update .env** dengan konfigurasi yang benar
4. **Clear cache**: `php artisan config:clear`
5. **Test with debug tool**: `/debug/email`
6. **Forgot password test** dengan email yang sudah dikonfigurasi

## 📁 Files yang Ditambahkan:
- `app/Services/EmailDebugService.php` - Debug utilities
- `app/Http/Controllers/Debug/EmailController.php` - Debug controller
- `resources/views/debug/email.blade.php` - Debug interface
- `SMTP_TROUBLESHOOTING.md` - Troubleshooting guide