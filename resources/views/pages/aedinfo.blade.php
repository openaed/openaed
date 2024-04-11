@extends('pages.layout')

@section('title', 'Wat is een AED?')

@section('content')
    <h1 class="mt-5">Wat is een AED?</h1>

    <div class="row">
        <div class="col-12 col-md-6">
            <p>
                Een AED is een draagbaar apparaat dat het hartritme weer kan herstellen bij een hartstilstand. Dit gebeurt
                door
                het
                geven van een elektrische schok.
                <br>
                <br>
                Een AED is een Automatische Externe Defibrillator. Dit betekent dat de AED zelfstandig kan bepalen of een
                schok
                noodzakelijk is.
                <br>
                <br>
                De AED is een belangrijk onderdeel van de overlevingsketen bij een hartstilstand. De overlevingsketen
                bestaat
                uit
                een aantal schakels die elkaar opvolgen. Hoe sneller de schakels worden doorlopen, hoe groter de
                overlevingskans
                van
                het slachtoffer.
                <br>
                <br>
                De overlevingsketen bestaat uit de volgende schakels:
                <br>
            <ol>
                <li>Directe hulpverlening door omstanders</li>
                <li>Vroegtijdige alarmering van professionele hulpdiensten</li>
                <li>Snelle reanimatie</li>
                <li>Snelle inzet van een AED</li>
                <li>Snelle inzet van professionele hulpdiensten</li>
            </ol>
            <br>
            De AED is een belangrijke schakel in de overlevingsketen. De AED kan binnen enkele minuten na een hartstilstand
            worden ingezet. Dit vergroot de overlevingskans van het slachtoffer.
            </p>
        </div>
        <div class="col-12 col-md-6">
            <img src="{{ asset('images/aed_kampenaar.jpg') }}" class="img-fluid border rounded mb-3 mb-md-0" alt="AED">
        </div>
    </div>

    <h3>Meer informatie?</h3>
    <div class="row mb-4">
        <div class="col text-start">
            <a href="https://www.rodekruis.be/wat-kan-jij-doen/volg-een-opleiding/eerstehulptips/reanimeren/"
                target="_blank">
                <img src="{{ asset('images/logos/rodekruis.png') }}" class="img-fluid" style="max-height: 8em;"
                    alt="Rode Kruis">
            </a>
        </div>
    </div>
@endsection
