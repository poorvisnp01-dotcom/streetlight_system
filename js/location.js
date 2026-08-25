let map;
let marker;

function initMap(lat, lng){

map = L.map('map').setView([lat,lng],17);

L.tileLayer(
'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
{
maxZoom:19,
attribution:'© OpenStreetMap'
}
).addTo(map);

marker=L.marker(
[lat,lng],
{
draggable:true
}
).addTo(map);

document.getElementById("latitude").value=lat;

document.getElementById("longitude").value=lng;

getAddress(lat,lng);

marker.on("dragend",function(e){

const pos=marker.getLatLng();

document.getElementById("latitude").value=pos.lat;

document.getElementById("longitude").value=pos.lng;

getAddress(pos.lat,pos.lng);

});

}

function getAddress(lat,lng){

fetch(

`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`

)

.then(response=>response.json())

.then(data=>{

document.getElementById("address").value=data.display_name;

});

}

if(navigator.geolocation){

navigator.geolocation.getCurrentPosition(

function(position){

initMap(

position.coords.latitude,

position.coords.longitude

);

},

function(){

alert("Unable to fetch your location.");

}

);

}