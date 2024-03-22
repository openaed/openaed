@extends('pages.layout')

@section('title', 'Over ons')

@section('content')
    <h1 class="mt-5">Over ons</h1>
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

    <section id="roadmap">
        <h3>Roadmap</h3>
        <div class="row">
            <div class="col">
                <div class="timeline-steps mt-5">
                    <div class="timeline-step">
                        <div class="timeline-content">
                            <div class="inner-circle"></div>
                            <p class="h6 mt-3 mb-1">april 2023</p>
                            <p class="h6 text-muted mb-0 mb-lg-0">Begonnen met OpenAED</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-content">
                            <div class="inner-circle"></div>
                            <p class="h6 mt-3 mb-1">maart 2024</p>
                            <p class="h6 text-muted mb-0 mb-lg-0">Nieuwe website</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-content">
                            <div class="inner-circle"></div>
                            <p class="h6 mt-3 mb-1 fw-bold">nu</p>
                            <p class="h6 text-muted mb-0 mb-lg-0">Focus op in kaart brengen AEDs</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-content">
                            <div class="inner-circle"></div>
                            <p class="h6 mt-3 mb-1 fst-italic">toekomst</p>
                            <ul class="list-unstyled list-bb text-muted">
                                <li>Zelf plaatsen en beheren AEDs</li>
                                <li>Release AED-managementsoftware</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="future">
        <h3>Toekomst</h3>
        <div class="col-12 col-md-8">
            <p>
                OpenAED heeft de ambitie om in de toekomst zélf AEDs te financieren, plaatsen, en beheren. Daarnaast zijn we
                bezig met het ontwikkelen van AED-managementsoftware.
            </p>
        </div>
    </section>

    <section id="contact">
        <div class="col-12 col-md-8">
            <h3>Contact</h3>
            <p>
                Voor vragen, opmerkingen of suggesties kunt u contact opnemen met OpenAED. Dit kan door een e-mail
                te sturen naar <a href="mailto:info@openaed.nl">info@openaed.nl</a>.
            </p>

            <h3>API-gebruik</h3>
            <p>
                Voor het gebruik van de OpenAED API is een API-sleutel nodig. Deze kan aangevraagd worden door een e-mail te
                sturen
                naar <a href="mailto:api@openaed.nl">api@openaed.nl</a>
            </p>
        </div>
    </section>
@endsection
