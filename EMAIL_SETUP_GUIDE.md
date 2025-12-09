# Email Configuration Guide for Money Management App

## Panduan Setup Email untuk Forgot Password

Untuk mengirim reset password link ke email pengguna yang sebenarnya (Gmail, Yahoo, Outlook, dll), ikuti panduan berikut:

## 1. Setup Gmail

### Langkah 1: Enable 2-Factor Authentication (2FA)
1. Login ke Gmail account Anda
2. Buka: https://myaccount.google.com/security
3. Scroll ke "2-Step Verification" dan klik "Turn on"
4. Ikuti proses setup 2FA

### Langkah 2: Create App Password
1. Setelah 2FA aktif, buka: https://myaccount.google.com/apppasswords
2. Pilih:
   - App: Mail
   - Device: Other (Custom name)
   - Name: Money Management App
3. Klik "Generate"
4. Copy password yang muncul (format: xxxx xxxx xxxx xxxx)

### Langkah 3: Update .env file
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=gmail-anda@gmail.com
MAIL_PASSWORD=password-dari-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=gmail-anda@gmail.com
MAIL_FROM_NAME="Money Management App"
```

## 2. Setup Yahoo Mail

### Langkah 1: Enable 2-Factor Authentication
1. Login ke Yahoo Mail
2. Buka: https://login.yahoo.com/account/security
3. Aktifkan "Two-step verification"

### Langkah 2: Create App Password
1. Buka: https://login.yahoo.com/account/security/app-passwords
2. Select app: Other apps
3. App name: Money Management App
4. Klik "Generate"
5. Copy password yang muncul

### Langkah 3: Update .env file
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_USERNAME=yahoo-anda@yahoo.com
MAIL_PASSWORD=password-dari-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=yahoo-anda@yahoo.com
MAIL_FROM_NAME="Money Management App"
```

## 3. Setup Outlook/Hotmail

### Langkah 1: Enable 2-Factor Authentication
1. Login ke Microsoft Account
2. Buka: https://account.microsoft.com/security
3. Aktifkan "Two-step verification"

### Langkah 2: Create App Password
1. Buka: https://account.microsoft.com/security
2. Klik "Advanced security options"
3. Pilih "Create a new app password"
4. Copy password yang muncul

### Langkah 3: Update .env file
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=outlook-anda@outlook.com
MAIL_PASSWORD=password-dari-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=outlook-anda@outlook.com
MAIL_FROM_NAME="Money Management App"
```

## 4. Setup iCloud Mail

### Langkah 1: Generate App-Specific Password
1. Login ke Apple ID: https://appleid.apple.com
2. Buka "Sign-in and Security"
3. Klik "App-Specific Passwords"
4. Generate new password untuk aplikasi

### Langkah 3: Update .env file
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.me.com
MAIL_PORT=587
MAIL_USERNAME=icloud-anda@icloud.com
MAIL_PASSWORD=app-specific-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=icloud-anda@icloud.com
MAIL_FROM_NAME="Money Management App"
```

## 5. Cara Testing

### Langkah 1: Update Konfigurasi
1. Edit file `.env` dengan konfigurasi email yang dipilih
2. Save file .env

### Langkah 2: Clear Cache
```bash
php artisan config:cache
php artisan cache:clear
```

### Langkah 3: Testing Forgot Password
1. Buka halaman login: `http://localhost:8000/login`
2. Klik "Forgot your password?"
3. Masukkan email yang terdaftar
4. Check inbox email Anda
5. Klik link reset password dari email
6. Set password baru

## 6. Troubleshooting

### Common Issues & Solutions:

**Error: SMTP Connection Failed**
- Cek koneksi internet
- Pastikan email & password benar
- Verify app password (bukan regular password)

**Error: Authentication Failed**
- Gunakan App Password (bukan password biasa)
- Pastikan 2FA diaktifkan
- Check IMAP/POP3 diaktifkan di email settings

**Email tidak sampai**
- Check spam/junk folder
- Pastikan from address valid
- Verify domain tidak di-blacklist

**Gmail Specific:**
- Gunakan App Password: https://myaccount.google.com/apppasswords
- Pastikan "Less secure app access" tidak dibutuhkan jika menggunakan App Password

### SMTP Server Details:
- **Gmail**: smtp.gmail.com:587 (TLS)
- **Yahoo**: smtp.mail.yahoo.com:587 (TLS)
- **Outlook**: smtp-mail.outlook.com:587 (TLS)
- **Hotmail**: smtp.live.com:587 (TLS)
- **iCloud**: smtp.mail.me.com:587 (TLS)

## 7. Production Deployment

Untuk production environment, disarankan menggunakan:
1. **SendGrid**: `composer require laravel-sendgrid`
2. **Mailgun**: `composer require mailgun/mailgun-php`
3. **Amazon SES**: Built-in Laravel support
4. **Brevo (Sendinblue)**: `composer require sendinblue/api`

Ini lebih reliable untuk high volume emails.

## 8. Security Notes

⚠️ **IMPORTANT**:
- Jangan simpan password di file config yang di-commit ke Git
- Gunakan App Password bukan password utama
- Enable 2FA untuk semua email accounts
- Rate limiting untuk mencegah spam
- Validation untuk email input

## Quick Setup Command

Untuk testing cepat di development:

```bash
# Copy example .env
cp .env.example .env

# Edit .env dengan konfigurasi Gmail:
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Money Management App"

# Clear cache
php artisan config:clear
php artisan config:cache

# Test dengan forgot password feature
```