@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container mt-4">
    <!-- Header Section -->
    <div class="card card-shadcn bg-gradient-primary text-white mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Profil Saya</h2>
                    <p class="mb-0 opacity-90">Kelola informasi pribadi dan pengaturan keamanan akun Anda</p>
                </div>
                <div>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible border-start-4 border-success border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">
                        <i class="fas fa-check-circle me-2"></i>Berhasil!
                    </h5>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible border-start-4 border-danger border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">
                        <i class="fas fa-exclamation-triangle me-2"></i>Error!
                    </h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card card-shadcn">
                <div class="card-body text-center">
                    <!-- Profile Photo -->
                    <div class="mb-3">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                             class="rounded-circle" width="120" height="120"
                             style="object-fit: cover; border: 4px solid #3b82f6;">
                    </div>

                    <h5 class="card-title mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>

                    @if($user->bio)
                        <p class="text-muted mt-3">{{ $user->bio }}</p>
                    @endif

                    @if($user->phone)
                        <p class="text-muted small">
                            <i class="fas fa-phone me-1"></i>{{ $user->phone }}
                        </p>
                    @endif

                    @if($user->birth_date)
                        <p class="text-muted small">
                            <i class="fas fa-birthday-cake me-1"></i>{{ $user->birth_date->format('d F Y') }}
                        </p>
                    @endif

                    <hr>

                    <!-- Account Statistics -->
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border-end">
                                <h6 class="mb-0 text-primary">{{ $user->incomes()->count() }}</h6>
                                <small class="text-muted">Income</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <h6 class="mb-0 text-danger">{{ $user->expenses()->count() }}</h6>
                                <small class="text-muted">Expenses</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <h6 class="mb-0 text-info">{{ $user->budgets()->count() }}</h6>
                            <small class="text-muted">Budgets</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form Card -->
        <div class="col-lg-8">
            <div class="card card-shadcn">
                <div class="card-body">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs nav-tabs-custom mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ session('tab') == 'profile' || !session('tab') ? 'active' : '' }}"
                               id="profile-tab" data-bs-toggle="tab" href="#profile-content" role="tab">
                                <i class="fas fa-user me-2"></i>Informasi Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ session('tab') == 'security' ? 'active' : '' }}"
                               id="security-tab" data-bs-toggle="tab" href="#security-content" role="tab">
                                <i class="fas fa-shield-alt me-2"></i>Keamanan
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Profile Tab -->
                        <div class="tab-pane fade {{ session('tab') == 'profile' || !session('tab') ? 'show active' : '' }}"
                             id="profile-content" role="tabpanel">

                            <!-- Profile Form -->
                            <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Profile Photo -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Foto Profil</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $user->profile_photo_url }}" alt="Profile Photo"
                                             class="rounded-circle" width="80" height="80"
                                             style="object-fit: cover;" id="current-photo">
                                        <div class="flex-grow-1">
                                            <input type="file" class="form-control" name="profile_photo"
                                                   id="profile_photo" accept="image/*">
                                            <small class="text-muted">Format: JPEG, PNG, JPG, GIF. Maks: 2MB</small>
                                            @if($user->profile_photo)
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="if(confirm('Hapus foto profil?')) window.location.href='{{ route('user.profile.remove-photo') }}'">
                                                        <i class="fas fa-trash me-1"></i>Hapus Foto
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-medium">Nomor Telepon</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                           placeholder="+62 812-3456-7890">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Birth Date -->
                                <div class="mb-3">
                                    <label for="birth_date" class="form-label fw-medium">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                           id="birth_date" name="birth_date"
                                           value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Bio -->
                                <div class="mb-4">
                                    <label for="bio" class="form-label fw-medium">Bio</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror"
                                              id="bio" name="bio" rows="3"
                                              placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio) }}</textarea>
                                    <small class="text-muted">Maksimal 500 karakter</small>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </form>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade {{ session('tab') == 'security' ? 'show active' : '' }}"
                             id="security-content" role="tabpanel">

                            <form method="POST" action="{{ route('user.profile.update-password') }}">
                                @csrf

                                <!-- Current Password -->
                                <div class="mb-4">
                                    <label for="current_password" class="form-label fw-medium">
                                        Password Saat Ini <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                        <input type="password" class="form-control border-start-0 @error('current_password') is-invalid @enderror"
                                               id="current_password" name="current_password" required>
                                        <button type="button" class="btn btn-outline-secondary border-start-0"
                                                onclick="togglePasswordVisibility('current_password')" title="Tampilkan/Sembunyikan">
                                            <i class="fas fa-eye" id="current_password-toggle-icon"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div class="mb-4">
                                    <label for="new_password" class="form-label fw-medium">
                                        Password Baru <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-key text-primary"></i>
                                        </span>
                                        <input type="password" class="form-control border-start-0 @error('new_password') is-invalid @enderror"
                                               id="new_password" name="new_password" required minlength="8">
                                        <button type="button" class="btn btn-outline-secondary border-start-0"
                                                onclick="togglePasswordVisibility('new_password')" title="Tampilkan/Sembunyikan">
                                            <i class="fas fa-eye" id="new_password-toggle-icon"></i>
                                        </button>
                                    </div>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm New Password -->
                                <div class="mb-4">
                                    <label for="new_password_confirmation" class="form-label fw-medium">
                                        Konfirmasi Password Baru <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                        <input type="password" class="form-control border-start-0 @error('new_password_confirmation') is-invalid @enderror"
                                               id="new_password_confirmation" name="new_password_confirmation" required minlength="8">
                                        <button type="button" class="btn btn-outline-secondary border-start-0"
                                                onclick="togglePasswordVisibility('new_password_confirmation')" title="Tampilkan/Sembunyikan">
                                            <i class="fas fa-eye" id="new_password_confirmation-toggle-icon"></i>
                                        </button>
                                    </div>
                                    @error('new_password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password Strength Indicator -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Kekuatan Password</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div id="strength-bar" class="progress-bar bg-danger" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <span id="strength-text" class="text-muted small">Lemah</span>
                                    </div>
                                </div>

                                <!-- Password Requirements -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Persyaratan Password:</label>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle text-muted me-2" id="req-length"></i>
                                                <span class="small" id="req-length-text">Minimal 8 karakter</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle text-muted me-2" id="req-uppercase"></i>
                                                <span class="small" id="req-uppercase-text">Huruf besar (A-Z)</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle text-muted me-2" id="req-lowercase"></i>
                                                <span class="small" id="req-lowercase-text">Huruf kecil (a-z)</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle text-muted me-2" id="req-number"></i>
                                                <span class="small" id="req-number-text">Angka (0-9)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Security Alert -->
                                <div class="alert alert-info border-start-4 border-info border-0 mb-4">
                                    <h6 class="alert-heading"><i class="fas fa-shield-alt me-2"></i>Informasi Keamanan</h6>
                                    <ul class="mb-0">
                                        <li>Password baru harus berbeda dengan password saat ini</li>
                                        <li>Gunakan password yang kuat dan tidak mudah ditebak</li>
                                        <li>Jangan berbagi password dengan orang lain</li>
                                        <li>Ubah password secara berkala untuk keamanan akun</li>
                                    </ul>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key me-2"></i>Ubah Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-toggle-icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function checkPasswordStrength(password) {
    let strength = 0;
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');

    // Reset requirements
    resetRequirements();

    if (password.length >= 8) {
        strength++;
        updateRequirement('length', true);
    }
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password)) {
        strength++;
        updateRequirement('lowercase', true);
    }
    if (/[A-Z]/.test(password)) {
        strength++;
        updateRequirement('uppercase', true);
    }
    if (/[0-9]/.test(password)) {
        strength++;
        updateRequirement('number', true);
    }
    if (/[^a-zA-Z0-9]/.test(password)) {
        strength++;
    }

    const strengthPercentage = (strength / 6) * 100;
    strengthBar.style.width = strengthPercentage + '%';

    if (strength <= 2) {
        strengthBar.className = 'progress-bar bg-danger';
        strengthText.textContent = 'Lemah';
        strengthText.className = 'text-danger small';
    } else if (strength <= 4) {
        strengthBar.className = 'progress-bar bg-warning';
        strengthText.textContent = 'Sedang';
        strengthText.className = 'text-warning small';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        strengthText.textContent = 'Kuat';
        strengthText.className = 'text-success small';
    }
}

function updateRequirement(req, met) {
    const icon = document.getElementById('req-' + req);
    const text = document.getElementById('req-' + req + '-text');

    if (met) {
        icon.className = 'fas fa-check-circle text-success me-2';
        text.className = 'small text-success';
    } else {
        icon.className = 'fas fa-check-circle text-muted me-2';
        text.className = 'small text-muted';
    }
}

function resetRequirements() {
    const requirements = ['length', 'uppercase', 'lowercase', 'number'];
    requirements.forEach(req => {
        updateRequirement(req, false);
    });
    document.getElementById('strength-text').textContent = 'Lemah';
    document.getElementById('strength-text').className = 'text-muted small';
}

// Profile photo preview
document.getElementById('profile_photo')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('current-photo').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});

// Password strength checker
document.getElementById('new_password')?.addEventListener('input', function() {
    checkPasswordStrength(this.value);
});

// Tab persistence
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function (e) {
            // Update URL hash for tab persistence
            const tabId = e.target.getAttribute('href').substring(1);
            history.replaceState(null, null, '#' + tabId);
        });
    });

    // Restore tab from hash
    const hash = window.location.hash.substring(1);
    if (hash && ['profile-content', 'security-content'].includes(hash)) {
        const tabTrigger = document.querySelector(`[href="#${hash}"]`);
        if (tabTrigger) {
            new bootstrap.Tab(tabTrigger).show();
        }
    }
});
</script>
@endsection