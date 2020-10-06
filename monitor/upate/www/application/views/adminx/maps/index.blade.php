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
    
    <!--<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAb7d-G5Ea9j3X_haj37bSPJkSN7PpAp7I&libraries=places"></script>-->
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
        var markers = [];
		setInterval(refreshMapa, 2000);

        function initMap() {
            var now_location = {
                lat: -6.2267392,
                lng: 106.85317119
            };

            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: now_location,
                mapTypeId: 'roadmap'
            });

            // This event listener will call addMarker() when the map is clicked.
            // map.addListener('click', function(event) {
                // addMarker(event.latLng);
            // });
			
			console.log(now_location);
			
			$.get("<?=base_url()?>maps/get_marker", function(response){
				$.each(JSON.parse(response), function(key, data) {
					var latlng = JSON.parse(data.latlng);
					marker = {lat: Number(latlng.lat), lng: Number(latlng.lng)};
					addMarker(marker, data.nik);
				});
			});

            // Adds a marker at the center of the map.
            // addMarker(now_location);
			
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
			
			google.maps.event.addListener(map, 'click', function(me) {
				var result = [me.latLng.lat(), me.latLng.lng()];
				// transition(result);
				console.log(markers);
			});
        }
		
		function refreshMapa() {
			
			DeleteMarkers();
			$.get("<?=base_url()?>maps/get_marker", function(response){
				$.each(JSON.parse(response), function(key, data) {
					var latlng = JSON.parse(data.latlng);
					marker = {lat: Number(latlng.lat), lng: Number(latlng.lng)};
					addMarker2(marker, data.nik);
				});
			});
		}
		
		var numDeltas = 100;
		var delay = 10; //milliseconds
		var i = 0;
		var deltaLat;
		var deltaLng;
		function transition(result){
			i = 0;
			deltaLat = (result[0] - position[0])/numDeltas;
			deltaLng = (result[1] - position[1])/numDeltas;
			moveMarker();
		}

		function moveMarker(){
			position[0] += deltaLat;
			position[1] += deltaLng;
			var latlng = new google.maps.LatLng(position[0], position[1]);
			marker.setPosition(latlng);
			if(i!=numDeltas){
				i++;
				setTimeout(moveMarker, delay);
			}
		}
		
		function addMarker2(location, nik) {
			var infowindow = new google.maps.InfoWindow({
				content: "KODE PIC : "+nik
			});
            var marker = new google.maps.Marker({
                position: location,
				// animation: google.maps.Animation.BOUNCE,
                map: map,
            });
            markers.push(marker);
			marker.addListener('click', function() {
				infowindow.open(map, marker);
			});
        }

        // Adds a marker to the map and push to the array.
        function addMarker(location, nik) {
			var infowindow = new google.maps.InfoWindow({
				content: "KODE PIC : "+nik
			});
            var marker = new google.maps.Marker({
                position: location,
				// animation: google.maps.Animation.BOUNCE,
				animation: google.maps.Animation.DROP,
                map: map,
            });
            markers.push(marker);
			marker.addListener('click', function() {
				infowindow.open(map, marker);
			});
        }

        // Sets the map on all markers in the array.
        function setMapOnAll(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        // Removes the markers from the map, but keeps them in the array.
        function clearMarkers() {
            setMapOnAll(null);
        }

        // Shows any markers currently in the array.
        function showMarkers() {
            setMapOnAll(map);
        }

        // Deletes all markers in the array by removing references to them.
        function deleteMarkersz() {
            clearMarkers();
            markers = [];
        }
		
		function DeleteMarkers() {
            //Loop through all the markers and remove
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }

            markers = [];
        };

        // var map;
		// function initialize() {
			// var myOptions = {
				// zoom: 10,
				// center: new google.maps.LatLng(0, 0),
				// mapTypeId: google.maps.MapTypeId.ROADMAP
			// }

			// return new google.maps.Map(document.getElementById("map"), myOptions);
		// }

		// map = initialize();

		// var beaches = [
			// ['Bondi Beach', -33.890542, 151.274856, 4],
			// ['Coogee Beach', -33.923036, 161.259052, 5],
			// ['Cronulla Beach', -36.028249, 153.157507, 3],
			// ['Manly Beach', -31.80010128657071, 151.38747820854187, 2],
			// ['Maroubra Beach', -33.950198, 151.159302, 1]
		// ];

		// setMarkers(map, beaches);

		// function setMarkers(map, locations) {
			// // var image = new google.maps.MarkerImage('images/beachflag.png',
			// // 	new google.maps.Size(20, 32),
			// // 	new google.maps.Point(0,0),
			// // 	new google.maps.Point(0, 32));
			// // var shadow = new google.maps.MarkerImage('images/beachflag_shadow.png',
			// // 	new google.maps.Size(37, 32),
			// // 	new google.maps.Point(0,0),
			// // 	new google.maps.Point(0, 32));
			// var shape = {
				// coord: [1, 1, 1, 20, 18, 20, 18 , 1],
				// type: 'poly'
			// };
			// var bounds = new google.maps.LatLngBounds();
			// for (var i = 0; i < locations.length; i++) {
				// var beach = locations[i];
				// var myLatLng = new google.maps.LatLng(beach[1], beach[2]);
				// var marker = new google.maps.Marker({
					// position: myLatLng,
					// map: map,
					// // shadow: shadow,
					// // icon: image,
					// shape: shape,
					// title: beach[0],
					// zIndex: beach[3]
				// });
				// bounds.extend(myLatLng);
			// }
			// map.fitBounds(bounds);
		// }
    </script>
	<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAb7d-G5Ea9j3X_haj37bSPJkSN7PpAp7I&callback=initMap" async defer></script>
</body>

</html>