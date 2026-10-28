@extends('layout.admin-layout')

@section('title', 'Detail Transaksi | Cap Jamu Raden')

@section('admin')
    <header class="flex flex-wrap items-end justify-between gap-5">
        <div>
            <p class="fade-in text-sm font-medium text-hutan/65" style="--d: 100ms">
                Manajemen penjualan
            </p>

            <h1 class="mt-2 text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-[1.05] tracking-[-0.035em]">
                <span class="line"><span style="--d: 200ms">Detail Transaksi</span></span>
            </h1>

            <p class="fade-in mt-3 text-sm text-hutan/65" style="--d: 300ms">
                Informasi lengkap transaksi #{{ $transaksi->id }}.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.transaksi.index') }}"
               class="fade-in inline-flex items-center gap-2 rounded-full border border-hutan/20 px-5 py-3 font-medium transition hover:bg-hutan/5"
               style="--d: 350ms">
                Kembali
            </a>

            @if ($transaksi->status !== 'selesai')
                <a href="{{ route('admin.transaksi.edit', $transaksi) }}"
                   class="fade-in inline-flex items-center gap-2 rounded-full bg-hutan px-5 py-3 font-medium text-daun transition hover:bg-hutan-deep"
                   style="--d: 400ms">
                    Proses transaksi
                </a>
            @endif
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

    <div class="mt-8 grid gap-8 lg:grid-cols-2">
        <section class="fade-in" style="--d: 450ms">
            <h2 class="text-lg font-semibold">Informasi pelanggan</h2>
            <div class="mt-4 space-y-4 border-y border-hutan/15 py-5 text-sm">
                <div>
                    <p class="text-hutan/60">Nama pelanggan</p>
                    <p class="mt-1 font-medium">
                        {{ $transaksi->user?->name ?? 'Pengguna tidak tersedia' }}
                    </p>
                </div>

                <div>
                    <p class="text-hutan/60">Email</p>
                    <p class="mt-1">{{ $transaksi->user?->email ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-hutan/60">Nomor HP</p>
                    <p class="mt-1">{{ $transaksi->user?->no_hp ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-hutan/60">Tanggal transaksi</p>
                    <p class="mt-1">
                        {{ $transaksi->tgl_transaksi?->locale('id')->translatedFormat('j F Y') ?? '-' }}
                    </p>
                </div>
            </div>
        </section>

        <section class="fade-in" style="--d: 500ms">
            <h2 class="text-lg font-semibold">Informasi pesanan</h2>
            <div class="mt-4 space-y-4 border-y border-hutan/15 py-5 text-sm">
                <div>
                    <p class="text-hutan/60">Status transaksi</p>
                    <p class="mt-1">
                        <span class="role-pill" data-role="{{ $transaksi->status }}">
                            {{ ucfirst($transaksi->status) }}
                        </span>
                    </p>
                </div>

                <div>
                    <p class="text-hutan/60">Metode pembayaran</p>
                    <p class="mt-1 font-medium">
                        {{ $transaksi->metode_bayar ? strtoupper($transaksi->metode_bayar) : 'Belum ditentukan' }}
                    </p>
                </div>

                <div>
                    <p class="text-hutan/60">Pilihan pengiriman</p>
                    <p class="mt-1 font-medium">
                        {{ $transaksi->pengiriman === 'ambil_sendiri'
                            ? 'Ambil sendiri'
                            : 'Diantar' }}
                    </p>
                </div>

                <div>
                    <p class="text-hutan/60">Bukti pembayaran QRIS</p>
                    @if ($transaksi->bukti_bayar)
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($transaksi->bukti_bayar) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="mt-1 inline-flex font-medium text-hutan underline">
                            Lihat bukti pembayaran
                        </a>
                    @else
                        <p class="mt-1 text-hutan/60">Belum tersedia</p>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <section class="fade-in mt-10" style="--d: 550ms" aria-label="Detail produk transaksi">
        <div>
            <h2 class="text-xl font-semibold">Detail produk</h2>
            <p class="mt-1 text-sm text-hutan/65">
                Daftar produk yang dibeli pada transaksi ini.
            </p>
        </div>

        <div class="mt-5 overflow-x-auto rounded-2xl border-y border-hutan/15">
            <table class="w-full min-w-[650px] text-left text-sm">
                <thead class="tabel-kepala">
                    <tr>
                        <th class="px-4 py-4">Produk</th>
                        <th class="px-4 py-4">Harga satuan</th>
                        <th class="px-4 py-4">Jumlah</th>
                        <th class="px-4 py-4 text-right">Subtotal</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-hutan/15">
                    @forelse ($transaksi->detailTransaksis as $detail)
                        <tr class="baris cepat" style="--i: {{ $loop->index }}">
                            <td class="px-4 py-5 font-medium">
                                {{ $detail->produk?->nama_produk ?? 'Produk tidak tersedia' }}
                            </td>

                            <td class="px-4 py-5 whitespace-nowrap">
                                Rp{{ number_format((float) $detail->harga_satuan, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-5 whitespace-nowrap">
                                {{ number_format($detail->jumlah, 0, ',', '.') }} unit
                            </td>

                            <td class="px-4 py-5 text-right font-medium whitespace-nowrap">
                                Rp{{ number_format((float) $detail->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-hutan/65">
                                Belum ada detail produk pada transaksi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @php
            $subtotalProduk = $transaksi->detailTransaksis->sum('subtotal');
            $ongkir = $transaksi->pengirimanData?->ongkir ?? 0;
        @endphp

        <div class="ml-auto mt-6 max-w-sm space-y-3 text-sm">
            <div class="flex justify-between gap-4">
                <span class="text-hutan/65">Subtotal produk</span>
                <span>Rp{{ number_format((float) $subtotalProduk, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between gap-4">
                <span class="text-hutan/65">Ongkos kirim</span>
                <span>Rp{{ number_format((float) $ongkir, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between gap-4 border-t border-hutan/15 pt-4 text-base font-semibold">
                <span>Total bayar</span>
                <span class="text-hutan">
                    Rp{{ number_format((float) $transaksi->total_bayar, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </section>

    @if ($transaksi->pengirimanData)
        <section class="fade-in mt-10" style="--d: 600ms">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold">Informasi pengiriman</h2>
                    <p class="mt-1 text-sm text-hutan/65">
                        Data pengiriman untuk transaksi ini.
                    </p>
                </div>

                <a href="{{ route('admin.pengiriman.show', $transaksi->pengirimanData) }}"
                   class="aksi">
                    Detail pengiriman
                </a>
            </div>

            <div class="mt-5 grid gap-5 border-y border-hutan/15 py-5 text-sm sm:grid-cols-2">
                <div>
                    <p class="text-hutan/60">Metode</p>
                    <p class="mt-1 font-medium">{{ $transaksi->pengirimanData->metode }}</p>
                </div>

                <div>
                    <p class="text-hutan/60">Status pengiriman</p>
                    <p class="mt-1">
                        <span class="role-pill" data-role="{{ $transaksi->pengirimanData->status }}">
                            {{ ucfirst($transaksi->pengirimanData->status) }}
                        </span>
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-hutan/60">Alamat tujuan</p>
                    <p class="mt-1">{{ $transaksi->pengirimanData->alamat_tujuan ?? '-' }}</p>
                </div>
            </div>
        </section>
    @endif

    @if ($transaksi->status === 'pesanan')
        <div class="alert mt-8" role="note">
            Pastikan pembayaran sudah diverifikasi dan data pengiriman sudah lengkap sebelum menyetujui pesanan.
        </div>
    @endif

    @if ($transaksi->status === 'pesanan')
        <form method="POST"
              action="{{ route('admin.transaksi.destroy', $transaksi) }}"
              class="mt-8"
              onsubmit="return confirm('Hapus transaksi yang belum diproses ini?')">
            @csrf
            @method('DELETE')

            <button type="submit" class="aksi aksi-hapus">
                Hapus transaksi
            </button>
        </form>
    @endif
@endsection