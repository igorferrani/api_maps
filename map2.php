<!DOCTYPE html>
<html>
	<head>
	    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	    <meta charset="utf-8">
	    <title>Draggable directions</title>
	    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
	    <style>
				html, body {
				height: 100%;
				margin: 0;
				padding: 0;
				}
				#map {
				height: 100%;
				float: left;
				width: 63%;
				height: 100%;
				}
				#right-panel {
				float: right;
				width: 34%;
				height: 100%;
				}
				#right-panel {
				  font-family: 'Roboto','sans-serif';
				  line-height: 30px;
				  padding-left: 10px;
				}

				#right-panel select, #right-panel input {
				  font-size: 15px;
				}

				#right-panel select {
				  width: 100%;
				}

				#right-panel i {
				  font-size: 12px;
				}

		      .panel {
		        height: 100%;
		        overflow: auto;
		      }
		</style>
	</head>

	<body>
		<div id="layer_black" style="background-color:rgba(0,0,0,.8);width:100%;height:100%;position:fixed;top:0;left:0;z-index:9999"></div>

		<div  style="background-color:#ccc;width:300px;height:200px;position:absolute;top:0;left:0;z-index:500">
			<p id="data_location"></p>
			<input type="text" id="inputBuscaOrigem" placeholder="Endereço de origem">
			<input type="text" id="inputBuscaDestino" placeholder="Endereço de origem">
			<button type="button" id="btn_buscar">buscar</button>
		</div>

	    <div id="map"></div>
	    <div id="right-panel">
	      <p>Total Distance: <span id="total"></span></p>
	    </div>
	    <script>
	    	$("#btn_buscar").on('click', function(){
				var origem = $('#inputBuscaOrigem').val();
				var destino = $('#inputBuscaDestino').val();
				displayRoute(origem, destino, directionsService, directionsDisplay);
			});

			function geoFindMe(callback) {

			  if (!navigator.geolocation){
			    alert("Geolocation is not supported by your browser");
			    return;
			  }

			  function success(position) {
			    var latitude  = position.coords.latitude;
			    var longitude = position.coords.longitude;

			    $("#layer_black").fadeOut();
			    return callback(position.coords);
			  }

			  function error() {
			    alert("Unable to retrieve your location");
			  }

			  navigator.geolocation.getCurrentPosition(success, error);
			}

			geoFindMe(function(location){
				console.log(location);
			});

			var directionsService;
			var directionsDisplay;

		    function initMap(){
			    // Instancia a classe principal do DirectionsService
				directionsService = new google.maps.DirectionsService;
				directionsDisplay = new google.maps.DirectionsRenderer;

			  // Define as configuracoes do mapa antes de redenriza-lo
			  var map = new google.maps.Map(document.getElementById('map'), {
			    zoom: 4,
			    //center: {lat: -24.345, lng: 134.46}  // Australia.
			  });

			  // Instancia classe de renderizacao
			  directionsDisplay = new google.maps.DirectionsRenderer({
			    draggable: true, // Torna cada ponto da rota arrastavel
			  });

			  // Setando as configuracoes de todo o mapa, depois da classe instanciada
			  directionsDisplay.setMap(map);
			  directionsDisplay.setPanel(document.getElementById('right-panel'));

			  // Funcao que fica ouvindo cada evento acionado na aplicacao
			  // neste caso, retorna todas as alteracoes que aconteceram ao moficar a direcao de percursso.
			  directionsDisplay.addListener('directions_changed', function() {
			  	// Calculo da soma de todas as distancias percorridas
			    computeTotalDistance(directionsDisplay.getDirections());
			  });

			  // Funcao responsavel por mostrar as rotas no mapa
			  displayRoute('Carapina grande', 'Central carapina', directionsService, directionsDisplay);
			}

			function displayRoute(origin, destination, service, display) {
			  service.route({
			    origin: origin,
			    destination: destination,
			    //waypoints: [{location: 'Cocklebiddy, WA'}], // pontos de passagem
			    //avoidTolls: true, // evita pedagios
			    provideRouteAlternatives: true, // aceita sugestao de rotas alternativas
			    //avoidHighways: true, // evita as principais rodovias
			    optimizeWaypoints: true,
			    travelMode: google.maps.TravelMode.DRIVING, // DRIVING, BICYCLING, TRANSIT, WALKING(à pé)
			    drivingOptions: {
					departureTime: new Date(),
					trafficModel: google.maps.TrafficModel.PESSIMISTIC // OPTIMISTIC = tempo mais curto, PESSIMISTIC = tempo mais longo de viajem
				},
			    avoidTolls: true
			  }, function(response, status) {
			    if (status === google.maps.DirectionsStatus.OK) {
			    	// Seta no mapa as coordenadas passadas
			      	display.setDirections(response);
			    } else {
			      	alert('Could not display directions due to: ' + status);
			    }
			  });
			}

			function computeTotalDistance(result) {
			  var total = 0;
			  var myroute = result.routes[0];
			  for (var i = 0; i < myroute.legs.length; i++) {
			    total += myroute.legs[i].distance.value;
			  }
			  total = total / 1000;
			  document.getElementById('total').innerHTML = total + ' km';
			}
	    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8Lh0gVQ7uZh5JqkH71EAyFsmliUhi2q4&callback=initMap"
        async defer></script>
  </body>
</html>