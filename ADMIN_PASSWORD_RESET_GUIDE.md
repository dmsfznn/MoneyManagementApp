# Admin-Based Password Reset System

## Overview

Sistem password reset yang baru tidak mengirim link reset otomatis ke user, melainkan:
1. User mengajukan permintaan reset password
2. Admin menerima notifikasi untuk memproses permintaan
3. Admin membuat password baru secara manual
4. Sistem mengirim email notifikasi ke user dengan password baru
5. User dapat login dengan password baru

## 🔄 Alur Kerja

### 1. **User Request Password Reset**
- User mengisi form di halaman login: "Forgot your password?"
- User memasukkan email terdaftar
- System membuat password reset request dengan status "Pending"

### 2. **Admin Notification**
- Semua admin menerima notifikasi:
  - Email notifikasi ke admin Gmail
  - Database notification di admin dashboard
- Notifikasi berisi:
  - Email user yang minta reset
  - Waktu request
  - Link untuk proses request

### 3. **Admin Processing**
- Admin login ke dashboard
- Buka menu "Password Reset Management"
- Review request yang pending
- Click "Process" untuk set password baru

### 4. **Password Generation & Email**
- Admin generate password baru:
  - Bisa generate otomatis (secure random)
  - Bisa input manual
- System update password user di database
- System kirim email ke user dengan password baru

### 5. **User Login**
- User menerima email dengan password baru
- User login dengan password baru
- Request status diubah menjadi "Completed"

## 🛠️ Fitur-Fitur

### **User Side:**
- Form request password reset yang user-friendly
- Informasi jelas tentang proses reset password
- Protection dari email enumeration (tidak memberi tahu apakah email ada/tdk)

### **Admin Side:**
- Dashboard untuk monitoring semua password reset requests
- Status tracking (Pending, Processing, Completed, Cancelled)
- Filter dan sorting requests
- Statistics dan analytics
- Bulk actions untuk multiple requests

### **Security Features:**
- Rate limiting (maksimal 1 request per 30 menit per user)
- Request expiration (24 jam jika tidak diproses)
- Admin authentication required untuk proses
- Audit trail untuk semua actions
- Secure password generation

## 📊 Admin Dashboard Features

### **Password Reset Management:**
1. **Index Page** (`/admin/password-resets`)
   - List semua password reset requests
   - Filter by status
   - Search by email/user
   - Pagination untuk data besar

2. **Request Detail** (`/admin/password-resets/{id}/edit`)
   - Detail informasi request
   - Generate password baru (auto/manual)
   - Preview email sebelum kirim
   - Admin notes untuk documentation

3. **Statistics** (`/admin/password-resets/statistics`)
   - Total requests
   - Pending/Processing/Completed counts
   - Request trends (daily, weekly, monthly)
   - Recent activity log

### **Status Types:**
- **Pending**: Baru dibuat, menunggu admin proses
- **Processing**: Sedang diproses oleh admin
- **Completed**: Selesai diproses, password sudah dikirim
- **Cancelled**: Dibatalkan oleh admin

## 🔧 Email Templates

### **Email ke Admin (Notification):**
```
Subject: Password Reset Request - Money Management App

Hello Admin Name,

A user has requested a password reset for their Money Management App account.

Request Details:
• Email: user@example.com
• Requested at: Dec 9, 2025 2:30 PM
• Status: Pending

Please review this request and take appropriate action.
You can either approve and reset the password, or cancel the request if it seems suspicious.

This request will expire if not processed within 24 hours.
```

### **Email ke User (Password Baru):**
```
Subject: Password Reset - Money Management App

Dear User Name,

Your password reset request has been processed by our admin team.

Here are your new login credentials:
Email: user@example.com
New Password: SecurePass123!

You can now login to your Money Management App account at: http://localhost:8000/login

For security reasons, we recommend changing your password after logging in.

If you did not request this password reset, please contact our support team immediately.

Best regards,
Admin Name
Admin - Money Management App
```

## 📝 API Endpoints

### **User Endpoints:**
- `POST /password/email` - Submit password reset request
- `GET /password/reset/{token}` - (Deprecated, tidak digunakan)

### **Admin Endpoints:**
- `GET /admin/password-resets` - List all requests
- `GET /admin/password-resets/{id}/edit` - Edit request
- `POST /admin/password-resets/{id}/send-email` - Send email to user
- `POST /admin/password-resets/{id}/cancel` - Cancel request
- `GET /admin/password-resets/statistics` - Statistics dashboard

## 🔒 Security Measures

### **Request Validation:**
- Email format validation
- Rate limiting per email address
- CSRF protection untuk semua forms
- Admin authentication required

### **Data Protection:**
- Secure password hashing (bcrypt)
- Request token generation
- Audit logging untuk semua actions
- Auto-cleanup old requests (7 days)

### **Abuse Prevention:**
- Request frequency limiting
- Automatic request expiration
- Suspicious activity detection
- Admin review workflow

## 🎯 Usage Instructions

### **For Users:**
1. Kunjungi halaman login
2. Klik "Forgot your password?"
3. Masukkan email terdaftar
4. Tunggu email dari admin team
5. Gunakan password baru dari email

### **For Admins:**
1. Login ke admin dashboard
2. Check notification untuk new requests
3. Review password reset requests di `/admin/password-resets`
4. Process pending requests:
   - Click "Process" pada request
   - Generate atau input password baru
   - Preview dan send email
5. Monitor request status

### **Best Practices:**
- Generate strong passwords (12+ karakter)
- Include admin notes for documentation
- Review requests secara berkala
- Cancel suspicious requests
- Monitor statistics untuk abuse detection

## 🔄 Migration dari Sistem Lama

Sistem baru ini mengganti sistem Laravel default yang mengirim link reset otomatis. Perubahan utama:

### **Sebelum (Laravel Default):**
- User menerima link reset password
- User reset password sendiri
- Email automation penuh

### **Sekarang (Admin-Based):**
- User request password ke admin
- Admin review dan approve
- Admin generate password baru
- Admin kirim password manual (via email template)

### **Benefits:**
- Lebih secure (human verification)
- Better abuse prevention
- Full audit trail
- Admin control
- Professional email communication

## 📁 Files yang Ditambahkan:

### **Models:**
- `app/Models/PasswordResetRequest.php` - Model untuk request data

### **Controllers:**
- `app/Http/Controllers/Admin/PasswordResetController.php` - Admin management
- `app/Http/Controllers/Auth/ForgotPasswordController.php` - Updated untuk sistem baru

### **Services:**
- `app/Services/PasswordResetService.php` - Business logic dan utilities

### **Notifications:**
- `app/Notifications/AdminPasswordResetNotification.php` - Admin notifications

### **Views (Admin):**
- `resources/views/admin/password-resets/index.blade.php` - List requests
- `resources/views/admin/password-resets/edit.blade.php` - Process form
- `resources/views/admin/password-resets/email-preview.blade.php` - Email preview

### **Views (User):**
- `resources/views/auth/password/email.blade.php` - Updated form

### **Migrations:**
- `database/migrations/2025_12_09_064646_create_password_reset_requests_table.php`

### **Documentation:**
- `ADMIN_PASSWORD_RESET_GUIDE.md` - Guide ini
- `README_PASSWORD_RESET.md` - Quick reference

## 🚀 Quick Start

### 1. Run Migration:
```bash
php artisan migrate
```

### 2. Test sebagai User:
- Kunjungi `/login`
- Klik "Forgot your password?"
- Masukkan email dan submit

### 3. Test sebagai Admin:
- Login sebagai admin
- Cek notifikasi
- Kunjungi `/admin/password-resets`
- Process pending request

### 4. Monitor:
- Check email delivery
- Review statistics dashboard
- Monitor request logs

## 📞 Support

Untuk troubleshooting atau pertanyaan:
1. Check logs: `storage/logs/laravel.log`
2. Verify database: `php artisan tinker`
3. Test email configuration
4. Review documentation di atas