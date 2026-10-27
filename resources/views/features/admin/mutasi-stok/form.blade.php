{{-- resources/views/features/admin/mutasi-stok/form.blade.php --}}
@php
    $ubah = $mutasi->exists;
    $produkDipilih = old('produk_id', $mutasi->produk_id ?? request('produk_id'));
@endphp

@if ($errors->any())
    <div class="alert alert-error mb-8" role="alert">
        Ada isian yang perlu diperbaiki. Periksa kolom yang bertanda merah.
    </div>
@endif

<form method="POST"
      action="{{ $ubah ? route('admin.mutasi-stok.update', $mutasi) : route('admin.mutasi-stok.store') }}">
    @csrf

    @if ($ubah)
        @method('PUT')
    @endif

    <fieldset class="grid gap-x-6 gap-y-5 sm:grid-cols-2">
        <legend class="mb-5 text-lg font-semibold tracking-tight">
            Informasi mutasi
        </legend>

        <div class="field sm:col-span-2">
            <label for="produk_id" class="field-label">Produk</label>
            <select id="produk_id" name="produk_id" required class="input"
                    @error('produk_id') aria-invalid="true" aria-describedby="produk-error" @enderror>
                <option value="">Pilih produk</option>

                @foreach ($produkList as $produk)
                    <option value="{{ $produk->id }}"
                            @selected((string) $produkDipilih === (string) $produk->id)>
                        {{ $produk->nama_produk }}
                        — Stok: {{ number_format($produk->stok, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>

            @error('produk_id')
                <p id="produk-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="jenis" class="field-label">Jenis mutasi</label>
            <select id="jenis" name="jenis" required class="input"
                    @error('jenis') aria-invalid="true" aria-describedby="jenis-error" @enderror>
                <option value="">Pilih jenis</option>
                <option value="masuk" @selected(old('jenis', $mutasi->jenis) === 'masuk')>
                    Stok masuk
                </option>
                <option value="keluar" @selected(old('jenis', $mutasi->jenis) === 'keluar')>
                    Stok keluar
                </option>
            </select>

            @error('jenis')
                <p id="jenis-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="jumlah" class="field-label">Jumlah</label>
            <input id="jumlah" name="jumlah" type="number"
                   min="1" max="2147483647" step="1"
                   value="{{ old('jumlah', $mutasi->jumlah) }}"
                   required class="input"
                   @error('jumlah') aria-invalid="true" aria-describedby="jumlah-error" @enderror>

            @error('jumlah')
                <p id="jumlah-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field sm:col-span-2">
            <label for="tanggal" class="field-label">Tanggal mutasi</label>
            <input id="tanggal" name="tanggal" type="date"
                   value="{{ old('tanggal', $mutasi->tanggal?->format('Y-m-d') ?? now()->toDateString()) }}"
                   max="{{ now()->toDateString() }}"
                   required class="input"
                   @error('tanggal') aria-invalid="true" aria-describedby="tanggal-error" @enderror>

            @error('tanggal')
                <p id="tanggal-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>
    </fieldset>

    <div class="mt-7 rounded-2xl bg-daun-2 p-4 text-sm leading-relaxed">
        <p class="font-medium">Perhatian</p>
        <p class="mt-1 text-hutan/75">
            Stok masuk akan menambah persediaan. Stok keluar akan mengurangi
            persediaan. Mutasi keluar tidak dapat melebihi stok yang tersedia.
        </p>
        @if ($ubah)
            <p class="mt-2 text-hutan/75">
                Mengubah mutasi akan mengoreksi efek mutasi lama dan menerapkan
                nilai baru pada stok produk.
            </p>
        @endif
    </div>

    <div class="mt-10 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ route('admin.mutasi-stok.index') }}"
           class="rounded-full px-7 py-3.5 text-center font-medium transition hover:bg-hutan/10">
            Batal
        </a>

        <div class="sm:w-56">
            <button type="submit" class="btn-submit">
                <span class="spin" aria-hidden="true"></span>
                <span>{{ $ubah ? 'Simpan perubahan' : 'Simpan mutasi' }}</span>
            </button>
        </div>
    </div>
</form>