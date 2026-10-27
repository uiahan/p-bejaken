@extends('layout.landing-layout')

@section('landing')
    @php
        /*
         * Sumber data varian untuk halaman ini (hero & kartu varian).
         * Harga, ukuran, dan kedaluwarsa belum ada di data wawancara;
         * nanti ambil dari database produk, jangan ditulis di sini.
         *
         * cairan = warna asli minuman (diambil dari foto produk), dipakai di botol hero.
         * latar  = warna lembut di belakang botol & foto.
         * teks   = warna tulisan di atas latar.
         */
        $varian = [
            [
                'slug' => 'sweet-tamarind',
                'nama' => 'Sweet Tamarind',
                'bahan' => 'Asam jawa',
                'manfaat' => 'Menyegarkan dan membantu pencernaan.',
                'cairan' => '#B4491A',
                'latar' => '#F1C7A4',
                'teks' => '#1D4A38',
            ],
            [
                'slug' => 'blue-telang',
                'nama' => 'Blue Telang',
                'bahan' => 'Bunga telang',
                'manfaat' => 'Mengandung antioksidan alami (antosianin).',
                'cairan' => '#6B1654',
                'latar' => '#DCC7E3',
                'teks' => '#1D4A38',
            ],
            [
                'slug' => 'kunyit-asem-sirih',
                'nama' => 'Kunyit Asem Sirih',
                'bahan' => 'Kunyit, asam jawa, sirih',
                'manfaat' =>
                    'Menjaga kesehatan lambung dan kulit. Kaya vitamin C, antioksidan, antiradang; sirih bersifat antiseptik.',
                'cairan' => '#D07A0A',
                'latar' => '#F4CD72',
                'teks' => '#1D4A38',
            ],
            [
                'slug' => 'red-rosella',
                'nama' => 'Red Rosella',
                'bahan' => 'Rosela',
                'manfaat' => 'Kaya vitamin C dan antioksidan.',
                'cairan' => '#8E1417',
                'latar' => '#F2B8B2',
                'teks' => '#1D4A38',
            ],
            [
                'slug' => 'beras-kencur',
                'nama' => 'Beras Kencur',
                'bahan' => 'Beras, kencur',
                'manfaat' => 'Membantu meredakan pegal dan menghangatkan badan.',
                'cairan' => '#CDA068',
                'latar' => '#E8DCC3',
                'teks' => '#1D4A38',
            ],
            [
                'slug' => 'sereh-jahe',
                'nama' => 'Sereh Jahe',
                'bahan' => 'Sereh, jahe',
                'manfaat' => 'Menghangatkan badan.',
                'cairan' => '#A15A0B',
                'latar' => '#E6D08E',
                'teks' => '#1D4A38',
            ],
        ];
        $awal = 4; // varian yang tampil pertama di hero (Kunyit Asem Sirih)
        $v0 = $varian[$awal];

        // Tetes embun di botol (acak tapi tetap, supaya tidak berubah tiap refresh)
        mt_srand(11);
        $tetes = [];
        for ($i = 0; $i < 34; $i++) {
            $sisi = mt_rand(0, 9) < 4;
            $x = $sisi ? (mt_rand(0, 1) ? mt_rand(220, 340) : mt_rand(1660, 1780)) / 10 : mt_rand(240, 1760) / 10;
            $y = $sisi ? mt_rand(1800, 3800) / 10 : mt_rand(1120, 1740) / 10;
            $tetes[] = ['x' => $x, 'y' => $y, 'r' => mt_rand(6, 20) / 10];
        }
    @endphp

    {{-- ===================== HERO ===================== --}}
    <section id="beranda" class="hero" data-hero data-start="{{ $awal }}" data-variants="{{ json_encode($varian) }}"
        style="--v: {{ $v0['latar'] }}; --v-ink: {{ $v0['teks'] }};">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-6 pb-8 pt-10 lg:grid-cols-12 lg:pb-10 lg:pt-10">
            <div class="lg:col-span-7">
                <h1 class="text-[clamp(3.1rem,8.4vw,7.4rem)] font-semibold leading-[0.96] tracking-[-0.045em]">
                    <span class="line"><span style="--d: 150ms">Minuman</span></span>
                    <span class="line"><span style="--d: 270ms">para raden.</span></span>
                </h1>

                <p class="fade-in mt-8 max-w-md text-lg leading-relaxed" style="--d: 800ms">
                    Jamu dari bahan alami, tanpa pengawet dan tanpa bahan sintetis. Diracik sesuai stok, jadi yang kamu
                    minum selalu baru.
                </p>

                <div class="fade-in mt-9 flex flex-wrap gap-3" style="--d: 950ms">
                    <a href="{{ url('/toko') }}" class="btn-solid rounded-full px-7 py-3.5 font-medium">Belanja
                        sekarang</a>
                    <a href="#chatbot" class="btn-line rounded-full px-7 py-3.5 font-medium">Bantu aku memilih</a>
                </div>
            </div>

            <div class="flex justify-center lg:col-span-5 lg:justify-end">
                <div class="bottle-in bottle w-[min(56vw,230px)] lg:w-[min(100%,380px,31svh)]"
                    style="--b: {{ $v0['cairan'] }}" data-bottle>
                    {{-- Botol PET 250 ml, mengikuti foto produk --}}
                    <svg viewBox="0 0 200 430" role="img" aria-label="Botol jamu Cap Jamu Raden 250 ml"
                        class="block h-auto w-full overflow-visible">
                        <defs>
                            <clipPath id="kaca">
                                <path
                                    d="M52 76V92C52 112 20 112 20 140V395Q20 424 50 424H150Q180 424 180 395V140C180 112 148 112 148 92V76Z" />
                            </clipPath>
                        </defs>

                        {{-- cairan, warna asli --}}
                        <g clip-path="url(#kaca)">
                            <g id="liquid">
                                <g class="wave">
                                    <path class="liq"
                                        d="M-100 88Q-75 84 -50 88T0 88T50 88T100 88T150 88T200 88T250 88T300 88V450H-100Z" />
                                </g>
                            </g>
                            <rect x="20" y="100" width="9" height="330" fill="#000" fill-opacity=".14" />
                            <rect x="171" y="100" width="9" height="330" fill="#000" fill-opacity=".14" />
                            @foreach ($tetes as $t)
                                <circle cx="{{ $t['x'] }}" cy="{{ $t['y'] }}" r="{{ $t['r'] }}"
                                    fill="#fff" fill-opacity=".34" />
                            @endforeach
                            <rect x="25" y="132" width="4.5" height="240" rx="2.2" fill="#fff"
                                fill-opacity=".3" />
                        </g>
                        <path
                            d="M52 76V92C52 112 20 112 20 140V395Q20 424 50 424H150Q180 424 180 395V140C180 112 148 112 148 92V76Z"
                            fill="none" stroke="currentColor" stroke-opacity=".28" stroke-width="1.4"
                            stroke-linejoin="round" />

                        {{-- label krem --}}
                        <path d="M38 184Q38 176 46 176H71Q100 158 129 176H154Q162 176 162 184V398H38Z" fill="#EEE6D6" />
                        <path d="M42.5 186Q42.5 180.5 47.5 180.5H72Q100 163 128 180.5H152.5Q157.5 180.5 157.5 186V394H42.5Z"
                            fill="none" stroke="#C99A3B" stroke-width=".6" />
                        <text x="100" y="191" text-anchor="middle" font-size="5.4" font-weight="600" letter-spacing=".5"
                            fill="#1D4A38">MINUMAN HERBAL</text>
                        <image href="{{ asset('images/brand/label-logo.png') }}" x="58" y="195" width="84"
                            height="92.9" />
                        <text x="100" y="257.5" text-anchor="middle" font-size="4.6" font-weight="600" letter-spacing=".4"
                            fill="#1D4A38">CAP</text>
                        <image href="{{ asset('images/brand/label-ilustrasi.png') }}" x="54" y="293" width="92"
                            height="52" />
                        <path d="M38 352H70Q100 341 130 352H162V398H38Z" fill="#1D4A38" />
                        <text x="100" y="369" text-anchor="middle" font-size="4" font-weight="500" letter-spacing=".5"
                            fill="#EEE6D6">GOALPARA - SUKABUMI</text>
                        <text x="100" y="383" text-anchor="middle" font-size="6.2" font-weight="500" fill="#EEE6D6">250
                            ml</text>

                        {{-- tutup hijau --}}
                        <rect x="44" y="72" width="112" height="6" rx="3" fill="#fff" fill-opacity=".5" />
                        <path d="M38 30V62Q38 74 50 74H150Q162 74 162 62V30Z" fill="#17492F" />
                        <path
                            d="M45 32V72M51 32V73M57 32V73.5M63 32V74M69 32V74M75 32V74M81 32V74M87 32V74M93 32V74M99 32V74M105 32V74M111 32V74M117 32V74M123 32V74M129 32V74M135 32V73.5M141 32V73M147 32V72M153 32V72"
                            stroke="#0C3320" stroke-opacity=".6" stroke-width="1.4" />
                        <path d="M42 33V68M154 33V68" stroke="#4D8A68" stroke-opacity=".5" stroke-width="2"
                            stroke-linecap="round" />
                        <ellipse cx="100" cy="30" rx="62" ry="13" fill="#1F5C3C" />
                        <ellipse cx="100" cy="29.5" rx="41" ry="8.6" fill="#F3EEE2"
                            stroke="var(--b)" stroke-width="1.4" />
                        <text x="100" y="31.5" text-anchor="middle" font-size="5.2" font-weight="600" fill="#1D4A38"
                            data-label-name>{{ $v0['nama'] }}</text>
                    </svg>
                </div>
            </div>
        </div>

        {{-- pemilih varian --}}
        <div class="fade-in mx-auto max-w-7xl px-6 pb-12 lg:pb-16" style="--d: 1100ms">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between lg:gap-12">
                <div class="max-w-md lg:min-h-[4.5rem]" aria-live="polite">
                    <p class="text-[15px] leading-relaxed">
                        <span class="font-semibold" data-note-name>{{ $v0['nama'] }}</span><span
                            class="font-semibold">.</span>
                        <span data-note-text>{{ $v0['manfaat'] }}</span>
                    </p>
                </div>

                <div class="-mx-6 overflow-x-auto px-6 pb-1 lg:mx-0 lg:overflow-visible lg:px-0" role="group"
                    aria-label="Pilih varian">
                    <div class="flex w-max gap-2 lg:w-auto lg:flex-wrap lg:justify-end">
                        @foreach ($varian as $i => $v)
                            <button type="button"
                                class="pick flex items-center gap-2.5 whitespace-nowrap rounded-full py-2.5 pl-3.5 pr-5 text-[15px] font-medium"
                                style="--c: {{ $v['cairan'] }}" data-pick
                                aria-pressed="{{ $i === $awal ? 'true' : 'false' }}">
                                <span class="pick-dot" aria-hidden="true"></span>
                                {{ $v['nama'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CERITA ===================== --}}
    <section id="cerita" class="bg-daun">
        <div class="mx-auto max-w-7xl px-6 py-24 lg:py-36">
            <div class="grid gap-12 lg:grid-cols-12 lg:gap-16">
                <h2
                    class="text-[clamp(2.2rem,5vw,4.2rem)] font-semibold leading-[1.04] tracking-[-0.035em] text-balance lg:col-span-7">
                    Cita rasa darah biru, untuk siapa saja.
                </h2>
                <div class="space-y-5 text-lg leading-relaxed lg:col-span-5 lg:pt-3">
                    <p>
                        Kami menamainya Raden karena jamu ini diposisikan sebagai minuman para bangsawan. Siapa pun boleh
                        ikut merasakan cita rasa kalangan darah biru, tanpa harus jadi bangsawan.
                    </p>
                    <p class="text-hutan/75">
                        Semua varian dibuat dari bahan alami. Tidak ada pengawet, tidak ada bahan sintetis.
                    </p>
                </div>
            </div>

            <ol class="mt-20 grid gap-10 md:grid-cols-3 md:gap-8 lg:mt-28">
                @foreach ([['2019', 'Cap Jamu Raden dirintis, saat banyak orang mulai peduli pada kesehatan di masa pandemi.'], ['2022', 'Produksi berhenti sementara.'], ['2026', 'Produksi kembali, dan kini bisa dipesan lewat toko online.']] as [$tahun, $teks])
                    <li class="border-t-2 border-hutan pt-5">
                        <p class="text-4xl font-semibold tracking-tight">{{ $tahun }}</p>
                        <p class="mt-3 max-w-xs leading-relaxed text-hutan/75">{{ $teks }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ===================== VARIAN ===================== --}}
    <section id="varian" class="bg-daun-2">
        <div class="mx-auto max-w-7xl px-6 py-24 lg:py-32">
            <div class="grid gap-6 lg:grid-cols-12 lg:gap-16">
                <h2
                    class="text-[clamp(2.2rem,5vw,4.2rem)] font-semibold leading-[1.04] tracking-[-0.035em] text-balance lg:col-span-7">
                    Enam varian, pilih sesuai kebutuhanmu.
                </h2>
                <p class="max-w-sm leading-relaxed text-hutan/75 lg:col-span-5 lg:pt-3">
                    Manfaat di bawah ini bersifat tradisional dan bisa berbeda pada tiap orang.
                </p>
            </div>

            <ul class="mt-14 grid grid-cols-2 gap-x-4 gap-y-12 lg:mt-20 lg:grid-cols-3 lg:gap-x-8 lg:gap-y-16">
                @foreach ($varian as $v)
                    <li>
                        <a href="{{ url('/toko') }}?varian={{ $v['slug'] }}" class="varian-card group block"
                            style="--c: {{ $v['latar'] }}; --b: {{ $v['cairan'] }}">
                            <div class="foto">
                                <img src="{{ asset('images/produk/' . $v['slug'] . '.webp') }}"
                                    alt="Botol jamu {{ $v['nama'] }} 250 ml" width="360" height="800"
                                    loading="lazy">
                            </div>
                            <div class="mt-5">
                                <h3 class="text-xl font-semibold tracking-tight lg:text-2xl">{{ $v['nama'] }}</h3>
                                <p class="mt-0.5 text-sm text-hutan/65 lg:text-[15px]">{{ $v['bahan'] }}</p>
                                <p class="mt-3 text-[15px] leading-relaxed lg:text-base">{{ $v['manfaat'] }}</p>
                                <span class="go mt-4 inline-block font-medium">Lihat di toko</span>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ===================== KESEGARAN ===================== --}}
    <section id="segar" class="bg-daun">
        <div class="mx-auto grid max-w-7xl gap-16 px-6 py-24 lg:grid-cols-12 lg:gap-20 lg:py-36">
            <div class="lg:col-span-6">
                <h2 class="text-[clamp(2.2rem,5vw,4.2rem)] font-semibold leading-[1.04] tracking-[-0.035em] text-balance">
                    Segar karena diracik sesuai stok.
                </h2>
                <p class="mt-7 max-w-md text-lg leading-relaxed">
                    Tanpa pengawet berarti jamu tidak bisa disimpan lama. Karena itu kami memproduksi sesuai stok, supaya
                    yang sampai ke kamu selalu baru.
                </p>

                <ul class="mt-9 space-y-4 font-medium">
                    @foreach (['Dari bahan alami', 'Tanpa pengawet', 'Tanpa bahan sintetis'] as $poin)
                        <li class="flex items-center gap-3.5">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12.5l4.5 4.5L19 7.5" />
                            </svg>
                            {{ $poin }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="lg:col-span-6 lg:pt-4" data-bars>
                <p class="font-semibold">Berapa lama bisa disimpan?</p>

                <div class="mt-8 space-y-9">
                    <div>
                        <div class="mb-3 flex items-baseline justify-between gap-4">
                            <p class="text-lg">Di kulkas</p>
                            <p class="text-lg font-semibold">7 sampai 10 hari</p>
                        </div>
                        <div class="flex h-5 overflow-hidden rounded-full bg-hutan/10">
                            <span class="seg bg-hutan" style="--w: 70%"></span>
                            <span class="seg bg-hutan/40" style="--w: 30%; --delay: .5s"></span>
                        </div>
                    </div>

                    <div>
                        <div class="mb-3 flex items-baseline justify-between gap-4">
                            <p class="text-lg">Di suhu ruang</p>
                            <p class="text-lg font-semibold">2 sampai 3 hari</p>
                        </div>
                        <div class="flex h-5 overflow-hidden rounded-full bg-hutan/10">
                            <span class="seg bg-kunyit" style="--w: 20%; --delay: .2s"></span>
                            <span class="seg bg-kunyit/45" style="--w: 10%; --delay: .7s"></span>
                        </div>
                        <p class="mt-3 text-sm text-hutan/70">Dihitung selama tutup botol belum dibuka.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== BUKAN OBAT ===================== --}}
    <section class="kawung bg-hutan text-daun">
        <div class="mx-auto grid max-w-7xl gap-14 px-6 py-24 lg:grid-cols-12 lg:gap-16 lg:py-36">
            <div class="lg:col-span-7">
                <h2 class="text-[clamp(2.4rem,5.4vw,4.8rem)] font-semibold leading-[1.02] tracking-[-0.04em] text-balance">
                    Ini minuman herbal, bukan obat.
                </h2>
                <p class="mt-8 max-w-lg text-lg leading-relaxed text-daun/80">
                    Jamu ini untuk menjaga kesehatan dan pencegahan. Kalau sedang sakit atau punya kondisi medis, tanyakan
                    dulu ke dokter.
                </p>
            </div>

            <div class="lg:col-span-5 lg:pt-4">
                <p class="font-semibold">Biasa diminum oleh</p>
                <ul class="mt-5 divide-y divide-daun/20 border-y border-daun/20 text-lg">
                    <li class="py-4">Ibu muda, untuk anak</li>
                    <li class="py-4">Remaja hingga dewasa</li>
                    <li class="py-4">Perempuan yang sedang haid</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- ===================== CHATBOT ===================== --}}
    <section id="chatbot" class="bg-daun-2">
        <div class="mx-auto grid max-w-7xl items-center gap-14 px-6 py-24 lg:grid-cols-12 lg:gap-20 lg:py-36">
            <div class="lg:col-span-5">
                <h2 class="text-[clamp(2.2rem,5vw,4.2rem)] font-semibold leading-[1.04] tracking-[-0.035em] text-balance">
                    Bingung pilih yang mana? Ceritakan keluhanmu.
                </h2>
                <p class="mt-7 max-w-md text-lg leading-relaxed">
                    Tulis kebutuhanmu, misalnya haid, lambung, atau bau badan. Chatbot kami menyarankan varian yang cocok.
                </p>
                <a href="{{ url('/chatbot') }}"
                    class="mt-9 inline-block rounded-full bg-hutan px-7 py-3.5 font-medium text-daun transition hover:bg-hutan-deep">Mulai
                    tanya</a>
            </div>

            <div class="lg:col-span-7">
                <div class="mx-auto max-w-xl rounded-[28px] bg-daun p-5 sm:p-7" data-chat
                    aria-label="Contoh percakapan dengan chatbot">
                    <div class="flex flex-col gap-3 text-[15px] leading-relaxed">
                        <div class="msg ml-auto max-w-[80%] rounded-2xl rounded-br-md bg-hutan px-4 py-3 text-daun"
                            data-step="user">
                            Lagi haid, perut rasanya pegal. Enaknya minum apa?
                        </div>

                        <div class="msg max-w-[88%] rounded-2xl rounded-bl-md bg-daun-2 px-4 py-3" data-step="bot">
                            <span class="dots" aria-hidden="true"><i></i><i></i><i></i></span>
                            <span class="txt block">
                                Coba <strong class="font-semibold">Beras Kencur</strong>. Secara tradisional membantu
                                meredakan pegal dan menghangatkan badan. <strong class="font-semibold">Sereh Jahe</strong>
                                juga bisa untuk menghangatkan badan.
                            </span>
                        </div>

                        <div class="msg ml-auto mt-2 max-w-[80%] rounded-2xl rounded-br-md bg-hutan px-4 py-3 text-daun"
                            data-step="user">
                            Kalau untuk lambung dan bau badan?
                        </div>

                        <div class="msg max-w-[88%] rounded-2xl rounded-bl-md bg-daun-2 px-4 py-3" data-step="bot">
                            <span class="dots" aria-hidden="true"><i></i><i></i><i></i></span>
                            <span class="txt block">
                                <strong class="font-semibold">Kunyit Asem Sirih</strong>. Secara tradisional menjaga
                                kesehatan lambung dan kulit, dan sirih bersifat antiseptik.
                            </span>
                        </div>
                    </div>
                    <p class="mt-6 text-sm text-hutan/65">Saran berdasarkan manfaat tradisional, bukan saran medis.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== BELI ===================== --}}
    <section id="beli" class="bg-[#E8DCC3] text-hutan">
        <div class="mx-auto max-w-7xl px-6 py-24 lg:py-36">
            <h2
                class="max-w-5xl text-[clamp(2.4rem,5.4vw,4.8rem)] font-semibold leading-[1.02] tracking-[-0.04em] text-balance">
                Pesan dari rumah, atau ambil di toko mitra.
            </h2>

            <div class="mt-16 grid gap-12 md:grid-cols-2 lg:mt-20 lg:gap-20">
                <div>
                    <h3 class="text-2xl font-semibold tracking-tight">Toko online</h3>
                    <ul class="mt-5 space-y-3 text-lg leading-relaxed text-hutan/85">
                        <li>Buka 24 jam, dari mana saja.</li>
                        <li>Bayar dengan QRIS, transfer, atau metode lain.</li>
                        <li>Isi alamat, pilih pengiriman, dan pantau status pesananmu.</li>
                    </ul>
                    <a href="{{ url('/toko') }}"
                        class="mt-9 inline-block rounded-full bg-hutan px-7 py-3.5 font-medium text-daun transition hover:bg-hutan-deep">Belanja
                        sekarang</a>
                </div>

                <div>
                    <h3 class="text-2xl font-semibold tracking-tight">Toko mitra</h3>
                    <p class="mt-5 text-lg leading-relaxed text-hutan/85">
                        Kamu juga bisa menemukan Cap Jamu Raden di:
                    </p>
                    <ul class="mt-4 divide-y divide-daun/25 border-y border-daun/25 text-lg">
                        <li class="py-4">Mochi Lampion</li>
                        <li class="py-4">Mochi Mandiri</li>
                        <li class="py-4">Dapur Mungil</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
