@extends('layout.admin-layout')

@section('title', 'Kelola pengguna | Cap Jamu Raden')

@section('admin')
@php
    $tab = [null => 'Semua'] + \App\Models\User::LABEL_ROLE;
    $total = $hitung->sum();
@endphp

    <header class="flex flex-wrap items-end justify-between gap-5">
        <div>
            <p class="fade-in text-sm font-medium text-hutan/65" style="--d: 100ms">Khusus Super Admin</p>
            <h1 class="mt-2 text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-[1.05] tracking-[-0.035em]">
                <span class="line"><span style="--d: 200ms">Kelola pengguna</span></span>
            </h1>
        </div>
        <a href="{{ route('admin.pengguna.create') }}" class="fade-in inline-flex items-center gap-2 rounded-full bg-hutan px-6 py-3.5 font-medium text-daun transition hover:bg-hutan-deep" style="--d: 400ms">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            Tambah pengguna
        </a>
    </header>

    {{-- Filter & pencarian --}}
    <div class="fade-in mt-9 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between" style="--d: 450ms">
        <nav class="-mx-5 flex gap-2 overflow-x-auto px-5 pb-1 lg:mx-0 lg:overflow-visible lg:px-0 lg:pb-0" aria-label="Filter role">
            @foreach ($tab as $kunci => $nama)
                @php
                    $jumlah = $kunci ? ($hitung[$kunci] ?? 0) : $total;
                    $aktif = (string) $role === (string) $kunci; // kunci null jadi '' di array PHP
                @endphp
                <a href="{{ route('admin.pengguna.index', array_filter(['role' => $kunci, 'q' => $q])) }}" class="chip shrink-0 rounded-full px-5 py-2.5 text-[15px] font-medium" aria-pressed="{{ $aktif ? 'true' : 'false' }}">
                    {{ $nama }}
                    <span class="ml-1 opacity-70">{{ $jumlah }}</span>
                </a>
            @endforeach
        </nav>

        <form method="GET" action="{{ route('admin.pengguna.index') }}" role="search" class="search lg:w-80">
            @if ($role)
                <input type="hidden" name="role" value="{{ $role }}">
            @endif
            <label for="cari-pengguna" class="sr-only">Cari pengguna</label>
            <div class="flex items-center gap-2.5 rounded-full px-4 py-3">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="M16 16l4.5 4.5"/></svg>
                <input id="cari-pengguna" name="q" type="search" value="{{ $q }}" placeholder="Cari nama, email, atau nomor" autocomplete="off" class="w-full bg-transparent text-[15px] outline-none placeholder:text-hutan/50">
            </div>
        </form>
    </div>

    {{-- Daftar --}}
    <section class="mt-8" aria-label="Daftar pengguna">
        <div class="tabel-kepala hidden md:grid" aria-hidden="true">
            <span>Pengguna</span>
            <span>Role</span>
            <span>Nomor WhatsApp</span>
            <span>Terdaftar</span>
            <span class="text-right">Aksi</span>
        </div>

        <ul class="divide-y divide-hutan/15 border-y border-hutan/15">
            @forelse ($daftar as $i => $u)
                <li class="baris cepat tabel-baris" style="--i: {{ $i }}">
                    <div class="flex min-w-0 items-center gap-4">
                        <span class="grid h-11 w-11 flex-none place-items-center rounded-full bg-daun-2 font-semibold" aria-hidden="true">{{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}</span>
                        <div class="min-w-0">
                            <p class="truncate font-medium">
                                {{ $u->name }}
                                @if ($u->id === auth()->id())
                                    <span class="ml-1 rounded-full bg-emas/50 px-2 py-0.5 align-middle text-xs font-medium">Kamu</span>
                                @endif
                            </p>
                            <p class="truncate text-sm text-hutan/65">{{ $u->email }}</p>
                        </div>
                    </div>

                    <div><span class="role-pill" data-role="{{ $u->role }}">{{ $u->peran_label }}</span></div>
                    <p class="text-sm md:text-[15px]"><span class="mr-2 text-hutan/55 md:hidden">WA</span>{{ $u->hp_tampil }}</p>
                    <p class="text-sm md:text-[15px]"><span class="mr-2 text-hutan/55 md:hidden">Terdaftar</span>{{ $u->tanggal_daftar }}</p>

                    <div class="flex items-center gap-2 md:justify-end">
                        <a href="{{ route('admin.pengguna.edit', $u) }}" class="aksi">Ubah</a>
                        @if ($u->id !== auth()->id())
                            <button type="button" class="aksi aksi-hapus" data-hapus="{{ route('admin.pengguna.destroy', $u) }}" data-nama="{{ $u->name }}">Hapus</button>
                        @endif
                    </div>
                </li>
            @empty
                <li class="px-4 py-16 text-center">
                    <p class="font-medium">Tidak ada pengguna yang cocok.</p>
                    <p class="mt-1 text-sm text-hutan/65">Coba kata kunci lain atau pilih filter yang berbeda.</p>
                    <a href="{{ route('admin.pengguna.index') }}" class="tautan mt-4 inline-block text-sm">Tampilkan semua</a>
                </li>
            @endforelse
        </ul>

        <p class="mt-4 text-sm text-hutan/65">Menampilkan {{ $daftar->count() }} dari {{ $daftar->total() }} pengguna.</p>
        {{ $daftar->links('components.admin.pengguna.pagination') }}
    </section>

    {{-- Konfirmasi hapus --}}
    <dialog id="dialog-hapus" class="dialog" aria-labelledby="dialog-judul">
        <form method="POST" action="" data-hapus-form>
            @csrf
            @method('DELETE')
            <h2 id="dialog-judul" class="text-xl font-semibold tracking-tight">Hapus pengguna?</h2>
            <p class="mt-3 leading-relaxed text-hutan/80">
                Akun <strong class="font-semibold text-hutan" data-hapus-nama></strong> akan dihapus permanen dan tidak bisa dikembalikan.
            </p>
            <div class="mt-7 flex justify-end gap-3">
                <button type="button" class="rounded-full px-6 py-3 font-medium shadow-[inset_0_0_0_1.5px_var(--color-hutan)] transition hover:bg-hutan hover:text-daun" data-batal>Batal</button>
                <button type="submit" class="rounded-full bg-rosella px-6 py-3 font-medium text-white transition hover:bg-[#8a1a2e]">Ya, hapus</button>
            </div>
        </form>
    </dialog>
@endsection