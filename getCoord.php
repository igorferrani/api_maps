<div class="custom">
	<LINK REL=StyleSheet HREF="http://mondeca.com/mdc_css/A.weather.css.pagespeed.cf.bm2KxYrJN6.css" TYPE="text/css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.simpleWeather/3.0.2/jquery.simpleWeather.min.js"></script>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyBVTKLztwVOGDuo1qGsjHzdY7wXRcKbAVI"> </script>
	<script>
		jQuery(document).ready(function($) {
			var weatheron = 0;
			$('#weatherbutton').on('click', function() {
				weatheron = 1 - weatheron;
				//ga('send', 'event', 'button', 'click', 'weather');
				if (weatheron) {
					lng = $('#lng')[0].innerHTML;
					lat = $('#lat')[0].innerHTML;
					loadWeather(lat + ',' + lng);
					$('#weatherbutton').html('Hide weather');
					$('#weatherbutton').css('color', 'white');
				} else {
					$('#weather').html('');
					$('#weatherbutton').html('Show weather');
					$('#weatherbutton').css('color', 'white');
				}
			});

			function loadWeather(location, woeid) {
				$.simpleWeather({
					location: location,
					woeid: woeid,
					unit: 'c',
					success: function(weather) {
						html = '<h2><i class="icon-' + weather.code + '"></i> ' + weather.temp + '&deg;' + weather.units.temp + '</h2>';
						html += '<ul><li>' + weather.city + ', ' + weather.region + '</li>';
						html += '<li class="currently">' + weather.currently + '</li>';
						html += '<li>' + weather.wind.direction + ' ' + weather.wind.speed + ' ' + weather.units.speed + '</li></ul>';
						$("#weather").html(html);
					},
					error: function(error) {
						$("#weather").html('<p>' + error + '</p>');
					}
				});
			}
		});
	</script>
		
	<script>
		function load() {
			if (GBrowserIsCompatible()) {
				var map = new GMap2(document.getElementById("map"));
				map.addControl(new GSmallMapControl());
				map.addControl(new GMapTypeControl());
				var center = new GLatLng(48.87146, 2.35500);
				map.setCenter(center, 15);
				geocoder = new GClientGeocoder();
				var marker = new GMarker(center, {
					draggable: true
				});
				map.addOverlay(marker);
				document.getElementById("lat").innerHTML = center.lat().toFixed(5);
				document.getElementById("lng").innerHTML = center.lng().toFixed(5);
				GEvent.addListener(map, "dragstart", function() {
					document.getElementById("weather").innerHTML = "";
					document.getElementById("weatherbutton").innerHTML = "Show weather";
				});
				GEvent.addListener(marker, "dragend", function() {
					//ga('send', 'event', 'map', 'drag/move', 'map');
					var point = marker.getPoint();
					map.panTo(point);
					document.getElementById("lat").innerHTML = point.lat().toFixed(5);
					document.getElementById("lng").innerHTML = point.lng().toFixed(5);
				});
				GEvent.addListener(marker, "dragstart", function() {
					document.getElementById("weather").innerHTML = "";
					document.getElementById("weatherbutton").innerHTML = "Show weather";
				});
				GEvent.addListener(map, "moveend", function() {
					//ga('send', 'event', 'map', 'drag/move', 'map');
					map.clearOverlays();
					var center = map.getCenter();
					var marker = new GMarker(center, {
						draggable: true
					});
					map.addOverlay(marker);
					document.getElementById("lat").innerHTML = center.lat().toFixed(5);
					document.getElementById("lng").innerHTML = center.lng().toFixed(5);
					GEvent.addListener(marker, "dragend", function() {
						//ga('send', 'event', 'map', 'drag/move', 'map');
						var point = marker.getPoint();
						map.panTo(point);
						document.getElementById("lat").innerHTML = point.lat().toFixed(5);
						document.getElementById("lng").innerHTML = point.lng().toFixed(5);
					});
				});
			}
		}

		function showAddress(address) {
			var map = new GMap2(document.getElementById("map"));
			map.addControl(new GSmallMapControl());
			map.addControl(new GMapTypeControl());
			if (geocoder) {
				geocoder.getLatLng(address, function(point) {
					if (!point) {
						alert(address + " not found");
					} else {
						document.getElementById("lat").innerHTML = point.lat().toFixed(5);
						document.getElementById("lng").innerHTML = point.lng().toFixed(5);
						map.clearOverlays()
						map.setCenter(point, 14);
						var marker = new GMarker(point, {
							draggable: true
						});
						map.addOverlay(marker);
						GEvent.addListener(marker, "dragend", function() {
							var pt = marker.getPoint();
							map.panTo(pt);
							document.getElementById("lat").innerHTML = pt.lat().toFixed(5);
							document.getElementById("lng").innerHTML = pt.lng().toFixed(5);
						});
						GEvent.addListener(map, "moveend", function() {
							map.clearOverlays();
							var center = map.getCenter();
							var marker = new GMarker(center, {
								draggable: true
							});
							map.addOverlay(marker);
							document.getElementById("lat").innerHTML = center.lat().toFixed(5);
							document.getElementById("lng").innerHTML = center.lng().toFixed(5);
							GEvent.addListener(marker, "dragend", function() {
								var pt = marker.getPoint();
								map.panTo(pt);
								document.getElementById("lat").innerHTML = pt.lat().toFixed(5);
								document.getElementById("lng").innerHTML = pt.lng().toFixed(5);
							});
							GEvent.addListener(marker, "dragstart", function() {
								console.log('dragstart');
								document.getElementById("weather").innerHTML = "";
								document.getElementById("weatherbutton").innerHTML = "Show weather";
							});
						});
					}
				});
			}
		}
		if (window.attachEvent) {
			window.attachEvent('onload', load);
		} else {
			if (window.onload) {
				var curronload = window.onload;
				var newonload = function() {
					curronload();
					load();
				};
				window.onload = newonload;
			} else {
				window.onload = load;
			}
		}
	</script>
	<div style="display:flex;flex-wrap:wrap; margin-top: 2%; margin-bottom: 2%;">
		<div align="center" id="map" style="min-width:300px; min-height:300px;max-width:600px;max-height:600px; width:48%;"></div>
		<div style="display: flex; flex-wrap:wrap; flex-direction:column;
			max-width: 600px; justify-content:flex-start; min-width:300px; width:48%; ">

				<input type="text" size="60" name="address" id="inputaddress" value="35 boulevard de Strasbourg Paris France" " /> 
				<input type="submit" value="Search" class="btn btn-primary btn-mondeca"/>
				<button type="button" onclick="showAddress($('#inputaddress').val());">buscar</button>
			
			<table class="table" style="margin-left:5%; margin-top:5%; width:100%;">
				<tbody>
					<tr>
						<th>Latitude</th>
						<th>Longitude</th>
						<th></th>
					</tr>
					<tr>
						<td style="font-size: 48px; color: green;" id="lat">&nbsp;</td>
						<td style="font-size: 48px; color: green;" id="lng">&nbsp;</td>
					</tr>
				</tbody>
			</table>
			<div id="weather"></div>
		</div>
	</div>
	<script>
		var formaddress = document.getElementById("address");
		//formaddress.addEventListener("focusin", myFocusFunction);
		//formaddress.addEventListener("focusout", myBlurFunction);

		function myFocusFunction() {
			document.getElementById("weather").innerHTML = "";
		}

		function myBlurFunction() {
			document.getElementById("weather").innerHTML = "";
		}
	</script>
</div>