<div class="dropdown">
    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ asset('flags/' . app()->getLocale() . '.svg') }}" width="25"
            class="me-2 border border-1 border-dark">
        {{ config('app.available_locales')[app()->getLocale()] }}
    </button>
    <ul class="dropdown-menu dropdown-menu-light">
        @foreach (config('app.available_locales') as $key => $locale)
            <li><a class="dropdown-item @if (app()->getLocale() == $key) fw-bold @endif"
                    href="{{ route('lang.switch', [$key]) }}"><img src="{{ asset('flags/' . $key . '.svg') }}"
                        width="25" class="me-2 border border-1 border-dark">{{ $locale }}</a>
            </li>
        @endforeach
    </ul>
</div>
