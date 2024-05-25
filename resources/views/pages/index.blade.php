@extends('pages.layout', ['container' => 'container-fluid p-0'])

@section('title', 'Home')

@section('content')
    <div class="legend ui-element col-3">
        <span class="fs-5" onclick="toggleLegend()">{{ __('legend.legend') }} <i
                class="bi bi-caret-up-fill d-inline d-md-none" id="legend-icon"></i></span>
        <div class="container-fluid visible" id="legend-content">
            <div class="row">
                <div class="col-3">
                    <img src="{{ asset('icons/aed_Regular.png') }}" class="img-fluid legend-icon" alt="Regular icon">
                </div>
                <div class="col-9 my-auto">
                    <span class="fs-5">{{ __('legend.public') }}</span><br>
                    <small>{{ __('legend.public.description') }}</small>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <img src="{{ asset('icons/aed_Permissive.png') }}" class="img-fluid legend-icon" alt="Permissive icon">
                </div>
                <div class="col-9 my-auto">
                    <span class="fs-5">{{ __('legend.permissive') }}</span><br>
                    <small>{{ __('legend.permissive.description') }}</small>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <img src="{{ asset('icons/aed_Private.png') }}" class="img-fluid legend-icon" alt="Private icon">
                </div>
                <div class="col-9 my-auto">
                    <span class="fs-5">{{ __('legend.private') }}</span><br>
                    <small>{{ __('legend.private.description') }}</small>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <img src="{{ asset('icons/aed_Unknown.png') }}" class="img-fluid legend-icon" alt="Unknown icon">
                </div>
                <div class="col-9 my-auto">
                    <span class="fs-5">{{ __('legend.unknown') }}</span><br>
                    <small>{{ __('legend.unknown.description') }}</small>
                </div>
            </div>
        </div>
    </div>
    <div id="map"></div>

    <script type="text/javascript">
        let map;
        window.addEventListener('DOMContentLoaded', function() {
            map = L.map("map");
            @if (config('app.map_tiles') == 'osm-carto')
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
            @endif

            @if (config('app.map_tiles') == 'voyager')
                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                    subdomains: 'abcd',
                    maxZoom: 20
                }).addTo(map);
            @endif

            map.attributionControl.setPrefix(''); // Don't show the 'Leaflet' text in the bottom right corner
            map.attributionControl.setPosition('topright');

            if (localStorage.getItem("lat")) {
                map.setView([localStorage.getItem("lat"), localStorage.getItem("lon")], localStorage.getItem(
                    "zoom"));
            } else {
                map.setView([{{ config('app.default_coordinates.lat') }},
                    {{ config('app.default_coordinates.lon') }}
                ], {{ config('app.default_coordinates.zoom') }});
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

            const allAEDURL = "{{ route('api.aed.basic') }}";
            fetch(allAEDURL)
                .then(response => response.json())
                .then(data => {
                    renderAEDs(data.result);
                });

            function renderAEDs(aeds) {
                aedMarkerGroup.clearLayers(); // Empty so we can refill it

                aeds.forEach(aed => {
                    let icon;

                    if (aed.access == "permissive") {
                        icon = permissiveIcon;
                        access = "{{ __('defibrillator.access.permissive') }}"
                    } else if (aed.access == "unknown" || aed.access == null) {
                        icon = unknownIcon;
                        access = "{{ __('defibrillator.access.unknown') }}"
                    } else if (aed.access == "private" || aed.access == "no") {
                        icon = privateIcon;
                        access = "{{ __('defibrillator.access.private') }}"
                    } else {
                        icon = regularIcon;
                        access = "{{ __('defibrillator.access.public') }}"
                    }

                    const aedMarker = L.Marker.extend({
                        options: {
                            id: null
                        }
                    });

                    const marker = new aedMarker([aed.latitude, aed.longitude], {
                        icon: icon,
                        id: aed.id
                    });

                    marker.on('click', function(e) {
                        const id = e.target.options.id;

                        let url = "{{ route('api.aed.one', ['id' => 'id']) }}";
                        url = url.replace('id', id);
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                e.target.bindPopup(getPopupContent(data.result)).openPopup();
                            });
                    });

                    aedMarkerGroup.addLayer(marker)
                    // .bindPopup(popupContent));
                });
            }
        });

        const legend = document.getElementById("legend-content");
        const legendIcon = document.getElementById("legend-icon");

        if (localStorage.getItem("legend") == true) {
            legend.classList.add('visible');
            legendIcon.classList.remove('bi-caret-up-fill');
            legendIcon.classList.add('bi-caret-down-fill');
        } else {
            legend.classList.remove('visible');
            legendIcon.classList.add('bi-caret-up-fill');
            legendIcon.classList.remove('bi-caret-down-fill');
        }

        const toggleLegend = () => {
            if (window.matchMedia('(min-width: 768px)').matches) return;
            if (legend.classList.contains('visible')) {
                legend.classList.remove('visible');
                legendIcon.classList.remove('bi-caret-down-fill');
                legendIcon.classList.add('bi-caret-up-fill');

                localStorage.setItem("legend", false);
            } else {
                legend.classList.add('visible');
                legendIcon.classList.add('bi-caret-down-fill');
                legendIcon.classList.remove('bi-caret-up-fill');

                localStorage.setItem("legend", true);
            }
        }

        const parseOpeningHours = (hours) => {
            let returnstring = "";

            hours = hours.replace("Mo", "{{ __('days.mo') }}");
            hours = hours.replace("Tu", "{{ __('days.tu') }}");
            hours = hours.replace("We", "{{ __('days.we') }}");
            hours = hours.replace("Th", "{{ __('days.th') }}");
            hours = hours.replace("Fr", "{{ __('days.fr') }}");
            hours = hours.replace("Sa", "{{ __('days.sa') }}");
            hours = hours.replace("Su", "{{ __('days.su') }}");

            let strings = hours.split(";");
            strings.forEach(timestring => {
                if (timestring.includes("off")) return;

                returnstring += "<br>" + timestring;
            });

            return returnstring;
        }

        const getPopupContent = (aed) => {
            let access, indoor, level = "",
                locked = "",
                cabinet = "";

            if (aed.access == "permissive") {
                access = "{{ __('defibrillator.access.permissive') }}"
            } else if (aed.access == "unknown" || aed.access == null) {
                access = "{{ __('defibrillator.access.unknown') }}"
            } else if (aed.access == "private" || aed.access == "no") {
                access = "{{ __('defibrillator.access.private') }}"
            } else {
                access = "{{ __('defibrillator.access.public') }}"
            }

            if (aed.indoor == true) {
                indoor = "{{ __('Yes') }}";
                if (aed.level != 0 && aed.level != null) {
                    level = ("{{ __('defibrillator.indoor.on_floor', ['floor' => '-level-']) }}")
                        .replace('-level-', aed.level);
                } else if (aed.level == null) {
                    level = "";
                } else {
                    level = "{{ __('defibrillator.indoor.on_ground_floor') }}";
                }
            } else {
                indoor = "{{ __('No') }}";
            }

            if (aed.locked == "yes") {
                locked = "{{ __('Yes') }}";
            } else {
                locked = "{{ __('No') }}";
            }

            switch (aed.cabinet) {
                case "horizontal_door":
                    cabinet = "{{ __('defibrillator.cabinet.horizontal_door') }}";
                    break;
                case "vertical_door":
                    cabinet = "{{ __('defibrillator.cabinet.vertical_door') }}";
                    break;
                case "no":
                    cabinet = "{{ __('defibrillator.cabinet.no') }}";
                    break;
                case "twist":
                    cabinet = "{{ __('defibrillator.cabinet.twist') }}";
                    break;
                case "mechanical":
                    cabinet = "{{ __('defibrillator.cabinet.mechanical') }}";
                    break;
                default:
                    cabinet = "{{ __('defibrillator.cabinet.unknown') }}";
                    break;
            }

            let popupContent = ''; // Initialise popup content
            popupContent +=
                `<h3>${aed.operator ?? "{{ __('defibrillator.operator.unknown') }}"}</h3>`; // Operator name
            if (aed.operator_website) popupContent +=
                `<small><a target='_blank' href='${aed.operator_website}'>${aed.operator_website}</a></small><br>`; // Operator website
            popupContent += `<b>{{ __('defibrillator.access') }}:</b> ${access}`; // Access
            if (aed.locked) popupContent +=
                `<br><b>{{ __('defibrillator.locked') }}:</b> ${aed.locked ? "{{ __('Yes') }}" : "{{ __('No') }}"}`; // Lock
            if (aed.phone) popupContent +=
                `<br><b>{{ __('defibrillator.phone') }}:</b> ${aed.phone}`; // Operator phone number
            if (aed.location) popupContent +=
                `<br><b>{{ __('defibrillator.location') }}:</b> ${aed.location}`; // Exact location
            popupContent +=
                `<br><b>{{ __('defibrillator.indoor') }}:</b> ${indoor}${level}`; // Whether the AED is indoors
            if (aed["opening_hours"]) popupContent +=
                `<br><b>{{ __('defibrillator.opening_hours') }}:</b>${parseOpeningHours(aed.opening_hours)}`; // Opening hours
            if (aed.manufacturer) popupContent +=
                `<br><b>{{ __('defibrillator.type') }}:</b> ${aed.manufacturer} ${aed.model ?? ""}`; // Manufacturer
            if (aed.cabinet) popupContent +=
                `<br><b>{{ __('defibrillator.cabinet') }}:</b> ${cabinet}`; // Case
            if (aed.cabinet_manufacturer) popupContent +=
                `<br><b>{{ __('defibrillator.cabinet_manufacturer') }}:</b> ${aed.cabinet_manufacturer}`; // Case manufacturer
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
            if (aed.description) popupContent +=
                `<br><b>{{ __('defibrillator.description') }}:</b> ${aed.description}`; // Description
            popupContent +=
                `<br><a target='_blank' href='https://www.openstreetmap.org/node/${aed.osm_id}'>{{ __('defibrillator.edit_on_osm') }}</a>`; // OSM link

            return popupContent;
        }
    </script>
@endsection
