{{-- Form bersama untuk tambah & ubah. Butuh $pengguna (User; ->exists menandai mode ubah). --}}
@php
    $ubah = $pengguna->exists;
    $sendiri = $ubah && $pengguna->id === auth()->id();
    $roleDipilih = old('role', $pengguna->role);
    $deskripsiRole = [
        \App\Models\User::ROLE_PELANGGAN => 'Belanja di toko saja.',
        \App\Models\User::ROLE_ADMIN => 'Mengelola toko, tanpa akses kelola pengguna.',
        \App\Models\User::ROLE_SUPERADMIN => 'Akses penuh, termasuk kelola pengguna.',
    ];
@endphp

@if ($errors->any())
    <div class="alert alert-error mb-8" role="alert">
        Ada isian yang perlu diperbaiki. Periksa kolom yang bertanda merah.
    </div>
@endif

<form method="POST" action="{{ $ubah ? route('admin.pengguna.update', $pengguna) : route('admin.pengguna.store') }}" data-auth-form novalidate>
    @csrf
    @if ($ubah)
        @method('PUT')
    @endif

    {{-- Data diri --}}
    <fieldset class="grid gap-x-6 gap-y-5 sm:grid-cols-2">
        <legend class="mb-5 text-lg font-semibold tracking-tight">Data diri</legend>

        <div class="field sm:col-span-2">
            <label for="name" class="field-label">Nama lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $pengguna->name) }}" autocomplete="off" required class="input" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
            @error('name')
                <p id="name-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="email" class="field-label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $pengguna->email) }}" autocomplete="off" required class="input" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
            @error('email')
                <p id="email-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="phone" class="field-label">Nomor WhatsApp</label>
            <input id="phone" name="phone" type="tel" inputmode="tel" value="{{ old('phone', $pengguna->hp_tampil) }}" placeholder="081234567890" autocomplete="off" required class="input" @error('phone') aria-invalid="true" aria-describedby="phone-error" @enderror>
            @error('phone')
                <p id="phone-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field sm:col-span-2">
            <label for="alamat" class="field-label">Alamat <span class="font-normal text-hutan/55">(opsional)</span></label>
            <textarea id="alamat" name="alamat" rows="3" maxlength="255" class="input resize-y" @error('alamat') aria-invalid="true" aria-describedby="alamat-error" @enderror>{{ old('alamat', $pengguna->alamat) }}</textarea>
            @error('alamat')
                <p id="alamat-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>
    </fieldset>

    {{-- Role --}}
    <fieldset class="mt-10">
        <legend class="mb-5 text-lg font-semibold tracking-tight">Role</legend>

        <div class="grid gap-3 sm:grid-cols-3">
            @foreach (\App\Models\User::LABEL_ROLE as $kunci => $nama)
                <label class="role-opsi">
                    <input type="radio" name="role" value="{{ $kunci }}" @checked($roleDipilih === $kunci) @disabled($sendiri)>
                    <span class="role-kartu">
                        <strong class="block font-medium">{{ $nama }}</strong>
                        <span class="mt-1 block text-sm leading-snug">{{ $deskripsiRole[$kunci] }}</span>
                    </span>
                </label>
            @endforeach
        </div>

        @if ($sendiri)
            <p class="field-hint">Role akunmu sendiri tidak bisa diubah, supaya sistem selalu punya Super Admin.</p>
        @endif
        @error('role')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </fieldset>

    {{-- Password --}}
    <fieldset class="mt-10 grid gap-x-6 gap-y-5 sm:grid-cols-2">
        <legend class="mb-5 text-lg font-semibold tracking-tight">Password</legend>
        @if ($ubah)
            <p class="field-hint sm:col-span-2" style="margin-top: -0.75rem; margin-bottom: 1rem">Kosongkan kalau tidak ingin mengganti password.</p>
        @endif

        <div class="field">
            <label for="password" class="field-label">{{ $ubah ? 'Password baru' : 'Password' }}</label>
            <div class="relative">
                <input id="password" name="password" type="password" placeholder="Minimal 8 karakter" autocomplete="new-password" @required(! $ubah) class="input pr-32" data-confirm-source @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                <button type="button" class="pw-toggle" data-toggle-password="#password" aria-pressed="false">Tampilkan</button>
            </div>
            @error('password')
                <p id="password-error" class="field-error">{{ $message }}</p>
            @enderror
            <div class="mt-3" data-strength="#password" data-level="0">
                <div class="strength-bar" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                <p class="field-hint" data-strength-text aria-live="polite">Minimal 8 karakter.</p>
            </div>
        </div>

        <div class="field">
            <label for="password_confirmation" class="field-label">Ulangi password</label>
            <div class="relative">
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ketik ulang password" autocomplete="new-password" @required(! $ubah) class="input pr-32" data-confirm>
                <button type="button" class="pw-toggle" data-toggle-password="#password_confirmation" aria-pressed="false">Tampilkan</button>
            </div>
            <p class="field-hint" data-match="#password" data-with="#password_confirmation" aria-live="polite"></p>
        </div>
    </fieldset>

    <div class="mt-10 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ route('admin.pengguna.index') }}" class="rounded-full px-7 py-3.5 text-center font-medium transition hover:bg-hutan/10">Batal</a>
        <div class="sm:w-56">
            <button type="submit" class="btn-submit" data-busy="Menyimpan">
                <span class="spin" aria-hidden="true"></span>
                <span data-label>{{ $ubah ? 'Simpan perubahan' : 'Tambah pengguna' }}</span>
            </button>
        </div>
    </div>
</form>