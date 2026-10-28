@extends('layout.admin-layout')

@section('title', 'Ubah Pengiriman | Cap Jamu Raden')

@section('admin')
    <header class="flex flex-wrap items-end justify-between gap-5">
        <div>
            <p class="fade-in text-sm font-medium text-hutan/65" style="--d: 100ms">
                Manajemen distribusi
            </p>

            <h1 class="mt-2 text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-[1.05] tracking-[-0.035em]">
                <span class="line"><span style="--d: 200ms">Ubah Pengiriman</span></span>
            </h1>

            <p class="fade-in mt-3 text-sm text-hutan/65" style="--d: 300ms">
                Perbarui metode, ongkir, alamat tujuan, dan status pengiriman.
            </p>
        </div>

        <a href="{{ route('admin.pengiriman.show', $pengiriman) }}"
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
                    <p class="text-sm text-hutan/60">ID pengiriman</p>
                    <p class="mt-1 font-medium">#{{ $pengiriman->id }}</p>
                </div>

                <div>
                    <p class="text-sm text-hutan/60">ID transaksi</p>
                    <p class="mt-1 font-medium">#{{ $pengiriman->transaksi_id }}</p>
                </div>

                <div>
                    <p class="text-sm text-hutan/60">Pelanggan</p>
                    <p class="mt-1 font-medium">
                        {{ $pengiriman->transaksi?->user?->name ?? 'Pengguna tidak tersedia' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-hutan/60">Total transaksi saat ini</p>
                    <p class="mt-1 font-medium text-hutan">
                        Rp{{ number_format((float) ($pengiriman->transaksi?->total_bayar ?? 0), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.pengiriman.update', $pengiriman) }}"
              method="POST"
              class="mt-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="metode" class="field-label">
                    Metode pengiriman
                </label>

                <input id="metode"
                       name="metode"
                       type="text"
                       class="input w-full"
                       value="{{ old('metode', $pengiriman->metode === 'Belum ditentukan' ? '' : $pengiriman->metode) }}"
                       placeholder="Contoh: Kurir toko, GoSend, GrabExpress"
                       maxlength="100"
                       required>

                <p class="mt-2 text-sm text-hutan/65">
                    Masukkan nama layanan yang akan digunakan untuk mengirim pesanan.
                </p>

                @error('metode')
                    <p class="field-error mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="alamat_tujuan" class="field-label">
                    Alamat tujuan
                </label>

                <textarea id="alamat_tujuan"
                          name="alamat_tujuan"
                          class="input w-full"
                          rows="4"
                          maxlength="255"
                          placeholder="Masukkan alamat lengkap penerima"
                          required>{{ old('alamat_tujuan', $pengiriman->alamat_tujuan) }}</textarea>

                @error('alamat_tujuan')
                    <p class="field-error mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="ongkir" class="field-label">
                    Ongkos kirim (Rp)
                </label>

                <input id="ongkir"
                       name="ongkir"
                       type="number"
                       class="input w-full"
                       value="{{ old('ongkir', $pengiriman->ongkir) }}"
                       min="0"
                       max="9999999999.99"
                       step="0.01"
                       required>

                <p class="mt-2 text-sm text-hutan/65">
                    Total bayar transaksi akan dihitung ulang berdasarkan subtotal produk dan ongkir terbaru.
                </p>

                @error('ongkir')
                    <p class="field-error mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="field-label">
                    Status pengiriman
                </label>

                <select id="status" name="status" class="input w-full" required>
                    @foreach ([
                        'menunggu' => 'Menunggu',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'diterima' => 'Diterima',
                    ] as $key => $label)
                        <option value="{{ $key }}"
                                @selected(old('status', $pengiriman->status) === $key)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <p class="mt-2 text-sm text-hutan/65">
                    Perbarui status mengikuti proses pengiriman yang sebenarnya.
                </p>

                @error('status')
                    <p class="field-error mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap items-center gap-3 border-t border-hutan/15 pt-6">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-hutan px-6 py-3.5 font-medium text-daun transition hover:bg-hutan-deep">
                    Simpan perubahan
                </button>

                <a href="{{ route('admin.pengiriman.show', $pengiriman) }}"
                   class="aksi">
                    Batal
                </a>
            </div>
        </form>
    </section>
@endsection