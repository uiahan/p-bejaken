@extends('layout.ecommerce-layout')

@section('ecommerce')
@php
    /*
     * Data produk datang dari TokoController: $katalog (produk berstatus aktif) dan $filter (chip kebutuhan).
     * Foto dari kolom `gambar`; warna latar & kategori filter dihitung otomatis oleh model Produk.
     */
    $ulasan = []; // nanti isi dari tabel ulasan: nama, rating, komentar
    $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $ada = $katalog->isNotEmpty();
@endphp

{{-- ===================== RAK VARIAN ===================== --}}
<section class="rak-band" data-rak-band>
    <div class="mx-auto max-w-7xl px-6 pb-14 pt-12 lg:pb-16 lg:pt-16">
        <div>
            <h1 class="text-[clamp(2.6rem,6.4vw,5.6rem)] font-semibold leading-[0.98] tracking-[-0.045em]">
                <span class="line"><span style="--d: 100ms">Pilih varian jamu anda</span></span>
                <span class="line"><span style="--d: 220ms">untuk hari ini.</span></span>
            </h1>
        </div>

        @if ($ada)
            <div class="rak mt-10 lg:mt-14">
                <div class="rak-scroll {{ $katalog->count() > 8 ? 'rak-banyak' : '' }}">
                <ul class="rak-isi" style="--n: {{ $katalog->count() }}">
                    @foreach ($katalog as $i => $p)
                        <li style="--i: {{ $i }}">
                            <button type="button" class="rak-item" data-rak data-target="#p-{{ $p->id }}" data-latar="{{ $p->latar }}" data-nama="{{ $p->nama_produk }}" data-harga="{{ $rp($p->harga) }}" aria-label="{{ $p->nama_produk }}, lihat di katalog">
                                <span class="foto">
                                    @if ($p->gambar_url)
                                        <img src="{{ $p->gambar_url }}" alt="" width="360" height="800">
                                    @else
                                        <span class="foto-kosong" aria-hidden="true">{{ mb_strtoupper(mb_substr($p->nama_produk, 0, 1)) }}</span>
                                    @endif
                                </span>
                                <span class="nama">{{ $p->nama_produk }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
                </div>
                <p class="rak-note mt-5 text-[15px]" data-rak-note aria-live="off">
                    <span>Arahkan ke botol untuk melihat harga.</span>
                </p>
            </div>
        @endif
    </div>
</section>

{{-- ===================== KATALOG ===================== --}}
<section id="katalog" class="bg-daun">
    <div class="mx-auto max-w-7xl px-6 py-20 lg:py-28">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
            <h2 class="text-[clamp(2.2rem,5vw,4rem)] font-semibold leading-[1.04] tracking-[-0.035em] text-balance">
                Semua varian
            </h2>

            {{-- Chip hanya muncul untuk kebutuhan yang dimiliki minimal satu produk --}}
            @if ($ada && count($filter) > 1)
                <div class="-mx-6 overflow-x-auto px-6 pb-1 lg:mx-0 lg:overflow-visible lg:px-0" role="group" aria-label="Filter kebutuhan">
                    <div class="flex w-max gap-2 lg:w-auto lg:flex-wrap lg:justify-end">
                        @foreach ($filter as $kunci => $label)
                            <button type="button" class="chip whitespace-nowrap rounded-full px-5 py-2.5 text-[15px] font-medium" data-filter="{{ $kunci }}" aria-pressed="{{ $kunci === 'semua' ? 'true' : 'false' }}">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if ($ada)
            <p class="mt-5 text-sm text-hutan/65" data-hasil aria-live="polite">Menampilkan {{ $katalog->count() }} dari {{ $katalog->count() }} varian</p>

            <ul class="mt-10 grid grid-cols-2 gap-x-4 gap-y-12 lg:grid-cols-3 lg:gap-x-8 lg:gap-y-16">
                @foreach ($katalog as $p)
                    @php($habis = $p->stok <= 0)
                    <li id="p-{{ $p->id }}" class="produk flex flex-col {{ $habis ? 'habis' : '' }}" data-produk data-tags="{{ implode(' ', $p->tags) }}">
                        <a href="{{ url('/produk/' . $p->slug) }}" class="foto" style="--c: {{ $p->latar }}" aria-label="Lihat detail {{ $p->nama_produk }}">
                            @if ($habis)
                                <span class="tag">Stok habis</span>
                            @elseif ($p->stok <= \App\Models\Produk::AMBANG_MENIPIS)
                                <span class="tag">Sisa {{ $p->stok }} botol</span>
                            @endif
                            @if ($p->gambar_url)
                                <img src="{{ $p->gambar_url }}" alt="Botol jamu {{ $p->nama_produk }}" width="360" height="800" loading="lazy">
                            @else
                                <span class="foto-kosong" aria-hidden="true">{{ mb_strtoupper(mb_substr($p->nama_produk, 0, 1)) }}</span>
                            @endif
                        </a>

                        <div class="mt-5 flex flex-1 flex-col">
                            <h3 class="text-xl font-semibold tracking-tight lg:text-2xl">
                                <a href="{{ url('/produk/' . $p->slug) }}">{{ $p->nama_produk }}</a>
                            </h3>
                            <p class="mt-0.5 line-clamp-1 text-sm text-hutan/65 lg:text-[15px]">{{ $p->deskripsi }}</p>
                            <p class="mt-3 line-clamp-2 text-[15px] leading-relaxed lg:text-base">{{ $p->khasiat }}</p>
                            <p class="mt-auto pt-4 text-xl font-semibold tracking-tight">{{ $rp($p->harga) }}</p>

                            <button type="button" class="btn-tambah mt-4 w-full rounded-full bg-hutan px-5 py-3.5 font-medium text-daun" data-add
                                data-id="{{ $p->id }}" data-slug="{{ $p->slug }}" data-nama="{{ $p->nama_produk }}" data-harga="{{ (int) $p->harga }}" data-stok="{{ $p->stok }}"
                                data-foto="{{ $p->gambar_url }}" data-latar="{{ $p->latar }}"
                                @disabled($habis)>
                                <span data-label>{{ $habis ? 'Stok habis' : 'Tambah ke keranjang' }}</span>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-16 hidden max-w-md" data-filter-kosong hidden>
                <p class="text-xl font-semibold tracking-tight">Belum ada varian untuk kebutuhan ini.</p>
                <p class="mt-2 leading-relaxed text-hutan/70">Pilih "Semua" atau tanyakan ke chatbot.</p>
            </div>
        @else
            <div class="mt-12 max-w-md">
                <p class="text-xl font-semibold tracking-tight">Belum ada produk yang dijual.</p>
                <p class="mt-2 leading-relaxed text-hutan/70">Varian jamu akan tampil di sini setelah ditambahkan dari dashboard.</p>
            </div>
        @endif
    </div>
</section>

{{-- ===================== CHATBOT ===================== --}}
<section class="kawung bg-hutan text-daun">
    <div class="mx-auto flex max-w-7xl flex-col gap-8 px-6 py-20 lg:flex-row lg:items-center lg:justify-between lg:py-24">
        <h2 class="max-w-2xl text-[clamp(2rem,4.4vw,3.6rem)] font-semibold leading-[1.05] tracking-[-0.035em] text-balance">
            Bingung pilih yang mana? Ceritakan keluhanmu.
        </h2>
        <div class="max-w-xs lg:text-right">
            <p class="leading-relaxed text-daun/80">Chatbot kami menyarankan varian dari manfaat tradisionalnya.</p>
            <a href="{{ url('/chatbot') }}" class="mt-6 inline-block rounded-full bg-emas px-7 py-3.5 font-medium text-hutan transition hover:bg-daun">Mulai tanya</a>
        </div>
    </div>
</section>

{{-- ===================== ULASAN ===================== --}}
<section class="bg-daun-2">
    <div class="mx-auto max-w-7xl px-6 py-20 lg:py-28">
        <h2 class="text-[clamp(2rem,4.4vw,3.4rem)] font-semibold leading-[1.05] tracking-[-0.035em] text-balance">
            Kata pelanggan
        </h2>

        @forelse ($ulasan as $u)
            <figure class="mt-10 max-w-2xl border-t border-hutan/25 py-6">
                <p class="font-semibold" aria-label="Rating {{ $u['rating'] }} dari 5">{{ str_repeat('★', $u['rating']) }}{{ str_repeat('☆', 5 - $u['rating']) }}</p>
                <blockquote class="mt-2 text-lg leading-relaxed">{{ $u['komentar'] }}</blockquote>
                <figcaption class="mt-2 text-sm text-hutan/65">{{ $u['nama'] }}</figcaption>
            </figure>
        @empty
            <div class="mt-8 max-w-md">
                <p class="text-xl font-semibold tracking-tight">Belum ada ulasan.</p>
                <p class="mt-2 leading-relaxed text-hutan/75">
                    Setelah pesananmu sampai, kamu bisa memberi rating dan komentar dari halaman Pesanan saya.
                </p>
                <a href="{{ url('/pesanan') }}" class="mt-6 inline-block rounded-full px-6 py-3 font-medium shadow-[inset_0_0_0_1.5px_var(--color-hutan)] transition hover:bg-hutan hover:text-daun">Lihat pesananku</a>
            </div>
        @endforelse
    </div>
</section>
@endsection