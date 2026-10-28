@extends('layout.admin-layout')

@section('title', 'Proses Transaksi | Cap Jamu Raden')

@section('admin')
    <header class="flex flex-wrap items-end justify-between gap-5">
        <div>
            <p class="fade-in text-sm font-medium text-hutan/65" style="--d: 100ms">
                Manajemen penjualan
            </p>

            <h1 class="mt-2 text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-[1.05] tracking-[-0.035em]">
                <span class="line"><span style="--d: 200ms">Proses Transaksi</span></span>
            </h1>

            <p class="fade-in mt-3 text-sm text-hutan/65" style="--d: 300ms">
                Perbarui status pesanan #{{ $transaksi->id }} sesuai alur transaksi.
            </p>
        </div>

        <a href="{{ route('admin.transaksi.show', $transaksi) }}"
           class="fade-in inline-flex items-center gap-2 rounded-full border border-hutan/20 px-5 py-3 font-medium transition hover:bg-hutan/5"
           style="--d: 400ms">
            Kembali ke detail
        </a>
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

    <section class="fade-in mt-8 max-w-3xl" style="--d: 450ms">
        <div class="border-y border-hutan/15 py-5">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-hutan/60">Pelanggan</p>
                    <p class="mt-1 font-medium">
                        {{ $transaksi->user?->name ?? 'Pengguna tidak tersedia' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-hutan/60">Status sekarang</p>
                    <p class="mt-1">
                        <span class="role-pill" data-role="{{ $transaksi->status }}">
                            {{ ucfirst($transaksi->status) }}
                        </span>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-hutan/60">Metode pembayaran</p>
                    <p class="mt-1 font-medium">
                        {{ $transaksi->metode_bayar ? strtoupper($transaksi->metode_bayar) : 'Belum ditentukan' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-hutan/60">Pilihan pelanggan</p>
                    <p class="mt-1 font-medium">
                        {{ $transaksi->pengiriman === 'ambil_sendiri' ? 'Ambil sendiri' : 'Diantar' }}
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-sm text-hutan/60">Total bayar</p>
                    <p class="mt-1 text-xl font-semibold text-hutan">
                        Rp{{ number_format((float) $transaksi->total_bayar, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        @if ($transaksi->metode_bayar === 'qris')
            <div class="mt-6 rounded-2xl border border-hutan/15 p-5">
                <h2 class="font-semibold">Verifikasi pembayaran QRIS</h2>

                @if ($transaksi->bukti_bayar)
                    <p class="mt-2 text-sm text-hutan/65">
                        Pelanggan sudah mengunggah bukti pembayaran. Periksa bukti dan pastikan dana benar-benar masuk.
                    </p>

                    <a href="{{ \Illuminate\Support\Facades\Storage::url($transaksi->bukti_bayar) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="mt-4 inline-flex items-center gap-2 rounded-full border border-hutan/20 px-5 py-3 text-sm font-medium transition hover:bg-hutan/5">
                        Lihat bukti pembayaran
                    </a>
                @else
                    <p class="mt-2 text-sm text-hutan/65">
                        Bukti pembayaran belum tersedia. Pesanan belum dapat diproses.
                    </p>
                @endif
            </div>
        @endif

        @if ($transaksi->status === 'pesanan' && $transaksi->pengiriman === 'diantar')
            <div class="mt-6 rounded-2xl border border-hutan/15 p-5">
                <h2 class="font-semibold">Informasi pengiriman</h2>

                @if ($transaksi->pengirimanData)
                    <dl class="mt-4 space-y-3 text-sm">
                        <div>
                            <dt class="text-hutan/60">Metode pengiriman</dt>
                            <dd class="mt-1 font-medium">
                                {{ $transaksi->pengirimanData->metode }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-hutan/60">Alamat tujuan</dt>
                            <dd class="mt-1">
                                {{ $transaksi->pengirimanData->alamat_tujuan ?? '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-hutan/60">Ongkir</dt>
                            <dd class="mt-1 font-medium">
                                Rp{{ number_format((float) $transaksi->pengirimanData->ongkir, 0, ',', '.') }}
                            </dd>
                        </div>
                    </dl>

                    <a href="{{ route('admin.pengiriman.edit', $transaksi->pengirimanData) }}"
                       class="aksi mt-4 inline-flex">
                        Atur pengiriman dan ongkir
                    </a>
                @else
                    <p class="mt-2 text-sm text-hutan/65">
                        Data pengiriman belum dibuat. Periksa proses checkout pelanggan.
                    </p>
                @endif
            </div>
        @endif

        <form action="{{ route('admin.transaksi.update', $transaksi) }}"
              method="POST"
              class="mt-8 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="status" class="field-label">
                    Status transaksi berikutnya
                </label>

                <select id="status"
                        name="status"
                        class="input w-full"
                        required>
                    @if ($transaksi->status === 'pesanan')
                        @if ($transaksi->pengiriman === 'diantar')
                            <option value="diantar" @selected(old('status') === 'diantar')>
                                Diantar
                            </option>
                        @else
                            <option value="pengambilan" @selected(old('status') === 'pengambilan')>
                                Pengambilan
                            </option>
                        @endif
                    @elseif (in_array($transaksi->status, ['diantar', 'pengambilan'], true))
                        <option value="selesai" @selected(old('status') === 'selesai')>
                            Selesai
                        </option>
                    @else
                        <option value="selesai">Selesai</option>
                    @endif
                </select>

                <p class="mt-2 text-sm text-hutan/65">
                    Status selesai hanya digunakan setelah produk diterima pelanggan dan pembayaran sudah dikonfirmasi.
                </p>
            </div>

            @if ($transaksi->status !== 'selesai')
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-hutan px-6 py-3.5 font-medium text-daun transition hover:bg-hutan-deep">
                    Simpan status
                </button>
            @endif
        </form>
    </section>
@endsection