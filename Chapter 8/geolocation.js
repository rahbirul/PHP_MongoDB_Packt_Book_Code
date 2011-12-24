var mapContainer = document.getElementById('map');
var map;

function init() {
    var mapOptions = {zoom: 16,
                      disableDefaultUI: true, 
                      mapTypeId: google.maps.MapTypeId.ROADMAP};
    map = new google.maps.Map(mapContainer, mapOptions);
    detectLocation();
}

function detectLocation(){
    var options = { enableHighAccuracy: true, maximumAge: 1000, timeout: 30000};
    //check if the browser supports geolocation
    if (window.navigator.geolocation) {
        window.navigator.geolocation.getCurrentPosition(drawLocationOnMap, 
                                                        handleGeoloacteError, 
                                                        options);
    } else {
        alert("Sorry, your browser doesn't seem to support geolocation :-(");
    }
}

function drawLocationOnMap(position) {
    var lat = position.coords.latitude;
    var lon = position.coords.longitude;
    var msg = "You are here: Latitude "+lat+", Longitude "+lon;
    
    var pos = new google.maps.LatLng(lat, lon);
    map.setCenter(pos);

    var infoBox = new google.maps.InfoWindow({map: map, position:pos, 
                                              content: msg});
    return;
}

function handleGeoloacteError() {
    alert("Sorry, couldn't get your geolocation :-(");
}

window.onload = init;