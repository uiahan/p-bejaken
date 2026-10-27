{{-- resources/views/features/admin/produk/form.blade.php --}}
@php
    $ubah = $produk->exists;
@endphp

@if ($errors->any())
    <div class="alert alert-error mb-8" role="alert">
        Ada isian yang perlu diperbaiki. Periksa kolom yang bertanda merah.
    </div>
@endif

<form method="POST"
      action="{{ $ubah ? route('admin.produk.update', $produk) : route('admin.produk.store') }}"
      enctype="multipart/form-data">
    @csrf
    @if ($ubah)
        @method('PUT')
    @endif

    <fieldset class="grid gap-x-6 gap-y-5 sm:grid-cols-2">
        <legend class="mb-5 text-lg font-semibold tracking-tight">
            Informasi produk
        </legend>

        <div class="field sm:col-span-2">
            <label for="nama_produk" class="field-label">Nama produk</label>
            <input id="nama_produk" name="nama_produk" type="text"
                   value="{{ old('nama_produk', $produk->nama_produk) }}"
                   maxlength="255" required class="input"
                   @error('nama_produk') aria-invalid="true" aria-describedby="nama-produk-error" @enderror>
            @error('nama_produk')
                <p id="nama-produk-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field sm:col-span-2">
            <label for="deskripsi" class="field-label">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4"
                      maxlength="10000" required class="input resize-y"
                      @error('deskripsi') aria-invalid="true" aria-describedby="deskripsi-error" @enderror>{{ old('deskripsi', $produk->deskripsi) }}</textarea>
            @error('deskripsi')
                <p id="deskripsi-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="harga" class="field-label">Harga (Rp)</label>
            <input id="harga" name="harga" type="number" min="0"
                   max="99999999.99" step="0.01"
                   value="{{ old('harga', $produk->harga) }}"
                   required class="input"
                   @error('harga') aria-invalid="true" aria-describedby="harga-error" @enderror>
            @error('harga')
                <p id="harga-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="status" class="field-label">Status produk</label>
            <select id="status" name="status" required class="input"
                    @error('status') aria-invalid="true" aria-describedby="status-error" @enderror>
                <option value="aktif" @selected(old('status', $produk->status ?? 'aktif') === 'aktif')>
                    Aktif
                </option>
                <option value="nonaktif" @selected(old('status', $produk->status) === 'nonaktif')>
                    Nonaktif
                </option>
            </select>
            @error('status')
                <p id="status-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field sm:col-span-2">
            <label for="gambar" class="field-label">
                Gambar produk <span class="font-normal text-hutan/55">(opsional)</span>
            </label>

            @if ($produk->gambar)
                <img src="{{ asset('storage/' . $produk->gambar) }}"
                     alt="Gambar produk saat ini"
                     class="mb-4 h-36 w-36 rounded-2xl object-cover">
            @endif

            <input id="gambar" name="gambar" type="file"
                   accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                   class="input"
                   @error('gambar') aria-invalid="true" aria-describedby="gambar-error" @enderror>

            <p class="field-hint">
                JPG, PNG, atau WebP. Maksimal 2 MB.
                @if ($ubah)
                    Kosongkan jika tidak ingin mengganti gambar.
                @endif
            </p>

            @error('gambar')
                <p id="gambar-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field sm:col-span-2">
            <label for="khasiat" class="field-label">Khasiat</label>
            <textarea id="khasiat" name="khasiat" rows="4"
                      maxlength="10000" required class="input resize-y"
                      @error('khasiat') aria-invalid="true" aria-describedby="khasiat-error" @enderror>{{ old('khasiat', $produk->khasiat) }}</textarea>
            @error('khasiat')
                <p id="khasiat-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        @if (! $ubah)
            <div class="field sm:col-span-2">
                <label for="stok" class="field-label">Stok awal</label>
                <input id="stok" name="stok" type="number" min="0"
                       max="2147483647" step="1"
                       value="{{ old('stok', 0) }}" required class="input"
                       @error('stok') aria-invalid="true" aria-describedby="stok-error" @enderror>
                <p class="field-hint">
                    Stok awal akan dicatat sebagai mutasi masuk.
                </p>
                @error('stok')
                    <p id="stok-error" class="field-error">{{ $message }}</p>
                @enderror
            </div>
        @else
            <div class="field sm:col-span-2">
                <label class="field-label">Stok saat ini</label>
                <p class="rounded-2xl bg-daun-2 px-4 py-3 font-medium">
                    {{ number_format($produk->stok, 0, ',', '.') }} unit
                </p>
                <p class="field-hint">
                    Untuk mengubah stok, gunakan menu Mutasi Stok.
                </p>
            </div>
        @endif
    </fieldset>

    <div class="mt-10 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ route('admin.produk.index') }}"
           class="rounded-full px-7 py-3.5 text-center font-medium transition hover:bg-hutan/10">
            Batal
        </a>
        <div class="sm:w-56">
            <button type="submit" class="btn-submit">
                <span class="spin" aria-hidden="true"></span>
                <span>{{ $ubah ? 'Simpan perubahan' : 'Tambah produk' }}</span>
            </button>
        </div>
    </div>
</form>