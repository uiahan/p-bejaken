<footer class="bg-hutan-deep text-daun">

    <div class="mx-auto grid max-w-7xl gap-12 px-6 py-16 md:grid-cols-12 lg:py-20">

        {{-- Identitas brand --}}
        <div class="md:col-span-6">

            <img
                src="{{ asset('images/brand/logo-horizontal-light.png') }}"
                alt="Cap Jamu Raden"
                width="1000"
                height="183"
                class="h-11 w-auto">

            <p class="mt-5 max-w-sm text-[15px] leading-relaxed text-daun/70">
                Minuman herbal dari bahan alami. Fungsinya menjaga kesehatan,
                bukan obat.
            </p>

        </div>

        {{-- Navigasi footer --}}
        <div class="md:col-span-3">

            <p class="font-medium">Jelajahi</p>

            <ul class="mt-4 space-y-3 text-[15px] text-daun/70">
                <li><a href="#cerita" class="transition hover:text-daun">Cerita</a></li>
                <li><a href="#varian" class="transition hover:text-daun">Varian</a></li>
                <li><a href="#segar" class="transition hover:text-daun">Kesegaran</a></li>
                <li><a href="{{ url('/toko') }}" class="transition hover:text-daun">Toko online</a></li>
            </ul>

        </div>

        {{-- Kontak --}}
        <div class="md:col-span-3">

            <p class="font-medium">Hubungi kami</p>

            <ul class="mt-4 space-y-3 text-[15px] text-daun/70">
                {{-- Ganti dengan tautan akun resmi --}}
                <li><a href="#" class="transition hover:text-daun">Instagram</a></li>
                <li><a href="#" class="transition hover:text-daun">WhatsApp</a></li>
            </ul>

        </div>

    </div>

    {{-- Copyright --}}
    <div class="border-t border-daun/15">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-6 py-6 text-sm text-daun/60 sm:flex-row sm:justify-between">

            <p>&copy; {{ date('Y') }} Cap Jamu Raden</p>

            <p>
                Manfaat varian bersifat tradisional dan bisa berbeda pada tiap orang.
            </p>

        </div>
    </div>

</footer>