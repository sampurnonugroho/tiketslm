<!DOCTYPE html>
<html>

<head>
    <title>Tracking</title>
    <style>
        /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
        #map {
            height: 100%;
        }

        /* Optional: Makes the sample page fill the window. */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #floating-panel {
            position: absolute;
            top: 10px;
            left: 25%;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            text-align: center;
            font-family: 'Roboto', 'sans-serif';
            line-height: 30px;
            padding-left: 10px;
        }
    </style>
</head>

<body>
    <!--<div id="floating-panel">
        <input onclick="clearMarkers();" type=button value="Hide Markers">
        <input onclick="showMarkers();" type=button value="Show All Markers">
        <input onclick="deleteMarkers();" type=button value="Delete Markers">
    </div>-->
    <div id="map"></div>
    <p>Click on the map to add markers.</p>
    <script>
		var map;
		function initMap() {
			var now_location = {
                lat: -6.2267392,
                lng: 106.85317119
            };
			
			map = new google.maps.Map(document.getElementById('map'), {
				center: now_location,
				zoom: 16
			});
			
			var infoWindow = new google.maps.InfoWindow;	
			if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    infoWindow.setPosition(pos);
                    // infoWindow.setContent('Location found.');
                    // infoWindow.open(map);
					console.log(pos);
                    map.setCenter(pos);		
                }, function() {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
			
			$.get("http://pt-bijak.co.id/rest_api/server/maps/get_marker", function(response){
				// alert(response);
				var flightPath = new google.maps.Polyline({
					path: JSON.parse(response),
					geodesic: true,
					strokeColor: '#FF0000',
					strokeOpacity: 1.0,
					strokeWeight: 4,
				});

				flightPath.setMap(map);
				// $.each(JSON.parse(response), function(key, data) {
					// var latlng = JSON.parse(data.latlng);
					// marker = {lat: Number(latlng.lat), lng: Number(latlng.lng)};
					// addMarker(marker, data.nik);
				// });
			});

			// var flightPlanCoordinates = [
				// { lat: 41.7171899900261, lng: -85.002969973285587 },
				// { lat: 41.716339720601695, lng: -85.00356011920411 },
				// { lat: 41.715420123340095, lng: -85.003969783778473 },
				// { lat: 41.713850219112373, lng: -85.0043800221203 },
				// { lat: 41.709869880890324, lng: -85.004809740676933 },
				// { lat: 41.709570224086633, lng: -85.004860160268152 },

			// ];

			// var flightPlanCoordinates2 = [
				// { lat: 42, lng: -86 },
				// { lat: 42, lng: -87},
				// { lat: 42, lng: -88 },
				// { lat: 43, lng: -88 },
				// { lat: 44, lng: -89 },
				// { lat: 49, lng: -89 },

			// ];

			// var arrayOfFlightPlans = [flightPlanCoordinates, flightPlanCoordinates2];

			// //Loops through all polyline paths and draws each on the map.
			// for (let i = 0; i < 2; i++) {
				// var flightPath = new google.maps.Polyline({
					// path: arrayOfFlightPlans[i],
					// geodesic: true,
					// strokeColor: '#FF0000',
					// strokeOpacity: 1.0,
					// strokeWeight: 4,
				// });

				// flightPath.setMap(map);
			// }
		}
    </script>
	<script
			src="https://code.jquery.com/jquery-3.4.1.min.js"
			integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
			crossorigin="anonymous"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAb7d-G5Ea9j3X_haj37bSPJkSN7PpAp7I&callback=initMap" async defer></script>
    </script>
</body>

</html>