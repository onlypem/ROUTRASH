@include('layout.header')
@include('layout.navbar')
@include('layout.sidebar')
</head>
<style>
    #map-canvas {
        width: 100%;
        height: 500px;
    }

    .info-window {
        max-width: 200px;
    }

    #map-canvas-pso {
        width: 100%;
        height: 500px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfDg7Rknio90wPC0XaxJ6-l9JKppBygpU&callback=initMap" async
    defer></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfDg7Rknio90wPC0XaxJ6-l9JKppBygpU&callback=initMapPSO"
    async defer></script>
</head>
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6 xl-100 col-lg-12 box-col-12">
                <div class="card">
                    <div class="card-header pb-0">

                        <h5 class="pull-left">Rute {{ $driver->user->name }} &nbsp;&nbsp;
                        <a class="fa fa-repeat" href="/rute/resetdriver/{{ $driver->id }}"  title="Reset"></a></h5>
                    </div>
                    <div class="card-body">
                        <div class="tabbed-card">
                            <ul class="pull-right nav nav-pills nav-primary" id="pills-clrtab1" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="pills-clrhome-tab1"
                                        data-bs-toggle="pill" href="#pills-clrhome1" role="tab"
                                        aria-controls="pills-clrhome1" aria-selected="true">PSO</a></li>
                                <li class="nav-item"><a class="nav-link" id="pills-clrprofile-tab1"
                                        data-bs-toggle="pill" href="#pills-clrprofile1" role="tab"
                                        aria-controls="pills-clrprofile1" aria-selected="false">Nearest Neighbor</a></li>
                            </ul>
                            <div class="tab-content" id="pills-clrtabContent1">
                                <div class="tab-pane fade show active" id="pills-clrhome1" role="tabpanel"
                                    aria-labelledby="pills-clrhome-tab1">
                                    {{-- <div class="card-body" style="padding-top: 5px;"> --}}

                                        <div id="map-canvas-pso"></div>
                                        <!-- Tampilkan urutan lokasi -->
                                        <div id="route-order" class="mt-3">

                                            <p>Optimal Route:</p>
                                            <ol>
                                                @foreach ($urutanLokasi as $lokasi)
                                                    <li>{{ $lokasi->name }}</li>
                                                @endforeach


                                            </ol>
                                            <div lass="mt-3">
                                                Total Distance: {{ $route->jarak }} km<br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-clrprofile1" role="tabpanel"
                                        aria-labelledby="pills-clrprofile-tab1">

                                        <div id="map-canvas"></div>
                                        <!-- Tampilkan urutan lokasi -->
                                        <div id="route-order" class="mt-3">
                                                <p>Optimal Route:</p>
                                                <ol>
                                                    @foreach ($urutanLokasinn as $lokasinn)
                                                    <li>{{ $lokasinn->name }}</li>
                                                @endforeach
                                                </ol>
                                            <div lass="mt-3">
                                                Total Distance: {{ $routenn->jarak }} km<br>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function initMapPSO() {
        var map = new google.maps.Map(document.getElementById('map-canvas-pso'), {
            zoom: 12,
            center: new google.maps.LatLng(-6.123456, 106.789012)
        });

        var locations = [
            @foreach ($optimalRoutePSO as $locationId)
                {
                    name: "{{ collect($locations)->where('id', $locationId)->first()['name'] }}",
                    lat: {{ collect($locations)->where('id', $locationId)->first()['lat'] }},
                    lng: {{ collect($locations)->where('id', $locationId)->first()['lng'] }}
                },
            @endforeach
        ];

        var markers = [];
        for (var i = 0; i < locations.length; i++) {
            var location = locations[i];
            var marker = new google.maps.Marker({
                position: {
                    lat: location.lat,
                    lng: location.lng
                },
                map: map,
                title: location.name // Menampilkan nama lokasi saat marker diklik
            });
            markers.push(marker);
        }

        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            },
            map: map
        });

        var waypoints = locations.slice(1, locations.length - 1).map(function(location) {
            return {
                location: new google.maps.LatLng(location.lat, location.lng),
                stopover: true
            };
        });
        var request = {
            origin: new google.maps.LatLng(locations[0].lat, locations[0].lng),
            destination: new google.maps.LatLng(locations[locations.length - 1].lat, locations[locations.length - 1]
                .lng),
            waypoints: waypoints,
            optimizeWaypoints: false,
            travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(response);
                var route = response.routes[0];
                var legs = route.legs;
                var lastLeg = legs[legs.length - 1];
                var lastLocation = lastLeg.end_location;

                // Menambahkan marker lokasi mulai sebagai waypoint terakhir
                var startMarker = new google.maps.Marker({
                    position: request.origin,
                    map: map
                });
                // Menghubungkan lokasi terakhir ke lokasi mulai
                var routeToStart = new google.maps.DirectionsRenderer({
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    }
                });
                routeToStart.setMap(map);

                var routeRequestToStart = {
                    origin: lastLocation,
                    destination: request.origin,
                    travelMode: google.maps.TravelMode.DRIVING
                };

                directionsService.route(routeRequestToStart, function(response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        routeToStart.setDirections(response);
                    }
                });
            }
        });


        // Menambahkan event listener untuk menampilkan info window saat marker diklik
        markers.forEach(function(marker) {
            marker.addListener('click', function() {
                var infoWindow = new google.maps.InfoWindow({
                    content: marker.title
                });
                infoWindow.open(map, marker);
            });
        });

        // Fit map bounds to show all markers and route
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
        }
        map.fitBounds(bounds);
    }
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            zoom: 12,
            center: new google.maps.LatLng(-6.123456, 106.789012)
        });

        var locations = [
            @foreach ($optimalRoute as $locationId)
                {
                    name: "{{ collect($locations)->where('id', $locationId)->first()['name'] }}",
                    lat: {{ collect($locations)->where('id', $locationId)->first()['lat'] }},
                    lng: {{ collect($locations)->where('id', $locationId)->first()['lng'] }}
                },
            @endforeach
        ];

        var markers = [];
        for (var i = 0; i < locations.length; i++) {
            var location = locations[i];
            var marker = new google.maps.Marker({
                position: {
                    lat: location.lat,
                    lng: location.lng
                },
                map: map,
                title: location.name // Menampilkan nama lokasi saat marker diklik
            });
            markers.push(marker);
        }

        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            },
            map: map
        });

        var waypoints = locations.slice(1, locations.length - 1).map(function(location) {
            return {
                location: new google.maps.LatLng(location.lat, location.lng),
                stopover: true
            };
        });
        var request = {
            origin: new google.maps.LatLng(locations[0].lat, locations[0].lng),
            destination: new google.maps.LatLng(locations[locations.length - 1].lat, locations[locations.length - 1]
                .lng),
            waypoints: waypoints,
            optimizeWaypoints: false,
            travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(response);
                var route = response.routes[0];
                var legs = route.legs;
                var lastLeg = legs[legs.length - 1];
                var lastLocation = lastLeg.end_location;

                // Menambahkan marker lokasi mulai sebagai waypoint terakhir
                var startMarker = new google.maps.Marker({
                    position: request.origin,
                    map: map
                });
                // Menghubungkan lokasi terakhir ke lokasi mulai
                var routeToStart = new google.maps.DirectionsRenderer({
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: '#FF0000',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    }
                });
                routeToStart.setMap(map);

                var routeRequestToStart = {
                    origin: lastLocation,
                    destination: request.origin,
                    travelMode: google.maps.TravelMode.DRIVING
                };

                directionsService.route(routeRequestToStart, function(response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        routeToStart.setDirections(response);
                    }
                });
            }
        });


        // Menambahkan event listener untuk menampilkan info window saat marker diklik
        markers.forEach(function(marker) {
            marker.addListener('click', function() {
                var infoWindow = new google.maps.InfoWindow({
                    content: marker.title
                });
                infoWindow.open(map, marker);
            });
        });

        // Fit map bounds to show all markers and route
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition());
        }
        map.fitBounds(bounds);
    }

    // buat manggil fungsi keduanya
    window.addEventListener('load', function() {
            initMapPSO();
            initMap();
    });
</script>
@include('layout.footer')
@include('layout.js')
