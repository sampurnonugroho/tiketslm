<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<title>GeekOnJava: Directions Complex</title>


		<style>
			html {
				height: 100%;
			}

			body {
				height: 100%;
				margin: 0px;
				font-family: Helvetica, Arial;
			}
		</style>

		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAb7d-G5Ea9j3X_haj37bSPJkSN7PpAp7I&sensor=false"></script>
		<script type="text/javascript" src="<?=base_url()?>assets/v3_epoly.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript">
			var map;
			var infowindow = null;
			var markers = [];
			var position;
			
			var sitess = [];
			var markerss = [];

			function initialize() {
				// var centerMap = new google.maps.LatLng(45.3517923, 6.3101660);
				var centerMap = new google.maps.LatLng(-6.2267392, 106.85317119);

				var myOptions = {
					zoom: 10,
					center: centerMap,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				}

				map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

				$.get("<?=base_url()?>maps/get_marker", function(response){
					$.each(JSON.parse(response), function(key, data) {
						var latlng = JSON.parse(data.latlng);
						var datas = [data.nik, Number(latlng.lat), Number(latlng.lng), 1, data.nama];
						
						sitess.push(datas);
					});
					setZoom(map, sitess);
					setMarkers(map, sitess);
				});

				infowindow = new google.maps.InfoWindow({
					content: "Loading..."
				});
			}
			
			setInterval(refreshMapa, 2000);
			
			function refreshMapa() {
				$.get("<?=base_url()?>maps/get_marker", function(response){
					$.each(JSON.parse(response), function(key, data) {
						// var latlng = JSON.parse(data.latlng);
						// marker = {lat: Number(latlng.lat), lng: Number(latlng.lng)};
						// addMarker(marker, data.nik);
						
						if(markerss[data.nik]!==undefined) {
							var latlng = JSON.parse(data.latlng);
							var uluru = {lat: Number(latlng.lat), lng: Number(latlng.lng)};
							
							moove(data.nik, uluru);
							
						}
					});
				});
			}
			
			// function AddCar(data) {
                // var icon = { // car icon
                    // path: 'M29.395,0H17.636c-3.117,0-5.643,3.467-5.643,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759   c3.116,0,5.644-2.527,5.644-5.644V6.584C35.037,3.467,32.511,0,29.395,0z M34.05,14.188v11.665l-2.729,0.351v-4.806L34.05,14.188z    M32.618,10.773c-1.016,3.9-2.219,8.51-2.219,8.51H16.631l-2.222-8.51C14.41,10.773,23.293,7.755,32.618,10.773z M15.741,21.713   v4.492l-2.73-0.349V14.502L15.741,21.713z M13.011,37.938V27.579l2.73,0.343v8.196L13.011,37.938z M14.568,40.882l2.218-3.336 h13.771l2.219,3.336H14.568z M31.321,35.805v-7.872l2.729-0.355v10.048L31.321,35.805',
                    // scale: 0.4,
                    // fillColor: "#427af4", //<-- Car Color, you can change it 
                    // fillOpacity: 1,
                    // strokeWeight: 1,
                    // anchor: new google.maps.Point(0, 5),
                    // // rotation: data.val().angle //<-- Car angle
                    // rotation: 0 //<-- Car angle
                // };
				
				// var latlng = JSON.parse(data.latlng);
                // var uluru = {lat: Number(latlng.lat), lng: Number(latlng.lng)};
				// var bounds = new google.maps.LatLngBounds();
				
				// var infowindow = new google.maps.InfoWindow();
				
				// var marker = new google.maps.Marker({
                    // position: uluru,
                    // // icon: icon,
                    // map: map
                // });
				
				// bounds.extend(marker.position);
				
				// markers[data.nik] = marker;
				
				// marker.addListener('click', function() {
					// infowindow.setContent("<center>"+data.nik+"<br>"+data.nama+"</center>");
					// infowindow.open(map, marker);
				// });
				
				// infowindow.setContent("<center>"+data.nik+"<br>"+data.nama+"</center>");
				// infowindow.open(map, marker);
            // }

			/*
			This functions sets the markers (array)
			*/
			function setMarkers(map, markers) {
				for (var i = 0; i < markers.length; i++) {
					var site = markers[i];
					var siteLatLng = new google.maps.LatLng(site[1], site[2]);
					
					var icon = { // car icon
						path: 'M29.395,0H17.636c-3.117,0-5.643,3.467-5.643,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759   c3.116,0,5.644-2.527,5.644-5.644V6.584C35.037,3.467,32.511,0,29.395,0z M34.05,14.188v11.665l-2.729,0.351v-4.806L34.05,14.188z    M32.618,10.773c-1.016,3.9-2.219,8.51-2.219,8.51H16.631l-2.222-8.51C14.41,10.773,23.293,7.755,32.618,10.773z M15.741,21.713   v4.492l-2.73-0.349V14.502L15.741,21.713z M13.011,37.938V27.579l2.73,0.343v8.196L13.011,37.938z M14.568,40.882l2.218-3.336 h13.771l2.219,3.336H14.568z M31.321,35.805v-7.872l2.729-0.355v10.048L31.321,35.805',
						scale: 0.4,
						fillColor: "#427af4", //<-- Car Color, you can change it 
						fillOpacity: 1,
						strokeWeight: 1,
						anchor: new google.maps.Point(0, 5),
						// rotation: data.val().angle //<-- Car angle
						rotation: 0 //<-- Car angle
					};

					var marker = new google.maps.Marker({
						icon: "<?=base_url()?>assets/icon/van.png",
						position: siteLatLng,
						map: map,
						title: site[0],
						zIndex: site[3],
						html: site[4],
						// Markers drop on the map
						animation: google.maps.Animation.DROP
					});
					
					markerss[site[0]] = marker;

					google.maps.event.addListener(marker, "click", function() {
						infowindow.setContent(this.html);
						infowindow.open(map, this);
					});
				}
			}

			/*
			Set the zoom to fit comfortably all the markers in the map
			*/
			function setZoom(map, markers) {
				var boundbox = new google.maps.LatLngBounds();
				for (var i = 0; i < markers.length; i++) {
					boundbox.extend(new google.maps.LatLng(markers[i][1], markers[i][2]));
				}
				map.setCenter(boundbox.getCenter());
				map.fitBounds(boundbox);
			}
			
			function moove(index, latlng) {
				marker = markerss[index];
				marker.setPosition(latlng);
			}
		</script>
	</head>

	<body onload="initialize()">
		<div id="map_canvas" style="width:100%;height:100%;"></div>
	</body>
</html>