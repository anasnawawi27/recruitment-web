<style>
    .select2-selection__rendered {
        font-size: 14px !important;
        color: #878888 !important;
    }

    .form-goods {
        padding: 8px !important;
        border-radius: 5px !important;
    }

    #goods-width {
        border-radius: 0 !important;
    }

    #goods-length {
        border-radius: 5px 0 0 5px !important;
    }

    #goods-height {
        border-radius: 0 5px 5px 0 !important;
    }

    #map .maps-center-marker {
        position: absolute !important;
        background: url(http://maps.gstatic.com/mapfiles/markers2/marker.png) no-repeat;
        top: 50%;
        left: 50%;
        z-index: 400;
        margin-left: -10px;
        margin-top: -34px;
        height: 34px;
        width: 20px;
        cursor: pointer;
    }

    .modal-body button.close {
        position: absolute;
        right: -20px;
        top: -20px;
        width: 30px;
        height: 30px;
        background-color: white;
        border: 2px solid #000;
        border-radius: 100%;
        z-index: 100;
    }

    .btn-main {
        color: #fff;
        background-color: #d71f35;
        border-color: #d71f35;
    }

    .btn-main:hover {
        color: #fff;
        background-color: #8c0a1b;
        border-color: #8c0a1b;
    }

    .btn-main:focus {
        color: #fff;
        background-color: #8c0a1b;
        border-color: #8c0a1b;
        box-shadow: 0 0 0 0.2rem rgba(135, 144, 247, 0.5);
    }

    .btn-main.disabled {
        color: #fff;
        background-color: #d71f35;
        border-color: #d71f35;
    }

    .modal-footer {
        border-top: 0;
    }

    .pac-container {
        z-index: 10000 !important;
    }
</style>
<div class="modal-body p-0">
    <button class="close" type="button" data-dismiss="modal" aria-label="Close" data-original-title="" title=""><span aria-hidden="true">×</span></button>
    <input type="text" class="form-control" id="input-maps-search" placeholder="Search location...">
    <div id="map" style="height: 400px;"></div>
</div>
<div class="modal-footer p-2" style="display: block">
    <div class="d-grid">
        <button class="btn btn-block btn-info btn-main" id="btn-maps-set">Select Location</button>
    </div>
    <script>
        var maps;
        var geocoder;
        var placesAutocomplete;
        var mapData = {};
        var lat = '-6.304022';
        var lng = '107.3016613';

        function initMap() {
            geocoder = new google.maps.Geocoder();
            var myLatlng = new google.maps.LatLng(lat, lng);
            var options = {
                zoom: 13,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,
                clickableIcons: false,
                zoomControl: true
            }
            maps = new google.maps.Map(document.getElementById("map"), options);
            $('<div/>').addClass('maps-center-marker').appendTo(maps.getDiv());
            saveLatLng(lat, lng);
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(setLocation, geolocationError);
            }
        }

        function setLocation(position) {
            maps.setCenter(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
        }

        function geolocationError(error) {
            var lat = '-6.304022';
            var lng = '107.3016613';
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
            setLocation(lat, lng);
        }

        function saveLatLng(lat, lng) {
            geocodePosition(lat, lng);
        }

        // get position with geocode
        function geocodePosition(lat, lng) {
            geocoder.geocode({
                latLng: new google.maps.LatLng(lat, lng)
            }, function(res) {
                if (res && res.length > 0) {
                    mapData = res[0];
                    mapData.location = {
                        lat: mapData.geometry.location.lat(),
                        lng: mapData.geometry.location.lng(),
                    };
                    $('#input-maps-search').val(res[0].formatted_address);
                }
            });
        }

        initMap();

        // when maps dragged
        google.maps.event.addListener(maps, 'dragend', function() {
            var center = maps.getCenter();
            saveLatLng(center.lat(), center.lng());
        })

        //autocomplete maps input search
        var autocompleteInput = document.getElementById('input-maps-search');
        placesAutocomplete = new google.maps.places.Autocomplete(autocompleteInput);
        placesAutocomplete.setComponentRestrictions({
            country: ["id"],
        });

        placesAutocomplete.bindTo('bounds', maps);
        google.maps.event.addListener(placesAutocomplete, 'place_changed', function() {
            var place = this.getPlace();
            maps.setCenter(place.geometry.location);
            saveLatLng(place.geometry.location.lat(), place.geometry.location.lng())
        });

        // set location
        $('#btn-maps-set').click(function(e) {
            var center = maps.getCenter();
            saveLatLng(center.lat(), center.lng());

            $('#latitude').val(mapData.location.lat);
            $('#longitude').val(mapData.location.lng);
            $('#address').val(mapData.formatted_address);

            $("#modal-maps").modal("hide");

            selectedLat = mapData.location.lat;
            selectedLng = mapData.location.lng;

            if (!$('#display-map').length) {
                let mapThumbnail = `<div id="display-map" style="height: 216px; padding: .4375rem; border: 1px solid #ddd; margin-bottom:10px; border-radius:3px" data-lat="${selectedLat}" data-long="${selectedLng}"></div>`
                let latLongDisplay = `<div><small class="text-muted">Lat : <span id="lat-display">${selectedLat}</span></small> &bull; <small class="text-muted">Long : <span id="lng-display">${selectedLng}</span></small></div>`

                $(mapThumbnail).insertBefore('.set-map-location');
                $(latLongDisplay).insertAfter('#longitude');

                displayMap(selectedLat, selectedLng)
            } else {
                setViewMapThumbnail(selectedLat, selectedLng)
            };

            $('#lat-display').text(mapData.location.lat);
            $('#lng-display').text(mapData.location.lng);
        });
    </script>