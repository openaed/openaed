@extends('pages.layout', ['page' => true])

@section('title', __('pages.what_is_aed'))

@section('content')
    <h1 class="mt-5">{{ __('pages.what_is_aed') }}</h1>

    <div class="row">
        <div class="col-12 col-md-6">
            @switch(app()->getLocale())
                @case ('nl')
                    <p>
                        Een AED is een draagbaar apparaat dat het hartritme weer kan herstellen bij een hartstilstand. Dit
                        gebeurt
                        door
                        het
                        geven van een elektrische schok.
                        <br>
                        <br>
                        Een AED is een Automatische Externe Defibrillator. Dit betekent dat de AED zelfstandig kan bepalen of
                        een
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
                    De AED is een belangrijke schakel in de overlevingsketen. De AED kan binnen enkele minuten na een
                    hartstilstand
                    worden ingezet. Dit vergroot de overlevingskans van het slachtoffer.
                    </p>
                @break

                @case ('en')
                    <p>
                        An AED is a portable device that can restore the heart rhythm in case of a cardiac arrest. This is
                        done
                        by
                        giving an electric shock.
                        <br>
                        <br>
                        An AED is an Automatic External Defibrillator. This means that the AED can independently determine
                        whether
                        a
                        shock is necessary.
                        <br>
                        <br>
                        The AED is an important part of the survival chain in case of a cardiac arrest. The survival chain
                        consists
                        of
                        a number of links that follow each other. The faster the links are passed through, the greater the
                        chance
                        of
                        survival of the victim.
                        <br>
                        <br>
                        The survival chain consists of the following links:
                        <br>
                    <ol>
                        <li>Immediate assistance from bystanders</li>
                        <li>Early alerting of professional emergency services</li>
                        <li>Rapid resuscitation</li>
                        <li>Rapid deployment of an AED</li>
                        <li>Rapid deployment of professional emergency services</li>
                    </ol>
                    <br>
                    The AED is an important link in the survival chain. The AED can be deployed within minutes after a
                    cardiac
                    arrest.
                    This increases the chances of survival of the victim.
                    </p>
                @break

                @case ('de')
                    <p>
                        Ein AED ist ein tragbares Gerät, das den Herzrhythmus bei einem Herzstillstand wiederherstellen kann.
                        Dies
                        geschieht
                        durch
                        Abgabe eines elektrischen Schocks.
                        <br>
                        <br>
                        Ein AED ist ein Automatischer Externer Defibrillator. Dies bedeutet, dass der AED eigenständig
                        bestimmen
                        kann,
                        ob
                        ein Schock erforderlich ist.
                        <br>
                        <br>
                        Der AED ist ein wichtiger Bestandteil der Überlebenskette bei einem Herzstillstand. Die
                        Überlebenskette
                        besteht
                        aus
                        einer Reihe von Gliedern, die sich gegenseitig folgen. Je schneller die Glieder durchlaufen werden,
                        desto
                        größer
                        ist die Überlebenschance des Opfers.
                        <br>
                        <br>
                        Die Überlebenskette besteht aus den folgenden Gliedern:
                        <br>
                    <ol>
                        <li>Unmittelbare Hilfe durch Passanten</li>
                        <li>Frühzeitige Alarmierung professioneller Rettungsdienste</li>
                        <li>Schnelle Reanimation</li>
                        <li>Schneller Einsatz eines AED</li>
                        <li>Schneller Einsatz professioneller Rettungsdienste</li>
                    </ol>
                    <br>
                    Der AED ist ein wichtiger Bestandteil der Überlebenskette. Der AED kann innerhalb weniger Minuten nach
                    einem
                    Herzstillstand eingesetzt werden. Dies erhöht die Überlebenschancen des Opfers.
                    </p>
                @break

                @default
                    <p>{{ __('common.page_trans_not_available') }}</p>
                @break
            @endswitch
        </div>
        <div class="col-12 col-md-6">
            <img src="{{ asset('images/aed_kampenaar.jpg') }}" class="img-fluid border rounded mb-3 mb-md-0" alt="AED">
        </div>
    </div>

    <h3>{{ __('common.more_information') }}</h3>
    <div class="row mb-4">
        <div class="col-5 text-start">
            <a href="https://www.hartstichting.nl/reanimatie/wat-is-een-aed/" target="_blank">
                <img src="{{ asset('images/logos/hartstichting.png') }}" class="img-fluid" style="max-height: 8em;"
                    alt="Hartstichting">
            </a>
        </div>
        <div class="col-2 text-center">
            <a href="https://hartslagnu.nl/" target="_blank">
                <img src="{{ asset('images/logos/hartslagnu.jpg') }}" class="img-fluid" style="max-height: 8em;"
                    alt="HartslagNu">
            </a>
        </div>
        <div class="col-5 text-end">
            <a href="https://www.rodekruis.nl/ehbo/ehbo-tips/reanimatie/" target="_blank">
                <img src="{{ asset('images/logos/rodekruis.png') }}" class="img-fluid" style="max-height: 8em;"
                    alt="Rode Kruis">
            </a>
        </div>
    </div>
@endsection
