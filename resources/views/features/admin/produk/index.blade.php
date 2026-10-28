{{-- resources/views/features/admin/produk/index.blade.php --}}
@extends('layout.admin-layout')

@section('title', 'Kelola produk | Cap Jamu Raden')

@section('admin')
    <header class="flex flex-wrap items-end justify-between gap-5">
        <div>
            <p class="fade-in text-sm font-medium text-hutan/65" style="--d: 100ms">
                Manajemen persediaan
            </p>
            <h1 class="mt-2 text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-[1.05] tracking-[-0.035em]">
                <span class="line"><span style="--d: 200ms">Kelola produk</span></span>
            </h1>
            <p class="fade-in mt-3 text-sm text-hutan/65" style="--d: 300ms">
                Atur informasi, harga, dan ketersediaan produk jamu.
            </p>
        </div>

        <a href="{{ route('admin.produk.create') }}"
           class="fade-in inline-flex items-center gap-2 rounded-full bg-hutan px-6 py-3.5 font-medium text-daun transition hover:bg-hutan-deep"
           style="--d: 400ms">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                 aria-hidden="true">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah produk
        </a>
    </header>

    @if (session('toast'))
        <div class="alert {{ session('toast.type') === 'error' ? 'alert-error' : 'alert-success' }} mt-6"
             role="status">
            {{ session('toast.message') }}
        </div>
    @endif

    @if ($errors->has('produk'))
        <div class="alert alert-error mt-6" role="alert">
            {{ $errors->first('produk') }}
        </div>
    @endif

    {{-- Pencarian dan filter --}}
    <div class="fade-in mt-9 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between"
         style="--d: 450ms">
        <nav class="-mx-5 flex gap-2 overflow-x-auto px-5 pb-1 lg:mx-0 lg:px-0"
             aria-label="Filter status produk">
            @foreach ([null => 'Semua', 'aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] as $key => $label)
                <a href="{{ route('admin.produk.index', array_filter(['status' => $key, 'q' => $q])) }}"
                   class="chip shrink-0 rounded-full px-5 py-2.5 text-[15px] font-medium"
                   aria-current="{{ ($status ?? '') === (string) $key ? 'page' : 'false' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <form method="GET" action="{{ route('admin.produk.index') }}"
              role="search" class="search lg:w-80">
            @if ($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif

            <label for="cari-produk" class="sr-only">Cari produk</label>
            <div class="flex items-center gap-2.5 rounded-full px-4 py-3">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     aria-hidden="true">
                    <circle cx="11" cy="11" r="6.5"/>
                    <path d="m16 16 4.5 4.5"/>
                </svg>
                <input id="cari-produk" name="q" type="search"
                       value="{{ $q }}" placeholder="Cari nama atau deskripsi"
                       class="w-full bg-transparent text-[15px] outline-none placeholder:text-hutan/50">
            </div>
        </form>
    </div>

    {{-- Daftar produk --}}
    <section class="mt-8" aria-label="Daftar produk">
        <div class="tabel-kepala hidden md:grid"
             style="grid-template-columns:minmax(0,2fr) minmax(100px,.8fr) minmax(90px,.6fr) minmax(100px,.7fr) minmax(100px,.8fr)">
            <span>Produk</span>
            <span>Harga</span>
            <span>Stok</span>
            <span>Status</span>
            <span class="text-right">Aksi</span>
        </div>

        <ul class="divide-y divide-hutan/15 border-y border-hutan/15">
            @forelse ($daftar as $produk)
                <li class="baris cepat grid gap-4 py-5 md:items-center"
                    style="--i: {{ $loop->index }}; grid-template-columns:minmax(0,1fr)"
                    >
                    <div class="grid gap-4 md:items-center"
                         style="grid-template-columns:minmax(0,2fr) minmax(100px,.8fr) minmax(90px,.6fr) minmax(100px,.7fr) minmax(100px,.8fr)">
                        <div class="flex min-w-0 items-center gap-4">
                            @if ($produk->gambar)
                                <img src="{{ asset('storage/' . $produk->gambar) }}"
                                     alt="Gambar {{ $produk->nama_produk }}"
                                     loading="lazy"
                                     class="h-14 w-14 flex-none rounded-xl object-cover">
                            @else
                                <div class="grid h-14 w-14 flex-none place-items-center rounded-xl bg-daun-2"
                                     aria-hidden="true">
                                    <svg width="25" height="25" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="4" width="18" height="16" rx="2"/>
                                        <circle cx="8.5" cy="9" r="1.5"/>
                                        <path d="m21 15-5-5L5 20"/>
                                    </svg>
                                </div>
                            @endif

                            <div class="min-w-0">
                                <p class="truncate font-medium">{{ $produk->nama_produk }}</p>
                                <p class="mt-1 line-clamp-2 text-sm text-hutan/65">
                                    {{ $produk->deskripsi }}
                                </p>
                            </div>
                        </div>

                        <p class="text-sm md:text-[15px]">
                            <span class="mr-2 text-hutan/55 md:hidden">Harga:</span>
                            Rp{{ number_format((float) $produk->harga, 0, ',', '.') }}
                        </p>

                        <p class="text-sm md:text-[15px]">
                            <span class="mr-2 text-hutan/55 md:hidden">Stok:</span>
                            {{ number_format($produk->stok, 0, ',', '.') }}
                        </p>

                        <div>
                            <span class="role-pill"
                                  data-role="{{ $produk->status }}">
                                {{ ucfirst($produk->status) }}
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 md:justify-end">
                            <a href="{{ route('admin.produk.show', $produk) }}"
                               class="aksi">Detail</a>
                            <a href="{{ route('admin.produk.edit', $produk) }}"
                               class="aksi">Ubah</a>

                            @if ($produk->mutasiStoks()->doesntExist())
                                <form method="POST"
                                      action="{{ route('admin.produk.destroy', $produk) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="aksi aksi-hapus">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-16 text-center">
                    <p class="font-medium">Tidak ada produk yang cocok.</p>
                    <p class="mt-1 text-sm text-hutan/65">
                        Coba kata kunci lain atau pilih filter berbeda.
                    </p>
                    <a href="{{ route('admin.produk.index') }}"
                       class="tautan mt-4 inline-block text-sm">
                        Tampilkan semua
                    </a>
                </li>
            @endforelse
        </ul>

        <p class="mt-4 text-sm text-hutan/65">
            Menampilkan {{ $daftar->count() }} dari {{ $daftar->total() }} produk.
        </p>

        {{ $daftar->links('components.admin.pagination') }}
    </section>
@endsection