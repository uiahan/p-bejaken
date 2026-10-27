{{-- resources/views/features/admin/mutasi-stok/index.blade.php --}}
@extends('layout.admin-layout')

@section('title', 'Mutasi stok | Cap Jamu Raden')

@section('admin')
    <header class="flex flex-wrap items-end justify-between gap-5">
        <div>
            <p class="fade-in text-sm font-medium text-hutan/65" style="--d: 100ms">
                Manajemen persediaan
            </p>
            <h1 class="mt-2 text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-[1.05] tracking-[-0.035em]">
                <span class="line"><span style="--d: 200ms">Mutasi stok</span></span>
            </h1>
            <p class="fade-in mt-3 text-sm text-hutan/65" style="--d: 300ms">
                Catat dan pantau setiap perubahan persediaan produk.
            </p>
        </div>

        <a href="{{ route('admin.mutasi-stok.create') }}"
           class="fade-in inline-flex items-center gap-2 rounded-full bg-hutan px-6 py-3.5 font-medium text-daun transition hover:bg-hutan-deep"
           style="--d: 400ms">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                 aria-hidden="true">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Catat mutasi
        </a>
    </header>

    @if (session('toast'))
        <div class="alert {{ session('toast.type') === 'error' ? 'alert-error' : 'alert-success' }} mt-6"
             role="status">
            {{ session('toast.message') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error mt-6" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="fade-in mt-9 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between"
         style="--d: 450ms">
        <nav class="-mx-5 flex gap-2 overflow-x-auto px-5 pb-1 lg:mx-0 lg:px-0"
             aria-label="Filter jenis mutasi">
            @foreach (['' => 'Semua', 'masuk' => 'Stok masuk', 'keluar' => 'Stok keluar'] as $key => $label)
                <a href="{{ route('admin.mutasi-stok.index', array_filter(['jenis' => $key, 'q' => $q])) }}"
                   class="chip shrink-0 rounded-full px-5 py-2.5 text-[15px] font-medium"
                   aria-current="{{ ($jenis ?? '') === $key ? 'page' : 'false' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <form method="GET" action="{{ route('admin.mutasi-stok.index') }}"
              role="search" class="search lg:w-80">
            @if ($jenis)
                <input type="hidden" name="jenis" value="{{ $jenis }}">
            @endif

            <label for="cari-mutasi" class="sr-only">Cari nama produk</label>
            <div class="flex items-center gap-2.5 rounded-full px-4 py-3">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     aria-hidden="true">
                    <circle cx="11" cy="11" r="6.5"/>
                    <path d="m16 16 4.5 4.5"/>
                </svg>
                <input id="cari-mutasi" name="q" type="search"
                       value="{{ $q }}" placeholder="Cari nama produk"
                       class="w-full bg-transparent text-[15px] outline-none placeholder:text-hutan/50">
            </div>
        </form>
    </div>

    <section class="mt-8" aria-label="Riwayat mutasi stok">
        <div class="overflow-x-auto rounded-2xl border-y border-hutan/15">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="tabel-kepala">
                    <tr>
                        <th class="px-4 py-4">Tanggal</th>
                        <th class="px-4 py-4">Produk</th>
                        <th class="px-4 py-4">Jenis</th>
                        <th class="px-4 py-4">Jumlah</th>
                        <th class="px-4 py-4">Petugas</th>
                        <th class="px-4 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hutan/15">
                    @forelse ($daftar as $mutasi)
                        <tr class="baris cepat" style="--i: {{ $loop->index }}">
                            <td class="px-4 py-5 whitespace-nowrap">
                                {{ $mutasi->tanggal->locale('id')->translatedFormat('j M Y') }}
                            </td>
                            <td class="px-4 py-5">
                                <p class="font-medium">
                                    {{ $mutasi->produk?->nama_produk ?? 'Produk tidak tersedia' }}
                                </p>
                            </td>
                            <td class="px-4 py-5">
                                <span class="role-pill"
                                      data-role="{{ $mutasi->jenis }}">
                                    {{ $mutasi->jenis === 'masuk' ? 'Stok masuk' : 'Stok keluar' }}
                                </span>
                            </td>
                            <td class="px-4 py-5 font-medium whitespace-nowrap">
                                {{ number_format($mutasi->jumlah, 0, ',', '.') }} unit
                            </td>
                            <td class="px-4 py-5">
                                {{ $mutasi->user?->name ?? 'Pengguna tidak tersedia' }}
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.mutasi-stok.show', $mutasi) }}"
                                       class="aksi">Detail</a>
                                    <a href="{{ route('admin.mutasi-stok.edit', $mutasi) }}"
                                       class="aksi">Ubah</a>

                                    <form method="POST"
                                          action="{{ route('admin.mutasi-stok.destroy', $mutasi) }}"
                                          onsubmit="return confirm('Hapus mutasi ini dan koreksi stok produk?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="aksi aksi-hapus">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-16 text-center">
                                <p class="font-medium">Belum ada mutasi stok.</p>
                                <p class="mt-1 text-sm text-hutan/65">
                                    Tambahkan mutasi untuk mencatat perubahan persediaan.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="mt-4 text-sm text-hutan/65">
            Menampilkan {{ $daftar->count() }} dari {{ $daftar->total() }} mutasi.
        </p>

        {{ $daftar->links('components.admin.pengguna.pagination') }}
    </section>
@endsection