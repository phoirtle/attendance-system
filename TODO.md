# TODO: Implementasi Fitur Baru

## Fitur 1: Hanya Admin yang Bisa Edit Detail Akun
- [x] Ubah ProfileController - block user edit details
- [x] Sembunyikan menu "Personal Details" di profile/show untuk user
- [x] Update profile/details view untuk user

## Fitur 2: Single Device Login untuk User
- [x] Buat migration session_id di tabel users
- [x] Update User model - tambah session_id ke fillable
- [x] Update AuthController - simpan session_id saat login
- [x] Buat middleware SingleDeviceLogin
- [x] Daftarkan middleware di bootstrap/app.php
- [x] Jalankan migration

