@extends('pages.layout')

@section('title', __('pages.api'))

@section('content')
    <h1 class="mt-5">{{ __('pages.api') }}</h1>

    @switch(app()->getLocale())
        @case ('nl')
            <p>
                De OpenAED API is beschikbaar gemaakt onder de <a target="_blank"
                    href="https://opendatacommons.org/licenses/odbl/1.0/">Open Database
                    License v1.0</a>. Dit omdat de data geleverd wordt door <a target="_blank"
                    href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>.
            </p>

            <h2>Toegang</h2>
            <p>Om data te gebruiken van OpenAED is een API-sleutel nodig. Deze wordt meegestuurd in de query parameter
                <code>key</code>. Een API-sleutel kan aangevraagd worden door een e-mail te sturen naar <a
                    href="mailto:api@openaed.eu">api@openaed.eu</a>. In de e-mail moet u uw naam, organisatie (indien van toepassing)
                en het beoogde gebruik van de API vermelden.
            </p>
        @break

        @case ('en')
            <p>
                The OpenAED API is made available under the <a target="_blank"
                    href="https://opendatacommons.org/licenses/odbl/1.0/">Open Database License v1.0</a>. This is because the data is
                provided by <a target="_blank" href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>.
            </p>

            <h2>Access</h2>
            <p>To use data from OpenAED, an API key is required. This key is sent in the query parameter <code>key</code>. An API
                key
                can be requested by sending an e-mail to <a href="mailto:api@openaed.eu">api@openaed.eu</a>. In the e-mail, you must
                state your name, organization (if applicable), and the intended use of the API.
            </p>
        @break

        @case ('de')
            <p>
                Die OpenAED-API wird unter der <a target="_blank" href="https://opendatacommons.org/licenses/odbl/1.0/">Open
                    Database License v1.0</a> zur Verfügung gestellt. Dies
                liegt daran, dass die Daten von <a target="_blank" href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>
                bereitgestellt werden.
            </p>

            <h2>Zugang</h2>
            <p>Um Daten von OpenAED zu verwenden, ist ein API-Schlüssel erforderlich. Dieser wird im Abfrageparameter
                <code>key</code>
                gesendet. Ein API-Schlüssel kann per E-Mail an <a href="mailto:api@openaed.eu">api@openaed.eu</a> angefordert
                werden. In
                der E-Mail müssen Sie Ihren Namen, Ihre Organisation (falls zutreffend) und den beabsichtigten Verwendungszweck der
                API angeben.
            </p>
        @break

        @default
            <p>{{ __('common.page_trans_not_available') }}</p>
    @endswitch

    <div class="row">
        <div class="col">
            <h3>Endpoints</h3>
            <ul class="ps-3">
                <li>{{ __('api.all_aeds') }}<br><code>GET {{ route('api.aed.all') }}</code></li>
                <li>{{ __('api.all_aeds_city') }}<br><code>GET
                        {{ str_replace('city', '{city}', str_replace('region', '{region}', route('api.aed.city', ['city' => 'city', 'region' => 'region']))) }}</code>
                </li>
                <li>{{ __('api.all_aeds_region') }}<br><code>GET
                        {{ str_replace('region', '{region}', route('api.aed.province', ['region' => 'region'])) }}</code>
                </li>
            </ul>
        </div>
        <div class="col">
            <h3>Data</h3>
            <ul class="list-unstyled codeblock">
                <li>[</li>
                <ul class="ps-5 list-unstyled">
                    <li>{</li>
                    <ul class="ps-5 list-unstyled">
                        <li>"id": 56,</li>
                        <li>"osm_id": 6640032350,</li>
                        <li>"city": "Nijmegen",</li>
                        <li>"region": "Gelderland",</li>
                        <li>"latitude": "51.8453705",</li>
                        <li>"longitude": "5.8666328",</li>
                        <li>"access": "yes",</li>
                        <li>"indoor": false,</li>
                        <li>"operator": "Stichting AED Dukenburg",</li>
                        <li>"operator_website": "https://aeddukenburg.nl",</li>
                        <li>"phone": "+31 6 30894688",</li>
                        <li>"location": "in een doos aan de muur, links naast de hoofdingang van de bibliotheek",</li>
                        <li>"opening_hours": "24/7",</li>
                        <li>"manufacturer": "Defibtech",</li>
                        <li>"model": "Lifeline",</li>
                        <li>"image": "https://commons.wikimedia.org/wiki/File:AED_at_Regionaal_Archief_Nijmegen.jpg",
                        </li>
                        <li>"cabinet": "vertical_door",</li>
                        <li>"cabinet_manufacturer": "Aivia",</li>
                        <li>"note": null,</li>
                    </ul>
                    <li>}</li>
                </ul>
                <li>]</li>
            </ul>
        </div>
    </div>
@endsection
