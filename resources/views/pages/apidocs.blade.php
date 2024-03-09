@extends('pages.layout')

@section('title', 'API documentatie')

@section('content')
    <h1 class="mt-5">API documentatie</h1>

    <p>
        De OpenAED API is beschikbaar gemaakt onder de <a target="_blank"
            href="https://opendatacommons.org/licenses/odbl/1.0/">Open Database
            License v1.0</a>. Dit omdat de data geleverd wordt door <a target="_blank"
            href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>.
    </p>

    <h2>Toegang</h2>
    <p>Om data te gebruiken van OpenAED is een API-sleutel nodig. Deze wordt meegestuurd in de query parameter
        <code>key</code>. Een API-sleutel kan aangevraagd worden door een e-mail te sturen naar <a
            href="mailto:api@openaed.nl">api@openaed.nl</a>. In de e-mail moet u uw naam, organisatie (indien van toepassing)
        en het beoogde gebruik van de API vermelden.
    </p>

    <div class="row">
        <div class="col">
            <h3>Endpoints</h3>
            <ul class="ps-3">
                <li>Alle AEDs<br><code>GET {{ route('api.aed.all') }}</code></li>
                <li>Alle AEDs in een
                    woonplaats<br><code>GET
                        {{ str_replace('woonplaats', '{woonplaats}', str_replace('provincie', '{provincie}', route('api.aed.city', ['city' => 'woonplaats', 'province' => 'provincie']))) }}</code>
                </li>
                <li>Alle AEDs in een provincie<br><code>GET
                        {{ str_replace('provincie', '{provincie}', route('api.aed.province', ['province' => 'provincie'])) }}</code>
                </li>
            </ul>
        </div>
        <div class="col">
            <h3>Data</h3>
            <ul class="list-unstyled codeblock">
                <li>{</li>
                <ul class="ps-5 list-unstyled">
                    <li>"meta" : {</li>
                    <ul class="ps-5 list-unstyled">
                        <li>"attribution": "Data provided in part by OpenStreetMap",</li>
                        <li>"copyright": "https://www.openstreetmap.org/copyright",</li>
                        <li>"timestamp": 1709974508</li>
                    </ul>
                    <li>},</li>
                    <li>"result": [</li>
                    <ul class="ps-5 list-unstyled">
                        <li>{</li>
                        <ul class="ps-5 list-unstyled">
                            <li>"id": 56,</li>
                            <li>"osm_id": 6640032350,</li>
                            <li>"city": "Nijmegen",</li>
                            <li>"province": "Gelderland",</li>
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
                <li>}</li>
            </ul>
        </div>
    </div>
@endsection
