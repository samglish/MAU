<?php
include("../config/db.php");
include("../includes/auth_guard.php");
include("../includes/header.php");
?>

<h2 class="mb-4">📍 MAU Departments Map</h2>

<!-- Barre de recherche et filtre -->
<form method="GET" class="mb-3 row g-2">
    <div class="col-md-6">
        <input type="text" name="search" placeholder="Search department..." 
               class="form-control" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    </div>
    <div class="col-md-4">
        <select name="faculty" class="form-select">
            <option value="">All Faculties</option>
            <?php
            $faculties_result = $conn->query("SELECT DISTINCT faculte FROM departements");
            while($f = $faculties_result->fetch_assoc()){
                $selected = (isset($_GET['faculty']) && $_GET['faculty']==$f['faculte']) ? "selected" : "";
                echo "<option value='".$f['faculte']."' $selected>".$f['faculte']."</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100">Filter</button>
    </div>
</form>

<?php
$search = isset($_GET['search']) ? $_GET['search'] : '';
$faculty = isset($_GET['faculty']) ? $_GET['faculty'] : '';

$query = "SELECT * FROM departements WHERE 1";
$params = [];
$types = "";

if($search != ""){
    $query .= " AND nom LIKE ? ";
    $params[] = "%$search%";
    $types .= "s";
}
if($faculty != ""){
    $query .= " AND faculte = ? ";
    $params[] = $faculty;
    $types .= "s";
}

$stmt = $conn->prepare($query);
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$departements = [];
while($row = $result->fetch_assoc()){
    $departements[] = $row;
}
?>

<!-- Carte Leaflet -->
<div id="map" style="width:100%; height:600px;" class="mb-4"></div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Marker Cluster -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css"/>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<!-- Leaflet Routing Machine -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

<script>
var map = L.map('map').setView([9.2665, 12.4545], 16);

// OSM Layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    attribution:'&copy; OpenStreetMap contributors'
}).addTo(map);

// Cluster
var markers = L.markerClusterGroup();

// Départements
var departements = <?php echo json_encode($departements); ?>;

// Fonction pour créer itinéraire depuis la position de l’utilisateur
function createRoute(lat, lng){
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(function(pos){
            var userLat = pos.coords.latitude;
            var userLng = pos.coords.longitude;

            if(window.routingControl) map.removeControl(window.routingControl);

            window.routingControl = L.Routing.control({
                waypoints: [
                    L.latLng(userLat, userLng),
                    L.latLng(lat, lng)
                ],
                routeWhileDragging: true,
                showAlternatives: true
            }).addTo(map);
        });
    } else {
        alert("La géolocalisation n'est pas supportée par votre navigateur.");
    }
}

// Ajout des marqueurs
departements.forEach(function(dep){
    var icon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/1673/1673221.png',
        iconSize: [30,30],
        iconAnchor: [15,30],
        popupAnchor: [0,-30]
    });

    var marker = L.marker([parseFloat(dep.latitude), parseFloat(dep.longitude)], {icon: icon});
    marker.bindPopup("<b>"+dep.nom+"</b><br><strong>Faculty:</strong> "+dep.faculte+"<br>"+dep.description+
                     "<br><button onclick='createRoute("+dep.latitude+","+dep.longitude+")' class='btn btn-sm btn-primary mt-2'>Voir itinéraire</button>");
    markers.addLayer(marker);
});

map.addLayer(markers);

// Zoom auto
if(departements.length > 0){
    var group = new L.featureGroup(markers.getLayers());
    map.fitBounds(group.getBounds().pad(0.2));
}
</script>

<?php include("../includes/footer.php"); ?>