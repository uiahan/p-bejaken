{{-- resources/views/features/admin/mutasi-stok/show.blade.php --}}
@extends('layout.admin-layout')

@section('title', 'Detail mutasi stok | Cap Jamu Raden')

@section('admin')
    <a href="{{ route('admin.mutasi-stok.index') }}"
       class="back-link inline-flex items-center gap-2 rounded-full py-2 pl-2 pr-4 text-[15px] font-medium">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m15 6-6 6 6 6"/>
        </svg>
        Mutasi stok
    </a>

    <header class="mt-5 flex flex-wrap items-start justify-between gap-5">
        <div>
            <p class="text-sm font-medium text-hutan/65">Riwayat persediaan</p>
            <h1 class="mt-2 text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-tight tracking-[-0.035em]">
                Detail mutasi stok
            </h1>
            <p class="mt-3 text-sm text-hutan/65">
                ID mutasi #{{ $mutasi->id }}
            </p>
        </div>

        <a href="{{ route('admin.mutasi-stok.edit', $mutasi) }}"
           class="inline-flex items-center gap-2 rounded-full bg-hutan px-6 py-3 font-medium text-daun transition hover:bg-hutan-deep">
            Ubah mutasi
        </a>
    </header>

    <section class="mt-8 max-w-3xl rounded-3xl border border-hutan/15 p-6 sm:p-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-hutan/65">Jenis mutasi</p>
            <span class="role-pill" data-role="{{ $mutasi->jenis }}">
                {{ $mutasi->jenis === 'masuk' ? 'Stok masuk' : 'Stok keluar' }}
            </span>
        </div>

        <p class="mt-6 text-sm text-hutan/65">Jumlah perubahan</p>
        <p class="mt-1 text-3xl font-semibold tracking-tight">
            {{ $mutasi->jenis === 'masuk' ? '+' : '−' }}{{ number_format($mutasi->jumlah, 0, ',', '.') }}
            <span class="text-base font-normal text-hutan/65">unit</span>
        </p>

        <dl class="mt-7 divide-y divide-hutan/15 border-t border-hutan/15">
            <div class="grid gap-1 py-4 sm:grid-cols-[160px_1fr]">
                <dt class="text-sm text-hutan/65">Produk</dt>
                <dd class="font-medium">
                    @if ($mutasi->produk)
                        <a href="{{ route('admin.produk.show', $mutasi->produk) }}"
                           class="tautan">
                            {{ $mutasi->produk->nama_produk }}
                        </a>
                    @else
                        Produk tidak tersedia
                    @endif
                </dd>
            </div>

            <div class="grid gap-1 py-4 sm:grid-cols-[160px_1fr]">
                <dt class="text-sm text-hutan/65">Tanggal mutasi</dt>
                <dd class="font-medium">
                    {{ $mutasi->tanggal->locale('id')->translatedFormat('j F Y') }}
                </dd>
            </div>

            <div class="grid gap-1 py-4 sm:grid-cols-[160px_1fr]">
                <dt class="text-sm text-hutan/65">Petugas</dt>
                <dd class="font-medium">
                    {{ $mutasi->user?->name ?? 'Pengguna tidak tersedia' }}
                </dd>
            </div>

            <div class="grid gap-1 py-4 sm:grid-cols-[160px_1fr]">
                <dt class="text-sm text-hutan/65">Dicatat pada</dt>
                <dd class="font-medium">
                    {{ $mutasi->created_at?->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j F Y, H:i') ?? '—' }}
                </dd>
            </div>

            <div class="grid gap-1 py-4 sm:grid-cols-[160px_1fr]">
                <dt class="text-sm text-hutan/65">Terakhir diperbarui</dt>
                <dd class="font-medium">
                    {{ $mutasi->updated_at?->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j F Y, H:i') ?? '—' }}
                </dd>
            </div>
        </dl>

        <div class="mt-7 flex flex-wrap gap-3 border-t border-hutan/15 pt-6">
            <a href="{{ route('admin.mutasi-stok.edit', $mutasi) }}"
               class="rounded-full bg-hutan px-6 py-3 font-medium text-daun transition hover:bg-hutan-deep">
                Ubah mutasi
            </a>

            @if ($mutasi->produk)
                <a href="{{ route('admin.produk.show', $mutasi->produk) }}"
                   class="rounded-full px-6 py-3 font-medium transition hover:bg-hutan/10">
                    Lihat produk
                </a>
            @endif
        </div>
    </section>
@endsection