@extends('pages.layout')

@section('title', __('pages.about_us'))

@section('content')
    <h1 class="mt-5">{{ __('pages.about_us') }}</h1>
    @switch(app()->getLocale())
        @case ('nl')
            <section id="about-us">
                <div class="col-12 col-md-8">
                    <p>
                        OpenAED is een initiatief dat is ontstaan in april 2023. Het doel van OpenAED is om een zo volledig
                        mogelijk overzicht te geven van alle AEDs in Nederland. Dit overzicht is beschikbaar via de website en
                        API van OpenAED.
                    </p>
                    <p>
                        OpenAED is een non-profitorganisatie en is volledig afhankelijk van vrijwilligers. De website en API van
                        OpenAED zijn gratis te gebruiken.
                    </p>
                    <p>
                        OpenAED is een open-sourceproject en de broncode is beschikbaar op <a
                            href="https://github.com/openaed/openaed">GitHub</a>. Iedereen is welkom om bij te dragen.
                    </p>

                    <span class="fs-5">OpenStreetMap</span>
                    <p>
                        OpenAED maakt gebruik van de data van <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>
                        voor
                        de locaties van en informatie over AEDs. OpenStreetMap is een open-sourceproject dat wordt onderhouden door
                        vrijwilligers.
                        Wil je bijdragen aan OpenAED? Voeg AEDs toe aan OpenStreetMap en ze zullen automatisch de volgende dag
                        zichtbaar worden op
                        de kaart van OpenAED.<br>
                        Lees meer in de <a href="https://wiki.openstreetmap.org/wiki/NL:Beginnershandleiding">beginnershandleiding
                            van
                            OpenStreetMap</a>.
                    </p>
                </div>
            </section>

            <section id="future">
                <h3>Toekomst</h3>
                <div class="col-12 col-md-8">
                    <p>
                        OpenAED heeft de ambitie om in de toekomst zélf AEDs te financieren, installeren, en beheren.
                    </p>
                </div>
            </section>

            <section id="contact">
                <div class="col-12 col-md-8">
                    <h3>Contact</h3>
                    <p>
                        Voor vragen, opmerkingen of suggesties kunt u contact opnemen met OpenAED. Dit kan door een e-mail
                        te sturen naar <a href="mailto:info@openaed.eu">info@openaed.eu</a>.
                    </p>

                    <h3>API-gebruik</h3>
                    <p>
                        Voor het gebruik van de OpenAED API is een API-sleutel nodig. Deze kan aangevraagd worden door een e-mail te
                        sturen
                        naar <a href="mailto:api@openaed.eu">api@openaed.eu</a>
                    </p>
                </div>
            </section>
        @break

        @case ('en')
            <section id="about-us">
                <div class="col-12 col-md-8">
                    <p>
                        OpenAED is an initiative that was founded in April 2023. The goal of OpenAED is to provide a complete
                        overview of all AEDs in the Netherlands. This overview is available through the OpenAED website and API.
                    </p>
                    <p>
                        OpenAED is a non-profit organization and is fully dependent on volunteers. The OpenAED website and API are
                        free to use.
                    </p>
                    <p>
                        OpenAED is an open-source project and the source code is available on <a
                            href="https://github.com/openaed/openaed">GitHub</a>. Everyone is welcome to contribute.
                    </p>

                    <span class="fs-5">OpenStreetMap</span>
                    <p>
                        OpenAED uses the data from <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> for the
                        locations and information about AEDs. OpenStreetMap is an open-source project maintained by volunteers.
                        Want to contribute to OpenAED? Add AEDs to OpenStreetMap and they will automatically be visible on the
                        OpenAED map the next day.<br>
                        Read more in the <a href="https://wiki.openstreetmap.org/wiki/Beginners%27_guide">OpenStreetMap beginner's
                            guide</a>.
                    </p>
                </div>
            </section>

            <section id="future">
                <h3>Future</h3>
                <div class="col-12 col-md-8">
                    <p>
                        OpenAED has the ambition to finance, install, and manage AEDs itself in the future.
                    </p>
                </div>
            </section>

            <section id="contact">
                <div class="col-12 col-md-8">
                    <h3>Contact</h3>
                    <p>
                        For questions, comments, or suggestions, you can contact OpenAED. This can be done by sending an email to
                        <a href="mailto:info@openaed.eu">info@openaed.eu</a>.
                    </p>

                    <h3>API usage</h3>
                    <p>
                        To use the OpenAED API, an API key is required. This can be requested by sending an email to <a
                            href="mailto:api@openaed.eu">api@openaed.eu</a>
                    </p>
                </div>
            </section>
        @break

        @case ('de')
            <section id="about-us">
                <div class="col-12 col-md-8">
                    <p>
                        OpenAED ist eine Initiative, die im April 2023 gegründet wurde. Ziel von OpenAED ist es, einen vollständigen
                        Überblick über alle AEDs in den Niederlanden zu geben. Dieser Überblick ist über die OpenAED-Website und API
                        verfügbar.
                    </p>
                    <p>
                        OpenAED ist eine gemeinnützige Organisation und ist vollständig auf Freiwillige angewiesen. Die
                        OpenAED-Website
                        und API sind kostenlos zu verwenden.
                    </p>
                    <p>
                        OpenAED ist ein Open-Source-Projekt und der Quellcode ist auf <a
                            href="https://github.com/openaed/openaed">GitHub</a> verfügbar. Jeder ist willkommen, beizutragen.
                    </p>

                    <span class="fs-5">OpenStreetMap</span>
                    <p>
                        OpenAED verwendet die Daten von <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> für die
                        Standorte und Informationen zu AEDs. OpenStreetMap ist ein Open-Source-Projekt, das von Freiwilligen
                        gepflegt wird.
                        Möchten Sie zu OpenAED beitragen? Fügen Sie AEDs zu OpenStreetMap hinzu und sie werden automatisch am
                        nächsten Tag auf der OpenAED-Karte sichtbar.<br>
                        Lesen Sie mehr in der <a href="#">OpenStreetMap
                            Einsteigeranleitung</a>.
                    </p>

                </div>
            </section>

            <section id="future">
                <h3>Zukunft</h3>
                <div class="col-12 col-md-8">
                    <p>
                        OpenAED hat die Ambition, in Zukunft selbst AEDs zu finanzieren, zu installieren und zu verwalten.
                    </p>
                </div>
            </section>

            <section id="contact">
                <div class="col-12 col-md-8">
                    <h3>Kontakt</h3>
                    <p>
                        Bei Fragen, Anmerkungen oder Vorschlägen können Sie sich an OpenAED wenden. Dies kann per E-Mail an
                        <a href="mailto:info@openaed.eu">info@openaed.eu</a> erfolgen.
                    </p>

                    <h3>API-Nutzung</h3>
                    <p>
                        Für die Nutzung der OpenAED-API ist ein API-Schlüssel erforderlich. Dieser kann per E-Mail an <a
                            href="mailto:api@openaed.eu">api@openaed.eu</a> angefordert werden.
                    </p>
                </div>
            </section>
        @break

        @default
            <p>{{ __('common.page_trans_not_available') }}</p>
        @break
    @endswitch

@endsection
