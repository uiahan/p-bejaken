@extends('layout.admin-layout')

@section('title', 'Dashboard | Cap Jamu Raden')

@section('admin')
    {{-- Judul --}}
    <header class="flex flex-wrap items-end justify-between gap-5">
        <div>
            <p class="fade-in text-sm font-medium text-hutan/65" style="--d: 100ms">{{ $tanggal }}</p>
            <h1 class="mt-2 text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-[1.05] tracking-[-0.035em]">
                <span class="line"><span style="--d: 200ms">{{ $sapaan }}, {{ $namaDepan }}.</span></span>
            </h1>
        </div>
        <a href="{{ route('toko.index') }}" class="fade-in inline-flex items-center gap-2 rounded-full px-5 py-3 font-medium shadow-[inset_0_0_0_1.5px_var(--color-hutan)] transition hover:bg-hutan hover:text-daun" style="--d: 400ms">
            Lihat toko
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7M9 7h8v8"/></svg>
        </a>
    </header>

    {{-- Ringkasan --}}
    <section class="mt-10" aria-label="Ringkasan">
        <ul class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($kartu as $i => $k)
                <li class="stat fade-in" style="--c: {{ $k['latar'] }}; --d: {{ 450 + $i * 120 }}ms">
                    <p class="text-[15px] font-medium">{{ $k['label'] }}</p>
                    @if (! is_null($k['nilai']))
                        <p class="stat-nilai" data-count="{{ $k['nilai'] }}" data-prefix="{{ $k['prefix'] }}">{{ $k['prefix'] }}{{ $k['nilai'] }}</p>
                    @else
                        <p class="stat-nilai is-kosong" aria-label="Belum tersedia">&mdash;</p>
                    @endif
                    <p class="mt-auto text-sm leading-snug text-hutan/75">{{ $k['ket'] }}</p>
                </li>
            @endforeach
        </ul>
    </section>

    <div class="mt-12 grid gap-10 lg:grid-cols-12">
        {{-- Pelanggan terbaru --}}
        <section class="lg:col-span-7" aria-labelledby="h-pelanggan">
            <h2 id="h-pelanggan" class="text-xl font-semibold tracking-tight">Pelanggan terbaru</h2>

            <ul class="mt-4 divide-y divide-hutan/15 border-y border-hutan/15">
                @forelse ($pelangganTerbaru as $i => $p)
                    <li class="baris flex items-center gap-4 py-4" style="--i: {{ $i }}">
                        <span class="grid h-11 w-11 flex-none place-items-center rounded-full bg-daun-2 font-semibold" aria-hidden="true">{{ mb_strtoupper(mb_substr($p['nama'], 0, 1)) }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-medium">{{ $p['nama'] }}</p>
                            <p class="truncate text-sm text-hutan/65">{{ $p['email'] }}</p>
                        </div>
                        <div class="text-right text-sm">
                            <p class="font-medium">{{ $p['daftar'] }}</p>
                            <p class="text-hutan/65">{{ $p['hp'] }}</p>
                        </div>
                    </li>
                @empty
                    <li class="py-12 text-center">
                        <p class="font-medium">Belum ada pelanggan terdaftar.</p>
                        <p class="mt-1 text-sm text-hutan/65">Pelanggan yang mendaftar lewat toko akan muncul di sini.</p>
                    </li>
                @endforelse
            </ul>
        </section>

        {{-- Pintasan --}}
        <section class="lg:col-span-5" aria-labelledby="h-pintasan">
            <h2 id="h-pintasan" class="text-xl font-semibold tracking-tight">Pintasan</h2>

            <ul class="mt-4 space-y-3">
                <li>
                    <a href="{{ route('toko.index') }}" class="pintas">
                        <span>
                            <strong class="block font-medium">Lihat toko</strong>
                            <span class="text-sm text-hutan/70">Tampilan yang dilihat pelanggan</span>
                        </span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
                    </a>
                </li>
                @foreach (['Kelola produk & stok' => 'Tambah varian, catat stok masuk dan keluar', 'Proses pesanan' => 'Konfirmasi, kemas, dan kirim pesanan', 'Balas ulasan' => 'Lihat dan tanggapi ulasan pelanggan'] as $judul => $ket)
                    <li>
                        <span class="pintas is-soon" aria-disabled="true">
                            <span>
                                <strong class="block font-medium">{{ $judul }}</strong>
                                <span class="text-sm text-hutan/70">{{ $ket }}</span>
                            </span>
                            <em>Segera</em>
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>
@endsection