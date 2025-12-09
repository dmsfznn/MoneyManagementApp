# Testing Admin-Based Password Reset System

## 🚀 Quick Testing Guide

### 1. Test sebagai User

1. **Request Password Reset:**
   - Kunjungi: `http://localhost:8000/login`
   - Klik "Forgot your password?"
   - Masukkan email terdaftar (contoh: `user@example.com`)
   - Klik "Request Password Reset"

2. **Expected Result:**
   - Pesan sukses: "Password reset request has been sent to our administrators"
   - Informasi tentang alur kerja

### 2. Test sebagai Admin

#### **Setup Admin User:**
- Pastikan admin user ada di database
- Login sebagai admin: `http://localhost:8000/login`

#### **Cek Dashboard:**
1. Kunjungi: `http://localhost:8000/admin/dashboard`
2. Lihat card "Password Reset Requests"
3. Cek notifikasi pending requests

#### **Manage Requests:**
1. Kunjungi: `http://localhost:8000/admin/password-resets`
2. Lihat request pending
3. Klik "Process" pada request yang ingin diproses

#### **Process Request:**
1. Generate password baru:
   - Klik "Generate Secure Password" untuk auto-generate
   - Atau input password manual (minimum 8 karakter)
2. Preview email (otomatis terbuat)
3. Klik "Process Request"

#### **Send Email:**
1. Review email preview
2. Klik "Send Email"
3. Status request akan berubah menjadi "Completed"

## 📋 Testing Checklist

### User Side ✅
- [ ] Form forgot password berfungsi
- [ ] User menerima email notifikasi request
- [ ] Informasi proses jelas
- [ ] User bisa login dengan password baru

### Admin Side ✅
- [ ] Admin menerima notifikasi email saat ada request baru
- [ ] Dashboard menampilkan statistik password reset
- [ ] List requests berfungsi dengan filter
- [ ] Processing form berfungsi
- [ ] Password generator berfungsi
- [ ] Email preview berfungsi
- [ ] Email sending berfungsi
- [ ] Request status tracking berfungsi
- [ ] Statistics dashboard berfungsi

### Security ✅
- [ ] Rate limiting berfungsi (1 request per 30 menit)
- [ ] Request expiration berfungsi (24 jam)
- [ ] Audit trail berfungsi
- [ ] Admin authentication required

## 🔧 Advanced Testing

### 1. Test Rate Limiting:
```bash
# Test rate limiting - coba submit request berkali-kali dari email yang sama
# Harus menolak dengan error setelah submit pertama
```

### 2. Test Request Expiration:
```php
# Set expiration time lebih cepat untuk testing
// Di PasswordResetService, ubah 24 jam menjadi 5 menit
->where('created_at', '>', now()->subMinutes(5))
```

### 3. Test Email Configuration:
```bash
# Test email sending ke user
# Cek email delivery di Gmail/Yahoo/Outlook
# Verify email template berfungsi dengan baik
```

### 4. Test Multiple Admins:
- Buat 2+ admin users
- Pastikan semua admin menerima notifikasi
- Test concurrent processing

## 🐛 Common Issues & Solutions

### Issue: User tidak ditemukan di sistem
**Solution:**
- Request tetap dibuat untuk security (tidak reveal email existence)
- System memberikan response sukses ke user

### Issue: Email tidak terkirim ke admin
**Solution:**
- Check mail configuration: `php artisan tinker`
- Test email sending: `Mail::to('admin@test.com')->send('test')`
- Pastikan admin email sudah benar di database

### Issue: Password reset request tidak muncul di admin dashboard
**Solution:**
- Run migration: `php artisan migrate`
- Clear cache: `php artisan config:clear`
- Refresh browser admin dashboard

### Issue: Email template tidak terkirim ke user
**Solution:**
- Check email configuration di .env
- Pastikan `MAIL_USERNAME` dan `MAIL_PASSWORD` benar
- Cek SMTP connection dengan debug tool

### Issue: Password tidak terupdate di database
**Solution:**
- Cek user record di database
- Verifikasi password reset request terhubung ke user yang benar
- Check log untuk error messages

## 📊 Test Data Contoh

### Test Email Addresses:
- `user1@example.com` - Regular user
- `user2@example.com` - Another user
- `nonexistent@example.com` - Non-existent user (untuk testing security)

### Test Passwords:
- Generated: `SecurePass123!` (auto-generated)
- Manual: `UserPassword2024` (minimal 8 karakter)
- Special: `P@ssw0rd!` (dengan karakter khusus)

## 🔍 Debug Commands

### Check Database:
```bash
php artisan tinker
>>> $requests = App\Models\PasswordResetRequest::all();
>>> $pending = App\Models\PasswordResetRequest::pending()->count();
>>> $recent = App\Models\PasswordResetRequest::latest()->take(5)->get();
```

### Check Logs:
```bash
tail -f storage/logs/laravel.log
grep "Password reset" storage/logs/laravel.log
```

### Clear Cache:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## 📧 Environment Variables

Untuk testing, pastikan .env sudah dikonfigurasi dengan benar:

```env
# Email Configuration (gunakan Gmail untuk testing)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=gmail-anda@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=gmail-anda@gmail.com
MAIL_FROM_NAME="Money Management App"
```

## 🎯 Success Criteria

### ✅ User berhasil:
1. Submit password reset request
2. Menerima konfirmasi request
3. Menerima email dengan password baru
4. Login berhasil dengan password baru
5. Bisa mengubah password setelah login

### ✅ Admin berhasil:
1. Menerima notifikasi email saat ada request baru
2. Lihat request di dashboard
3. Process request dengan generate password
4. Preview email template
5. Kirim email ke user
6. Track request status
7. Lihat statistics dan analytics

### ✅ System berhasil:
1. Semua workflow berfungsi sesuai desain
2. Security measures aktif (rate limiting, authentication)
3. Email delivery berfungsi
4. Data persistence dan tracking
5. Error handling yang proper
6. Responsive user interface

## 📝 Test Report Template

```
Test Date: [Tanggal]
Tester: [Nama]

User Testing:
- [ ] Form submission successful
- [ ] Request notification received
- [ ] Email delivered successfully
- [ ] Login successful with new password
- [ ] Password change functionality works

Admin Testing:
- [ ] Email notification received
- [ ] Dashboard shows requests
- [ ] Request list functionality
- [ ] Processing form works
- [ ] Password generation works
- [ ] Email preview accurate
- [ ] Email sending successful
- [ ] Status tracking works
- [ ] Statistics dashboard functional

System Testing:
- [ ] Rate limiting active
- [ ] Request expiration works
- [ ] Security measures active
- [ ] Error handling appropriate
- [ ] Database integrity maintained
```

Selamat testing! Sistem password reset berbasis admin sekarang siap digunakan! 🎉