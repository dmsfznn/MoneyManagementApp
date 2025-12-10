<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = auth()->user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date|before:today',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'bio.max' => 'Bio maksimal 500 karakter.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Format gambar yang diizinkan: JPEG, PNG, JPG, GIF.',
            'profile_photo.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('tab', 'profile');
        }

        try {
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo) {
                    Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
                }

                $photo = $request->file('profile_photo');
                $photoName = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('profile-photos', $photoName, 'public');
                $user->profile_photo = $photoName;
            }

            // Update user information
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'bio' => $request->bio,
                'birth_date' => $request->birth_date,
            ]);

            return redirect()
                ->route('user.profile')
                ->with('success', 'Profil berhasil diperbarui!')
                ->with('tab', 'profile');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage()])
                ->withInput()
                ->with('tab', 'profile');
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini harus diisi.',
            'new_password.required' => 'Password baru harus diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('tab', 'security');
        }

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak benar.'])
                ->withInput()
                ->with('tab', 'security');
        }

        // Check if new password is different from current password
        if (Hash::check($request->new_password, $user->password)) {
            return back()
                ->withErrors(['new_password' => 'Password baru harus berbeda dengan password saat ini.'])
                ->withInput()
                ->with('tab', 'security');
        }

        try {
            // Update user password
            $user->update([
                'password' => Hash::make($request->new_password),
                'remember_token' => Str::random(60),
            ]);

            return redirect()
                ->route('user.profile')
                ->with('success', 'Password berhasil diubah! Silakan login kembali dengan password baru.')
                ->with('tab', 'security');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat mengubah password: ' . $e->getMessage()])
                ->withInput()
                ->with('tab', 'security');
        }
    }

    /**
     * Remove profile photo.
     */
    public function removePhoto()
    {
        $user = auth()->user();

        try {
            if ($user->profile_photo) {
                Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
                $user->update(['profile_photo' => null]);
            }

            return back()
                ->with('success', 'Foto profil berhasil dihapus!')
                ->with('tab', 'profile');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menghapus foto profil: ' . $e->getMessage()])
                ->with('tab', 'profile');
        }
    }
}