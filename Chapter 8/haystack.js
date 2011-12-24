var mapContainer = document.getElementById('map');
var map;

function init() {
    var mapOptions = {zoom: 15,
                      disableDefaultUI: true, 
                      mapTypeId: google.maps.MapTypeId.ROADMAP};
    map = new google.maps.Map(mapContainer, mapOptions);
    detectLocation();
}

function detectLocation(){
    var options = { enableHighAccuracy: true, maximumAge: 1000, timeout: 30000};
    //check if the browser supports geolocation
    if (window.navigator.geolocation) {
        window.navigator.geolocation.getCurrentPosition(markMyLocation, 
                                                        handleGeoloacteError, 
                                                        options);
    } else {
        alert("Sorry, your browser doesn't seem to support geolocation :-(");
    }
}

function markMyLocation(position) {
    var lat = position.coords.latitude;
    var lon = position.coords.longitude;
    var msg = "You are here";
    
    var pos = new google.maps.LatLng(lat, lon);
    map.setCenter(pos);
    
    var infoBox = new google.maps.InfoWindow({map: map, position:pos, 
                                              content: msg});
    var myMarker = new google.maps.Marker({map: map, position: pos});
    
    getNearByRestaurants(lat, lon);
    return;
}

function handleGeoloacteError() {
    alert("Sorry, couldn't get your geolocation :-(");
}

function getNearByRestaurants(lat, lon) {
    
    $.ajax({
         url      : 'haystack.php?lat='+lat+'&lon='+lon
        ,dataType : 'json'
        ,success  : ajaxSuccess 
    });
}

function ajaxSuccess(data){
    data.forEach(function(restaurant){
        var pos = new google.maps.LatLng(restaurant.latitude,
                                         restaurant.longitude);
        var marker = new google.maps.Marker({map: map, position: pos, 
                                             title: restaurant.name});
        
        var infoBox = new google.maps.InfoWindow({map: map, position: pos, content: restaurant.name});
    });
}

window.onload = init;