<!DOCTYPE html>
<html>

<head>
    <title>Removing Markers</title>
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
    <div id="floating-panel">
        <input onclick="clearMarkers();" type=button value="Hide Markers">
        <input onclick="showMarkers();" type=button value="Show All Markers">
        <input onclick="deleteMarkers();" type=button value="Delete Markers">
    </div>
    <div id="map"></div>
    <p>Click on the map to add markers.</p>
    <script>
		var map = undefined;
		var marker = undefined;
		var position = [43, -89];

		function initMap() {

			var latlng = new google.maps.LatLng(position[0], position[1]);
			var myOptions = {
				zoom: 8,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById("map"), myOptions);

			marker = new google.maps.Marker({
				position: latlng,
				map: map,
				title: "Your current location!"
			});

			google.maps.event.addListener(map, 'click', function(me) {
				var result = [me.latLng.lat(), me.latLng.lng()];
				transition(result);
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
    </script>
	<script src="<?=base_url()?>depend/js/jquery-1.7.1.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAb7d-G5Ea9j3X_haj37bSPJkSN7PpAp7I&callback=initMap" async defer></script>
    </script>
</body>

</html>