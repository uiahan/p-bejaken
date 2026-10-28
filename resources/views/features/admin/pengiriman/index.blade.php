@extends('layout.admin-layout')

@section('title', 'Pengiriman | Cap Jamu Raden')

@section('admin')
    <header class="flex flex-wrap items-end justify-between gap-5">
        <div>
            <p class="fade-in text-sm font-medium text-hutan/65" style="--d: 100ms">
                Manajemen distribusi
            </p>

            <h1 class="mt-2 text-[clamp(2rem,4vw,2.9rem)] font-semibold leading-[1.05] tracking-[-0.035em]">
                <span class="line"><span style="--d: 200ms">Pengiriman</span></span>
            </h1>

            <p class="fade-in mt-3 text-sm text-hutan/65" style="--d: 300ms">
                Atur metode pengiriman, ongkir, alamat, dan status pengantaran.
            </p>
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

    <div class="fade-in mt-9 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between"
         style="--d: 450ms">
        <nav class="-mx-5 flex gap-2 overflow-x-auto px-5 pb-1 lg:mx-0 lg:px-0"
             aria-label="Filter status pengiriman">
            @foreach ([
                '' => 'Semua',
                'menunggu' => 'Menunggu',
                'diproses' => 'Diproses',
                'dikirim' => 'Dikirim',
                'diterima' => 'Diterima',
            ] as $key => $label)
                <a href="{{ route('admin.pengiriman.index', array_filter([
                        'status' => $key,
                        'q' => request('q'),
                    ], fn ($value) => $value !== null && $value !== '')) }}"
                   class="chip shrink-0 rounded-full px-5 py-2.5 text-[15px] font-medium"
                   aria-current="{{ request('status', '') === $key ? 'page' : 'false' }}">
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <form method="GET"
              action="{{ route('admin.pengiriman.index') }}"
              role="search"
              class="search lg:w-80">
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <label for="cari-pengiriman" class="sr-only">
                Cari pengiriman
            </label>

            <div class="flex items-center gap-2.5 rounded-full px-4 py-3">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     aria-hidden="true">
                    <circle cx="11" cy="11" r="6.5"/>
                    <path d="m16 16 4.5 4.5"/>
                </svg>

                <input id="cari-pengiriman"
                       name="q"
                       type="search"
                       value="{{ request('q') }}"
                       placeholder="Cari pengiriman"
                       class="w-full bg-transparent text-[15px] outline-none placeholder:text-hutan/50">
            </div>
        </form>
    </div>

    <section class="mt-8" aria-label="Daftar pengiriman">
        <div class="overflow-x-auto rounded-2xl border-y border-hutan/15">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="tabel-kepala">
                    <tr>
                        <th class="px-4 py-4">Pengiriman</th>
                        <th class="px-4 py-4">Transaksi</th>
                        <th class="px-4 py-4">Pelanggan</th>
                        <th class="px-4 py-4">Metode</th>
                        <th class="px-4 py-4">Ongkir</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-hutan/15">
                    @forelse ($pengirimen as $pengiriman)
                        <tr class="baris cepat" style="--i: {{ $loop->index }}">
                            <td class="px-4 py-5 whitespace-nowrap font-medium">
                                #{{ $pengiriman->id }}
                            </td>

                            <td class="px-4 py-5 whitespace-nowrap">
                                #{{ $pengiriman->transaksi_id }}
                            </td>

                            <td class="px-4 py-5">
                                {{ $pengiriman->transaksi?->user?->name ?? 'Pengguna tidak tersedia' }}
                            </td>

                            <td class="px-4 py-5">
                                {{ $pengiriman->metode }}
                            </td>

                            <td class="px-4 py-5 whitespace-nowrap font-medium">
                                Rp{{ number_format((float) $pengiriman->ongkir, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-5">
                                <span class="role-pill" data-role="{{ $pengiriman->status }}">
                                    {{ ucfirst($pengiriman->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.pengiriman.show', $pengiriman) }}"
                                       class="aksi">
                                        Detail
                                    </a>

                                    <a href="{{ route('admin.pengiriman.edit', $pengiriman) }}"
                                       class="aksi">
                                        Ubah
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <p class="font-medium">Belum ada pengiriman.</p>
                                <p class="mt-1 text-sm text-hutan/65">
                                    Data pengiriman akan muncul setelah pelanggan checkout dengan pilihan diantar.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="mt-4 text-sm text-hutan/65">
            Menampilkan {{ $pengirimen->count() }} dari {{ $pengirimen->total() }} pengiriman.
        </p>

        {{ $pengirimen->links('components.admin.pagination') }}
    </section>
@endsection