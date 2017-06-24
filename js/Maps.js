function Maps(){

}


Maps.prototype.geoFindMe = function(callback) {

  	if (!navigator.geolocation){
		alert("Geolocation is not supported by your browser");
		return;
  	} else {
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
}



Maps.prototype.init = function(){

	var _instance = this;

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
		_instance.computeTotalDistance(directionsDisplay.getDirections());
	});

  	// Funcao responsavel por mostrar as rotas no mapa
  	_instance.displayRoute('Carapina grande', 'Central carapina', directionsService, directionsDisplay);
	/*
	  var cityCircle = new google.maps.Circle({
	  strokeColor: '#4285f4',
	  strokeOpacity: 0.35,
	  strokeWeight: 15,
	  fillColor: '#4285f4',
	  fillOpacity: 1,
	  map: map,
	  center: {lat:-20.2203825, lng: -40.2818566},
	  radius: 100
	});


	
	*/
	/*var marker = new google.maps.Marker({
		position: {lat:-20.2203825, lng: -40.2818566},
		map: map,
		animation: google.maps.Animation.DROP,
		title: "Titulo" 
	});*/

	return {
		map: map,
		service : directionsService,
		display : directionsDisplay
	};
}

Maps.prototype.displayRoute = function(origin, destination, service, display) {
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

Maps.prototype.computeTotalDistance = function(result) {
	var total = 0;
	var myroute = result.routes[0];
	for (var i = 0; i < myroute.legs.length; i++)
		total += myroute.legs[i].distance.value;
	total = total / 1000;
	document.getElementById('total').innerHTML = total + ' km';
}