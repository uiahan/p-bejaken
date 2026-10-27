<div class="toaster" data-toaster aria-live="polite" aria-relevant="additions"></div>

@if (session()->has('toast'))
    @php
        $flash = session('toast');
        $flash = is_string($flash) ? [['type' => 'success', 'message' => $flash]] : $flash;
        $flash = isset($flash['message']) ? [$flash] : $flash;
    @endphp
    <script type="application/json" data-toast-flash>@json($flash, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)</script>
@endif