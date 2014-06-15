<html>
<head>

<script type="text/javascript" src="jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?AIzaSyBFW7iJp8UzJZN3YWZtFnrkb1ywFxE-4y4&sensor=false"></script>
<script type="text/javascript">
var isWithinPolygon;
var poly, map;
var markers = [];
var path = new google.maps.MVCArray;
var uluru;
var geofence;
var LatLngPHP;
window.onload = setupRefresh;

function setupRefresh() {
setTimeout("refreshPage();", 150000); // milliseconds
}
function refreshPage() {
window.location = location.href;
}
function initialize() {


<?php
$YourFile = "YourFile.txt";
$handle = fopen($YourFile, 'r');
$LatLng = fread($handle, 24);
fclose($handle);

file_put_contents("data.txt", "");

$LatLngPHP = $LatLng;
?>
uluru = new google.maps.LatLng(<?php echo $LatLngPHP ?>);
   map = new google.maps.Map(document.getElementById("map"), {
zoom: 17,
center: uluru,
mapTypeId: google.maps.MapTypeId.SATELLITE
   });
var marker = new google.maps.Marker({
position: uluru,
map: map,
title:  'Location details'
});


   poly = new google.maps.Polygon({
strokeWeight: 3,
fillColor: '#5555FF'
   });
   poly.setMap(map);
   poly.setPaths(new google.maps.MVCArray([path]));
geofence=new String("hello");

   google.maps.event.addListener(map, 'click', addPoint);
}

function addPoint(event) {
   path.insertAt(path.length, event.latLng);

   var marker = new google.maps.Marker({
position: event.latLng,
map: map,
draggable: true
   });

   markers.push(marker);
   marker.setTitle("#" + event.latLng);
//alert(event.latLng);

if(google.maps.geometry.poly.containsLocation(uluru, poly) == true) {
alert("yes");
$.ajax({
type: "GET",
url: "ajax.php",
data: "&value="+event.latLng+'&status=1',
success: function(html){
}
});

var page_content;
$.get( "www.i2iedu.com/index6.php?value=1", function(data){

 page_content = data;
});
}
else
{
alert("no");
$.ajax({
type: "GET",
url: "ajax.php",
data: "&value="+event.latLng+'&status=0',
success: function(html){
}
});
var page_content2;
$.get( "www.i2iedu.com/index6.php?value=0", function(data){

 page_content2 = data;
});
}

   google.maps.event.addListener(marker, 'click', function() {
marker.setMap(null);
for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
markers.splice(i, 1);
path.removeAt(i);
}
);


google.maps.event.addListener(marker, 'dragend', function() {
for (var i = 0, I = markers.length; i < I && markers[i] != marker; ++i);
path.setAt(i, marker.getPosition());
}
);
}
</script>
</head>
<body style="margin:0px; padding:0px;" onLoad="initialize()">
<p>Instructions:
<ul>
<li>copy right 2013 Everest Infocom Pvt Ltd.</li>
<li>Team: 1. Abhinandan, Ankit Jain, Nicku Nayak (PESIT)</li>
<li>Click on the map to insert a vertex.</li>
<li>Click on a vertex to remove it.</li>
<li>Drag a vertex to move it.</li>
 </ul>
 </p>
 <div id="map" style="width: 480; height: 480;"></div>
</body>
</html>
