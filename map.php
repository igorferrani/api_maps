<!DOCTYPE html>
<html>
	<head><script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB462FLjWiLpowolQkDBbM4Rp9pEh6IF1g&callback=initMap" async defer></script>
		<!-- This stylesheet contains specific styles for displaying the map
			on this page. Replace it with your own styles as described in the
			documentation:
			https://developers.google.com/maps/documentation/javascript/tutorial -->
		<!-- <link rel="stylesheet" href="/maps/documentation/javascript/demos/demos.css"> -->
	<style type="text/css">
      html, body { height: 100%; margin: 0; padding: 0; }
      #map { height: 100%; }
    </style>
	</head>
	<body>
	<div  style="background-color:#ccc;width:300px;height:200px;position:absolute;top:0;left:0;z-index:9999">
		<p id="data_location"></p>
		<input type="text" id="inputBuscaOrigem" placeholder="Endereço de origem">
		<input type="text" id="inputBuscaDestino" placeholder="Endereço de origem">
		<button type="button" id="btn_buscar">buscar</button>
	</div>

		<div id="map"></div>
		<script>
			function geoFindMe(callback) {

			  if (!navigator.geolocation){
			    alert("Geolocation is not supported by your browser");
			    return;
			  }

			  function success(position) {
			    var latitude  = position.coords.latitude;
			    var longitude = position.coords.longitude;
			    return callback(position.coords);
			  }

			  function error() {
			    alert("Unable to retrieve your location");
			  }

			  navigator.geolocation.getCurrentPosition(success, error);
			}




			function initMap(callback) {

				window._instance = this;

				// Specify features and elements to define styles.
		        var styleArray = [
		          {
		            featureType: "all",
		            stylers: [
		             { saturation: -80 } // de 100 a 0, 0 a -100
		            ]
		          },{
		            featureType: "road.arterial",
		            elementType: "geometry",
		            stylers: [
		              { hue: "#00ffee" },
		              { saturation: 50 }
		            ]
		          },{
		            featureType: "poi.business",
		            elementType: "labels",
		            stylers: [
		              { visibility: "off" }
		            ]
		          }
		        ];

		        var styledMap = new google.maps.StyledMapType(styleArray, {
					name: "nome_do_meu_estilo"
				});
		        

		        geoFindMe(function(position){

					var cord_center = {lat: position.latitude, lng: position.longitude};

					// Create a map object, and include the MapTypeId to add
					// to the map type control.
					var mapOptions = {
						zoom: 11,
						center: cord_center,
						mapTypeControlOptions: {
							mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
						}
					};


					var map = new google.maps.Map(document.getElementById('map'), mapOptions);


					var directionsDisplay = new google.maps.DirectionsRenderer({
						map: map
					});

					//Associate the styled map with the MapTypeId and set it to display.
					map.mapTypes.set('map_style', styledMap);
					map.setMapTypeId('map_style');

					window.displayMap = directionsDisplay;
				});
			}



			/*geoFindMe(function(position){

				var cord_origem = {lat: position.coords.latitude, lng: position.coords.longitude};
	        	var cord_destino = {lat: -20.18692, lng: -40.25651};

				if(window.displayMap){
					setDirection(cord_origem, cord_destino, directionsDisplay);
				} else {
					initMap();
				}
			});*/


			function setDataLocation(data){
				window.map = data;

				var html = 'Distância: '+data.routes[0].legs[0].distance.text+'<br>';
					html+= 'Tempo: '+data.routes[0].legs[0].duration.text+'<br>';
					html+= 'Origem: '+data.routes[0].legs[0].start_address+'<br>';
					html+= 'Destino: '+data.routes[0].legs[0].end_address+'<br>';

				document.getElementById('data_location').innerHTML = html;
			}



			function setDirection(args, callback){
				// Set destination, origin and travel mode.
				var request = {
					destination: args.cord_destino,
					origin: args.cord_origem,
					travelMode: 'DRIVING'
				};

				// Pass the directions request to the directions service.
				var directionsService = new google.maps.DirectionsService();
				directionsService.route(request, function(response, status) {
					if (status == 'OK') {
						// Display the route on the map.
						args.directionsDisplay.setDirections(response);
						setDataLocation(response);
					}
				});
			}




			function getCoordOfAddress(address, callback){
				address = address.replace(/ /g, '+');
				var api_key = "AIzaSyD8Lh0gVQ7uZh5JqkH71EAyFsmliUhi2q4";
				var url = "https://maps.googleapis.com/maps/api/geocode/json?address="+address+"&key="+api_key;
				console.log(url);

				$.ajax({
					url : url,
					method : 'GET',
					success: function(response){
						callback(response);
					},
					error: function(response){
						callback(response);
					}
				});
			}

			$("#btn_buscar").on('click', function(){
				var origem = $('#inputBuscaOrigem').val();
				var destino = $('#inputBuscaDestino').val();
				getCoordOfAddress(origem, function(response){
					if(response.status == 'OK'){
						var lat = response.results[0].geometry.location.lat;
						var lng = response.results[0].geometry.location.lng;
					} else {
						alert('Não foi possivel buscar o endereco');
					}
				});
			});
			
		</script>

		
	</body>
</html>