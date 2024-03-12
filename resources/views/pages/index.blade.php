@extends('pages.layout', ['container' => 'container-fluid p-0'])

@section('title', 'Home')

@section('content')
    <div class="legend ui-element col-3">
        <h5>Legenda</h5>
        <div class="container-fluid">
            <div class="row">
                <div class="col-3">
                    <img src="{{ asset('icons/aed_Regular.png') }}" class="img-fluid" alt="Regular icon">
                </div>
                <div class="col-9 my-auto">
                    <span class="fs-5">Openbaar toegankelijk</span><br>
                    <small>Deze AED's zijn door iedereen te gebruiken.</small>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <img src="{{ asset('icons/aed_Permissive.png') }}" class="img-fluid" alt="Permissive icon">
                </div>
                <div class="col-9 my-auto">
                    <span class="fs-5">Beperkt toegankelijk</span><br>
                    <small>Deze AED's hangen in winkels of bedrijven die gesloten kunnen zijn.</small>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <img src="{{ asset('icons/aed_Private.png') }}" class="img-fluid" alt="Private icon">
                </div>
                <div class="col-9 my-auto">
                    <span class="fs-5">Geen toegang</span><br>
                    <small>Op deze AED's zit een fysiek slot.</small>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <img src="{{ asset('icons/aed_Unknown.png') }}" class="img-fluid" alt="Unknown icon">
                </div>
                <div class="col-9 my-auto">
                    <span class="fs-5">Onbekende toegang</span><br>
                    <small>Van deze AED's weten we nog niet hoe het zit.</small>
                </div>
            </div>
        </div>
    </div>
    <div id="map"></div>

    <script type="text/javascript">
        window.addEventListener('DOMContentLoaded', function() {
            const map = L.map("map");
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            if (localStorage.getItem("lat")) {
                map.setView([localStorage.getItem("lat"), localStorage.getItem("lon")], localStorage.getItem(
                    "zoom"));
            } else {
                map.setView([52.096, 5.548], 8); // Netherlands
            }

            map.on('moveend', () => {
                localStorage.setItem("lat", map.getCenter().lat);
                localStorage.setItem("lon", map.getCenter().lng);
                localStorage.setItem("zoom", map.getZoom());
            });


            const aedMarkerGroup = L.markerClusterGroup({
                showCoverageOnHover: false,
                removeOutsideVisibleBounds: true,
                disableClusteringAtZoom: 11,
            });

            map.addLayer(aedMarkerGroup);

            const regularIcon = L.icon({
                iconUrl: 'icons/aed_Regular.png',
                iconSize: [40, 45],
                iconAnchor: [20, 45],
                popupAnchor: [0, -40]
            });

            const permissiveIcon = L.icon({
                iconUrl: 'icons/aed_Permissive.png',
                iconSize: [40, 45],
                iconAnchor: [20, 45],
                popupAnchor: [0, -40]
            });

            const privateIcon = L.icon({
                iconUrl: 'icons/aed_Private.png',
                iconSize: [40, 45],
                iconAnchor: [20, 45],
                popupAnchor: [0, -40]
            });

            const unknownIcon = L.icon({
                iconUrl: 'icons/aed_Unknown.png',
                iconSize: [40, 45],
                iconAnchor: [20, 45],
                popupAnchor: [0, -40]
            });

            const allAEDURL = "{{ route('api.aed.all') }}";
            fetch(allAEDURL)
                .then(response => response.json())
                .then(data => {
                    renderAEDs(data.result);
                });

            function renderAEDs(aeds) {
                aedMarkerGroup.clearLayers(); // Empty so we can refill it

                aeds.forEach(aed => {
                    let icon, access, indoor, level = "",
                        locked = "",
                        cabinet = "";

                    if (aed.access == "permissive") {
                        icon = permissiveIcon;
                        access = "Beperkt"
                    } else if (aed.access == "unknown" || aed.access == null) {
                        icon = unknownIcon;
                        access = "Onbekend"
                    } else if (aed.access == "private" || aed.access == "no") {
                        icon = privateIcon;
                        access = "Geen"
                    } else {
                        icon = regularIcon;
                        access = "Openbaar"
                    }

                    if (aed.indoor == true) {
                        indoor = "Ja";
                        if (aed.level != 0 && aed.level != null) {
                            level = ", op verdieping " + aed.level;
                        } else if (aed.level == null) {
                            level = "";
                        } else {
                            level = ", op de begane grond";
                        }
                    } else {
                        indoor = "Nee";
                    }

                    if (aed.locked == "yes") {
                        locked = "Ja";
                    } else {
                        locked = "Nee";
                    }

                    switch (aed.cabinet) {
                        case "horizontal_door":
                            cabinet = "Horizontale deur";
                            break;
                        case "vertical_door":
                            cabinet = "Verticale deur";
                            break;
                        case "none":
                            cabinet = "Geen";
                            break;
                        case "twist":
                            cabinet = "Draaiend";
                            break;
                        case "mechanical":
                            cabinet = "Mechanisch";
                            break;
                        default:
                            cabinet = "Onbekend";
                            break;
                    }

                    let popupContent = ''; // Initialise popup content
                    popupContent +=
                        `<h3>${aed.operator ?? "Onbekende beheerder"}</h3>`; // Operator name
                    if (aed.operator_website) popupContent +=
                        `<small><a target='_blank' href='${aed.operator_website}'>${aed.operator_website}</a></small><br>`; // Operator website
                    popupContent += `<b>Toegang:</b> ${access}`; // Access
                    if (aed.locked) popupContent +=
                        `<br><b>Slot:</b> ${aed.locked ? "Ja" : "Nee"}`; // Lock
                    if (aed.phone) popupContent +=
                        `<br><b>Telefoon:</b> ${aed.phone}`; // Operator phone number
                    if (aed.location) popupContent +=
                        `<br><b>Exacte locatie:</b> ${aed.location}`; // Exact location
                    popupContent +=
                        `<br><b>Binnen:</b> ${indoor}${level}`; // Whether the AED is indoors
                    if (aed["opening_hours"]) popupContent +=
                        `<br><b>Openingstijden:</b>${parseOpeningHours(aed.opening_hours)}`; // Opening hours
                    if (aed.manufacturer) popupContent +=
                        `<br><b>Type:</b> ${aed.manufacturer} ${aed.model ?? ""}`; // Manufacturer
                    if (aed.cabinet) popupContent +=
                        `<br><b>Kast:</b> ${cabinet}`; // Case
                    if (aed.cabinet_manufacturer) popupContent +=
                        `<br><b>Kast fabrikant:</b> ${aed.cabinet_manufacturer}`; // Case manufacturer
                    if (aed.image) {
                        let directurl = aed.image;
                        if (directurl.includes("commons.wikimedia.org")) { // Wikimedia Commons
                            let filename = directurl.substring(directurl.lastIndexOf("File:") + 5,
                                directurl.length);
                            directurl = "https://commons.wikimedia.org/wiki/Special:FilePath/" +
                                filename;
                        }
                        popupContent +=
                            `<br><a target='_blank' href='${directurl}'><img class="popupimage" src='${directurl}'></a>`; // Image
                    }
                    if (aed.note) popupContent +=
                        `<br><b>Opmerking:</b> ${aed.note}`; // Note
                    popupContent +=
                        `<br><a target='_blank' href='https://www.openstreetmap.org/node/${aed.osm_id}'>Bewerken op OpenStreetMap</a>`; // OSM link

                    aedMarkerGroup.addLayer(L.marker([aed.latitude, aed.longitude], {
                        icon: icon
                    }).bindPopup(
                        popupContent));
                });
            }

            function parseOpeningHours(hours) {
                let returnstring = "";

                hours = hours.replace("Mo", "Ma");
                hours = hours.replace("Tu", "Di");
                hours = hours.replace("We", "Wo");
                hours = hours.replace("Th", "Do");
                hours = hours.replace("Fr", "Vr");
                hours = hours.replace("Sa", "Za");
                hours = hours.replace("Su", "Zo");

                let strings = hours.split(";");
                strings.forEach(timestring => {
                    if (timestring.includes("off")) return;

                    returnstring += "<br>" + timestring;
                });

                return returnstring;
            }
        });
    </script>
@endsection
