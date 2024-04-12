<nav class="navbar sticky-top navbar-expand-lg bg-primary" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand" href="{{ config('app.url') }}">
            <img src="{{ asset('logo.svg') }}" alt="Logo" class="d-inline-block align-text-top"
                height="50vh">&nbsp;<span class="ms-2 fw-medium fs-4">{{ config('app.name') }}</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
            aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <div class="navbar-nav ms-auto">
                <a class="nav-link @if (Route::is('aedinfo')) active @endif"
                    href="{{ route('aedinfo') }}">{{ __('pages.what_is_aed') }}</a>
                <a class="nav-link @if (Route::is('docs')) active @endif"
                    href="{{ route('docs') }}">{{ __('pages.api') }}</a>
                <a class="nav-link @if (Route::is('statistics')) active @endif"
                    href="{{ route('statistics') }}">{{ __('pages.statistics') }}</a>
                <a class="nav-link @if (Route::is('contact')) active @endif"
                    href="{{ route('about-us') }}">{{ __('pages.about_us') }}</a>
                <a target="_blank" href="https://patreon.com/OpenAED" class="nav-link" title="Patreon">Patreon</a>
                <a target="_blank" href="https://github.com/openaed" class="nav-link" title="GitHub"><i
                        class="bi bi-github"></i></a>
                <div class="ms-5">
                    <x-language-select />
                </div>
            </div>
        </div>
    </div>
    <div class="navbar-nav ms-0">
        <a target="_blank" href="https://ptdk.nl" class="nav-link d-none d-md-block me-3" title="potato donkey"><img
                src="https://www.ptdk.nl/assets/logo-white.svg" alt="potato donkey" style="height: 3em"></a>
    </div>
</nav>
