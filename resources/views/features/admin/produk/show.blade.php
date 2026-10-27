{{-- resources/views/features/admin/produk/show.blade.php --}}
@extends('layout.admin-layout')

@section('title', 'Detail produk | Cap Jamu Raden')

@section('admin')
    <a href="{{ route('admin.produk.index') }}"
       class="back-link inline-flex items-center gap-2 rounded-full py-2 pl-2 pr-4 text-[15px] font-medium">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m15 6-6 6 6 6"/>
        </svg>
        Kelola produk
    </a>

    <header class="mt-5 flex flex-wrap items-start justify-between gap-5">
        <div>
            <p class="text-sm font-medium text-hutan/65">Detail persediaan</p>
            <h1 class="mt-2 text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-tight tracking-[-0.035em]">
                {{ $produk->nama_produk }}
            </h1>
            <p class="mt-3 text-sm text-hutan/65">
                Ditambahkan {{ $produk->created_at?->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j F Y') }}
            </p>
        </div>

        <a href="{{ route('admin.produk.edit', $produk) }}"
           class="inline-flex items-center gap-2 rounded-full bg-hutan px-6 py-3 font-medium text-daun transition hover:bg-hutan-deep">
            Ubah produk
        </a>
    </header>

    <section class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.2fr)]">
        <div class="overflow-hidden rounded-3xl bg-daun-2">
            @if ($produk->gambar)
                <img src="{{ asset('storage/' . $produk->gambar) }}"
                     alt="Gambar {{ $produk->nama_produk }}"
                     class="aspect-square w-full object-cover">
            @else
                <div class="grid aspect-square place-items-center text-hutan/50">
                    Gambar belum tersedia
                </div>
            @endif
        </div>

        <div class="rounded-3xl border border-hutan/15 p-6 sm:p-8">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <span class="text-sm text-hutan/65">Status produk</span>
                <span class="role-pill" data-role="{{ $produk->status }}">
                    {{ ucfirst($produk->status) }}
                </span>
            </div>

            <p class="mt-6 text-sm text-hutan/65">Harga</p>
            <p class="mt-1 text-3xl font-semibold tracking-tight">
                Rp{{ number_format((float) $produk->harga, 0, ',', '.') }}
            </p>

            <div class="mt-6 border-t border-hutan/15 pt-5">
                <p class="text-sm text-hutan/65">Stok tersedia</p>
                <p class="mt-1 text-xl font-semibold">
                    {{ number_format($produk->stok, 0, ',', '.') }} unit
                </p>
            </div>

            <div class="mt-6 border-t border-hutan/15 pt-5">
                <h2 class="font-semibold">Deskripsi</h2>
                <p class="mt-2 whitespace-pre-line leading-relaxed text-hutan/80">
                    {{ $produk->deskripsi }}
                </p>
            </div>

            <div class="mt-6 border-t border-hutan/15 pt-5">
                <h2 class="font-semibold">Khasiat</h2>
                <p class="mt-2 whitespace-pre-line leading-relaxed text-hutan/80">
                    {{ $produk->khasiat }}
                </p>
            </div>

            <div class="mt-6 border-t border-hutan/15 pt-5">
                <a href="{{ route('admin.mutasi-stok.create', ['produk_id' => $produk->id]) }}"
                   class="tautan inline-block text-sm font-medium">
                    Catat mutasi stok →
                </a>
            </div>
        </div>
    </section>

    <section class="mt-12">
        <h2 class="text-xl font-semibold tracking-tight">Riwayat mutasi stok</h2>
        <p class="mt-2 text-sm text-hutan/65">
            Perubahan persediaan yang tercatat untuk produk ini.
        </p>

        <div class="mt-5 overflow-x-auto rounded-2xl border border-hutan/15">
            <table class="w-full min-w-[600px] text-left text-sm">
                <thead class="bg-daun-2">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Tanggal</th>
                        <th class="px-4 py-3 font-semibold">Jenis</th>
                        <th class="px-4 py-3 font-semibold">Jumlah</th>
                        <th class="px-4 py-3 font-semibold">Petugas</th>
                        <th class="px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hutan/10">
                    @forelse ($riwayat as $mutasi)
                        <tr>
                            <td class="px-4 py-3">
                                {{ $mutasi->tanggal->locale('id')->translatedFormat('j M Y') }}
                            </td>
                            <td class="px-4 py-3">
                                {{ ucfirst($mutasi->jenis) }}
                            </td>
                            <td class="px-4 py-3">
                                {{ number_format($mutasi->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $mutasi->user?->name ?? 'Pengguna tidak tersedia' }}
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.mutasi-stok.show', $mutasi) }}"
                                   class="aksi">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-hutan/65">
                                Belum ada riwayat mutasi stok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $riwayat->links('components.admin.pengguna.pagination') }}
    </section>
@endsection