<?php $__env->startSection('content'); ?>
	<script src="<?=base_url()?>constellation/assets/equipment/jquery-3.4.1.min.js"></script>
	<link href="<?=base_url()?>constellation/assets/equipment/select2.min.css" rel="stylesheet" />
	<script src="<?=base_url()?>constellation/assets/equipment/select2.min.js"></script>

	<!-- Always visible control bar -->
	<div id="control-bar" class="grey-bg clearfix"><div class="container_12">
	
		<div class="float-left">
			<button type="button" onclick="window.history.back()"><img src="<?=base_url()?>constellation/assets/images/icons/fugue/navigation-180.png" width="16" height="16"> Back</button>
		</div>
			
	</div></div>
	<!-- End control bar -->

	<!-- Content -->
	<article class="container_12">
		<section class="grid_2"></section>
		<section class="grid_8">
			<div class="block-border">
				<form class="block-content form" enctype="multipart/form-data" id="karyawanForm" method="post" action="<?php echo base_url();?><?php echo $url;?>">
					<fieldset class="grey-bg required">
						<legend><?=ucwords(str_replace("_", " ", $active_menu))?> <?=ucfirst($flag)?></legend>
							<input type="hidden" class="full-width" name="id" value="<?=$id?>">
							<p>
								<label>Nama Client / Retail</label>
								<input type="text" class="full-width" name="nama_client" value="<?=$nama_client?>" required>
							</p>
							<p>
								<label>Alamat</label>
								<input type="text" class="full-width" name="alamat" value="<?=$alamat?>" required>
							</p>
							<p>
								<label>Cabang</label>
								<select name="cabang" class="js-example-basic-single2 full-width" required>
									<option value="">- select cabang -</option>
									<?php 
										if(isset($cabang)) {
											echo '<option value="'.$id_cabang.'" selected>'.$cabang.'</option>';
										}
									?>
								</select>
							</p>
							<p>
								<label>Grup Area</label>
								<select name="sektor" class="js-example-basic-single3 full-width" required>
									<option value="">- select group area -</option>
									<?php 
										// if(isset($sektor)) {
											// echo '<option value="'.$id_sektor.'" selected>'.$sektor.'</option>';
										// }
									?>
								</select>
							</p>
							<p>
								<label>Kode Pos</label>
								<input type="text" class="full-width" name="kode_pos" value="<?=$kode_pos?>" required>
							</p>
							<p>
								<label>Wilayah</label>
								<input type="text" class="full-width" name="wilayah" value="<?=$wilayah?>" required>
							</p>
							<p>
								<label>PIC</label>
								<input type="text" class="full-width" name="pic" value="<?=$pic?>" required>
							</p>
							<p>
								<label>Telepon</label>
								<input type="text" class="full-width" name="telp" value="<?=$telp?>" required>
							</p>
							<p>
								<label>KTP</label>
								<input type="text" class="full-width" name="ktp" value="<?=$ktp?>" required>
							</p>
						<button type="submit" style="float: right">Simpan</button>
					</fieldset>
				</form>
			</div>
		</section>
		
		<div class="clear"></div>
	</article>
	
	<script>
	
		jq341 = jQuery.noConflict(true);
		console.log( "<h3>After $.noConflict(true)</h3>" );
		console.log( "2nd loaded jQuery version (jq162): " + jq341.fn.jquery + "<br>" );
	
		jq341(document).ready(function()
		{
			jq341('.js-example-basic-single2').select2({
				tags: true,
				tokenSeparators: [','],
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'select/select_branch'?>',
					delay: 250,
					type: "POST",
					data: function(params) {
						return {
							search: params.term
						}
					},
					processResults: function (data, page) {
						console.log(data);
						return {
							results: data
						};
					}
				},
				maximumSelectionLength: 3,

				// add "(new tag)" for new tags
				createTag: function (params) {
				  var term = jq341.trim(params.term);

				  if (term === '') {
					return null;
				  }

				  return {
					id: term,
					text: term + ' (add new)'
				  };
				},
			}).on('select2:select', function (evt) {
				jq341(".js-example-basic-single3").val('').trigger('change')
			});
			
			jq341('.js-example-basic-single3').select2({
				tags: true,
				tokenSeparators: [','],
				ajax: {
					dataType: 'json',
					url: '<?php echo base_url().'select/select_area'?>',
					delay: 250,
					type: "POST",
					data: function(params) {
						return {
							search: params.term,
							branch: jq341(".js-example-basic-single2 option:selected").val()
						}
					},
					processResults: function (data, page) {
						// console.log(data);
						return {
							results: data
						};
					}
				},
				maximumSelectionLength: 3,

				// add "(new tag)" for new tags
				createTag: function (params) {
				  var term = jq341.trim(params.term);

				  if (term === '') {
					return null;
				  }

				  return {
					id: term,
					text: term + ' (add new)'
				  };
				},
			});
		});
		
		var map;
		function initialize() {
			var prop = {
				center: new google.maps.LatLng(51.508742, -0.120850),
				zoom: 10,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			return new google.maps.Map(document.getElementById("w3docs-map"), prop);
		}
		
		// Demo modal
		function openModalZ()
		{
			var array = [];
			$.modal({
				content: '<input id="searchInput" class="controls" type="text" placeholder="Enter a location"><div id="w3docs-map" style="width:100%;height:380px;display: none"></div>',
				title: 'Example modal window',
				minWidth: $(window).width()-((20/100)*$(window).width()),
				minHeight: $(window).height()-((40/100)*$(window).height()),
				buttons: {
					'Add LatLng': function(win) { 
					
						if(array.length==0) {
							alert("Set Point Marker First!");
						} else {
							$("#latlng").val(JSON.stringify(array));
							$("#latlng2").val(array[0].latlng);
							win.closeModal();
						}
					}
				}
			});
			
			map = initialize();
            document.getElementById("w3docs-map").style.display = 'block';
            google.maps.event.trigger(map, 'resize');
			
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
                    map.setCenter(pos);		
                }, function() {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
			
			//	create the ContextMenuOptions object
            var contextMenuOptions = {};
            contextMenuOptions.classNames = {
                menu: 'context_menu',
                menuSeparator: 'context_menu_separator'
            };

            //	create an array of ContextMenuItem objects
            var menuItems = [];
            menuItems.push({
                className: 'context_menu_item',
                eventName: 'zoom_in_click',
                label: 'Zoom in'
            });
            menuItems.push({
                className: 'context_menu_item',
                eventName: 'zoom_out_click',
                label: 'Zoom out'
            });
            //	a menuItem with no properties will be rendered as a separator
            menuItems.push({});
			menuItems.push({
                className: 'context_menu_item',
                eventName: 'get_latlng_click',
                label: 'Get Latitude and Longitude'
            });
            menuItems.push({});
            menuItems.push({
                className: 'context_menu_item',
                eventName: 'center_map_click',
                label: 'Center map here'
            });
            contextMenuOptions.menuItems = menuItems;

            //	create the ContextMenu object
            var contextMenu = new ContextMenu(map, contextMenuOptions);
			var geocoder = new google.maps.Geocoder();

            //	display the ContextMenu on a Map right click
            google.maps.event.addListener(map, 'rightclick', function(mouseEvent) {
                contextMenu.show(mouseEvent.latLng);
            });
			
			var input = document.getElementById('searchInput');
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

			var autocomplete = new google.maps.places.Autocomplete(input);
			autocomplete.bindTo('bounds', map);
			
			var infowindow = new google.maps.InfoWindow();
			var marker = new google.maps.Marker({
				map: map,
				anchorPoint: new google.maps.Point(0, -29)
			});
			
			autocomplete.addListener('place_changed', function() {
				infowindow.close();
				marker.setVisible(false);
				var place = autocomplete.getPlace();
				if (!place.geometry) {
					window.alert("Autocomplete's returned place contains no geometry");
					return;
				}
		  
				// If the place has a geometry, then present it on a map.
				if (place.geometry.viewport) {
					map.fitBounds(place.geometry.viewport);
				} else {
					map.setCenter(place.geometry.location);
					map.setZoom(17);
				}
				marker.setIcon(({
					url: place.icon,
					size: new google.maps.Size(71, 71),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(17, 34),
					scaledSize: new google.maps.Size(35, 35)
				}));
				marker.setPosition(place.geometry.location);
				// marker.setVisible(true);
			
				var address = '';
				if (place.address_components) {
					address = [
					  (place.address_components[0] && place.address_components[0].short_name || ''),
					  (place.address_components[1] && place.address_components[1].short_name || ''),
					  (place.address_components[2] && place.address_components[2].short_name || '')
					].join(' ');
				}
			
				// infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
				// infowindow.open(map, marker);
			  
				//Location details
				for (var i = 0; i < place.address_components.length; i++) {
					if(place.address_components[i].types[0] == 'postal_code'){
						document.getElementById('postal_code').innerHTML = place.address_components[i].long_name;
					}
					if(place.address_components[i].types[0] == 'country'){
						document.getElementById('country').innerHTML = place.address_components[i].long_name;
					}
				}
				document.getElementById('location').innerHTML = place.formatted_address;
				document.getElementById('lat').innerHTML = place.geometry.location.lat();
				document.getElementById('lon').innerHTML = place.geometry.location.lng();
			});
			
			//	listen for the ContextMenu 'menu_item_selected' event
            google.maps.event.addListener(contextMenu, 'menu_item_selected', function(latLng, eventName) {
				infowindow.close();
				marker.setVisible(false);
                //	latLng is the position of the ContextMenu
                //	eventName is the eventName defined for the clicked ContextMenuItem in the ContextMenuOptions
                switch (eventName) {
                    case 'zoom_in_click':
                        map.setZoom(map.getZoom() + 1);
                        break;
                    case 'zoom_out_click':
                        map.setZoom(map.getZoom() - 1);
                        break;
                    case 'get_latlng_click':
						geocoder.geocode({
							'latLng': latLng
						}, function(results, status) {
							if (status == google.maps.GeocoderStatus.OK) {
								if (results[0]) {
									var place = results[0];
									
									marker.setIcon(({
										url: results[0].icon,
										size: new google.maps.Size(71, 71),
										origin: new google.maps.Point(0, 0),
										anchor: new google.maps.Point(17, 34),
										scaledSize: new google.maps.Size(35, 35)
									}));
									marker.setPosition(latLng);
									marker.setVisible(true);
								
									var address = '';
									if (place.address_components) {
										address = [
										  (place.address_components[0] && place.address_components[0].short_name || ''),
										  (place.address_components[1] && place.address_components[1].short_name || ''),
										  (place.address_components[2] && place.address_components[2].short_name || '')
										].join(' ');
									}
									
									for (var i=0; i<results[0].address_components.length; i++) {
										for (var b=0; b<results[0].address_components[i].types.length;b++) {

										//there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
											if (results[0].address_components[i].types[b] == "postal_code") {
												//this is the object you are looking for
												// document.getElementById('postal_code').innerHTML = results[0].address_components[i].long_name;
												postal_code = results[0].address_components[i].long_name;
												break;
											}
											if (results[0].address_components[i].types[b] == "country") {
												//this is the object you are looking for
												// document.getElementById('country').innerHTML = results[0].address_components[i].long_name;
												country = results[0].address_components[i].long_name;
												break;
											}
											
											// console.log(results[0].address_components[i].types[b]);
											if (results[0].address_components[i].types[b] == "administrative_area_level_4") {
												//this is the object you are looking for
												city= results[0].address_components[i];
												break;
											}
										}
									}
									
									array.push({
										address: results[0].formatted_address,
										postal_code: postal_code,
										city: city.short_name,
										country: country,
										latlng: latLng
									});			

									console.log(JSON.stringify(array));
									
									infowindow.setContent('<div><strong>' + results[0].formatted_address + '</strong>');
									infowindow.open(map, marker);
									
									// document.getElementById('location').innerHTML = place.formatted_address;
									// document.getElementById('lat').innerHTML = latLng.lat();
									// document.getElementById('lon').innerHTML = latLng.lng();
								}
							}
						});
					
						
                        break;
                    case 'center_map_click':
                        map.panTo(latLng);
                        break;
                }
            });
		}
	</script>
	
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>