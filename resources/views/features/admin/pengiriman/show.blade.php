@extends('layout.admin-layout')

@section('title', 'Detail Pengiriman | Cap Jamu Raden')

@section('admin')
    <header class="flex flex-wrap items-end justify-between gap-5">
        <div>
            <p class="fade-in text-sm font-medium text-hutan/65" style="--d: 100ms">
                Manajemen distribusi
            </p>

            <h1 class="mt-2 text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-[1.05] tracking-[-0.035em]">
                <span class="line"><span style="--d: 200ms">Detail Pengiriman</span></span>
            </h1>

            <p class="fade-in mt-3 text-sm text-hutan/65" style="--d: 300ms">
                Informasi pengiriman #{{ $pengiriman->id }}.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.pengiriman.index') }}"
               class="fade-in inline-flex items-center gap-2 rounded-full border border-hutan/20 px-5 py-3 font-medium transition hover:bg-hutan/5"
               style="--d: 350ms">
                Kembali
            </a>

            <a href="{{ route('admin.pengiriman.edit', $pengiriman) }}"
               class="fade-in inline-flex items-center gap-2 rounded-full bg-hutan px-5 py-3 font-medium text-daun transition hover:bg-hutan-deep"
               style="--d: 400ms">
                Ubah pengiriman
            </a>
        </div>
    </header>

    @if (session('toast'))
        <div class="alert {{ session('toast.type') === 'error' ? 'alert-error' : 'alert-success' }} mt-6"
             role="status">
            {{ session('toast.message') }}
        </div>
    @elseif (session('success'))
        <div class="alert alert-success mt-6" role="status">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-error mt-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error mt-6" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <section class="fade-in mt-8" style="--d: 450ms">
        <h2 class="text-lg font-semibold">Informasi pengiriman</h2>

        <div class="mt-4 grid gap-6 border-y border-hutan/15 py-5 text-sm sm:grid-cols-2">
            <div>
                <p class="text-hutan/60">ID pengiriman</p>
                <p class="mt-1 font-medium">#{{ $pengiriman->id }}</p>
            </div>

            <div>
                <p class="text-hutan/60">ID transaksi</p>
                <p class="mt-1 font-medium">
                    #{{ $pengiriman->transaksi_id }}
                </p>
            </div>

            <div>
                <p class="text-hutan/60">Metode pengiriman</p>
                <p class="mt-1 font-medium">{{ $pengiriman->metode }}</p>
            </div>

            <div>
                <p class="text-hutan/60">Status pengiriman</p>
                <p class="mt-1">
                    <span class="role-pill" data-role="{{ $pengiriman->status }}">
                        {{ ucfirst($pengiriman->status) }}
                    </span>
                </p>
            </div>

            <div>
                <p class="text-hutan/60">Ongkos kirim</p>
                <p class="mt-1 font-medium">
                    Rp{{ number_format((float) $pengiriman->ongkir, 0, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-hutan/60">Pelanggan</p>
                <p class="mt-1 font-medium">
                    {{ $pengiriman->transaksi?->user?->name ?? 'Pengguna tidak tersedia' }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-hutan/60">Alamat tujuan</p>
                <p class="mt-1">
                    {{ $pengiriman->alamat_tujuan ?? 'Alamat belum ditentukan' }}
                </p>
            </div>
        </div>
    </section>

    @if ($pengiriman->transaksi)
        <section class="fade-in mt-10" style="--d: 500ms">
            <h2 class="text-lg font-semibold">Ringkasan transaksi</h2>

            <div class="mt-4 space-y-4 border-y border-hutan/15 py-5 text-sm">
                <div class="flex items-center justify-between gap-4">
                    <span class="text-hutan/65">Status transaksi</span>
                    <span class="role-pill" data-role="{{ $pengiriman->transaksi->status }}">
                        {{ ucfirst($pengiriman->transaksi->status) }}
                    </span>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <span class="text-hutan/65">Metode pembayaran</span>
                    <span class="font-medium">
                        {{ $pengiriman->transaksi->metode_bayar
                            ? strtoupper($pengiriman->transaksi->metode_bayar)
                            : '-' }}
                    </span>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <span class="text-hutan/65">Total pembayaran</span>
                    <span class="font-semibold text-hutan">
                        Rp{{ number_format((float) $pengiriman->transaksi->total_bayar, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <a href="{{ route('admin.transaksi.show', $pengiriman->transaksi) }}"
               class="mt-5 inline-flex items-center gap-2 rounded-full bg-hutan px-5 py-3 font-medium text-daun transition hover:bg-hutan-deep">
                Lihat transaksi lengkap
            </a>
        </section>
    @endif
@endsection