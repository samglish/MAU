<?php
session_start();
include("../config/db.php");

// Sécurité admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: ../dashboard.php");
    exit();
}

// ==================== CHANGEMENT ROLE ====================
if(isset($_GET['change_role'])){
    $id = intval($_GET['change_role']);
    $user = $conn->query("SELECT role FROM users WHERE id=$id")->fetch_assoc();
    
    if($user){
        $newRole = ($user['role'] == "admin") ? "user" : "admin";
        $conn->query("UPDATE users SET role='$newRole' WHERE id=$id");
    }
    header("Location: dashboard.php");
    exit();
}

// ==================== VALIDATION COMMANDES ====================
if(isset($_GET['validate_commande'])){
    $id = intval($_GET['validate_commande']);
    $conn->query("UPDATE commandes SET statut='Validée' WHERE id=$id");
    header("Location: dashboard.php");
    exit();
}

if(isset($_GET['reject_commande'])){
    $id = intval($_GET['reject_commande']);
    $conn->query("UPDATE commandes SET statut='Rejetée' WHERE id=$id");
    header("Location: dashboard.php");
    exit();
}

// ==================== VALIDATION DOSSIERS ====================
if(isset($_GET['validate_dossier'])){
    $id = intval($_GET['validate_dossier']);
    $conn->query("UPDATE demandes_dossier SET statut='Validé' WHERE id=$id");
    header("Location: dashboard.php");
    exit();
}

if(isset($_GET['reject_dossier'])){
    $id = intval($_GET['reject_dossier']);
    $conn->query("UPDATE demandes_dossier SET statut='Rejeté' WHERE id=$id");
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>

body {
    background: linear-gradient(135deg,#1d1f21,#343a40);
    font-family: 'Segoe UI';
    color:#fff;
}

.container {
    margin-top:40px;
}

.card-box {
    background:#2c3034;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,0.4);
    text-align:center;
    transition:0.3s;
}

.card-box:hover {
    transform:translateY(-5px);
}

.card-box h3 {
    font-size:30px;
    font-weight:bold;
}

.section-title {
    margin-top:50px;
    border-left:5px solid #dc3545;
    padding-left:10px;
    text-transform:uppercase;
    font-weight:bold;
}

.table {
    background:#fff;
    color:#000;
    border-radius:10px;
    overflow:hidden;
}

.table thead {
    background:#212529;
    color:#fff;
}

.btn-custom {
    padding:5px 10px;
    border-radius:20px;
    font-size:13px;
    transition:0.3s;
}

.btn-role-admin {
    background:#198754;
    color:#fff;
}

.btn-role-user {
    background:#0d6efd;
    color:#fff;
}

.btn-role-admin:hover,
.btn-role-user:hover {
    opacity:0.8;
}
.btn-validate {
    background:#198754;
    color:#fff;
}

.btn-reject {
    background:#dc3545;
    color:#fff;
}

.btn-validate:hover,
.btn-reject:hover {
    opacity:0.8;
}

.status-valid { color:green; font-weight:bold; }
.status-reject { color:red; font-weight:bold; }
.status-wait { color:orange; font-weight:bold; }
</style>
</head>

<body>

<div class="container">

<h1 class="text-center mb-4">Admin Dashboard</h1>

<?php
// Stats
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$totalCmd = $conn->query("SELECT COUNT(*) as total FROM commandes")->fetch_assoc()['total'];
$totalDos = $conn->query("SELECT COUNT(*) as total FROM demandes_dossier")->fetch_assoc()['total'];
?>

<div class="row text-center">
<div class="col-md-4">
    <div class="card-box">
        <h3><?= $totalUsers ?></h3>
        <p>Utilisateurs</p>
    </div>
</div>

<div class="col-md-4">
    <div class="card-box">
        <h3><?= $totalCmd ?></h3>
        <p>Commandes</p>
    </div>
</div>

<div class="col-md-4">
    <div class="card-box">
        <h3><?= $totalDos ?></h3>
        <p>Dossiers</p>
    </div>
</div>
</div>

<!-- ================= UTILISATEURS ================= -->

<h2 class="section-title">Gestion des utilisateurs</h2>

<table class="table table-bordered mt-3">
<thead>
<tr>
<th>ID</th>
<th>Nom</th>
<th>Email</th>
<th>Role</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
while($u = $users->fetch_assoc()){
    
$btnClass = ($u['role']=="admin") ? "btn-role-admin" : "btn-role-user";
$btnText = ($u['role']=="admin") ? "Admin" : "User";

echo "
<tr>
<td>{$u['id']}</td>
<td>{$u['nom']}</td>
<td>{$u['email']}</td>
<td>{$u['role']}</td>
<td>
<a href='?change_role={$u['id']}' 
onclick='return confirm(\"Changer le rôle ?\")' 
class='btn btn-custom $btnClass'>
$btnText
</a>
</td>
</tr>";
}
?>

</tbody>
</table>
<h2 class="section-title">Gestion des Commandes</h2>

<table class="table table-bordered mt-3">
<thead>
<tr>
<th>ID</th>
<th>Utilisateur</th>
<th>Service</th>
<th>Description</th>
<th>Date</th>
<th>Statut</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php
$cmd = $conn->query("SELECT commandes.*, users.nom 
                     FROM commandes 
                     JOIN users ON commandes.user_id = users.id
                     ORDER BY commandes.id DESC");

while($c = $cmd->fetch_assoc()){

$status = $c['statut'] ?? "En attente";

$class="status-wait";
if($status=="Validée") $class="status-valid";
if($status=="Rejetée") $class="status-reject";

echo "
<tr>
<td>{$c['id']}</td>
<td>{$c['nom']}</td>
<td>{$c['service']}</td>
<td>{$c['description']}</td>
<td>{$c['created_at']}</td>
<td class='$class'>$status</td>
<td>
<a href='?validate_commande={$c['id']}' 
class='btn btn-sm btn-validate'
onclick='return confirm(\"Valider cette commande ?\")'>✔</a>

<a href='?reject_commande={$c['id']}' 
class='btn btn-sm btn-reject'
onclick='return confirm(\"Rejeter cette commande ?\")'>✖</a>
</td>
</tr>";
}
?>

</tbody>
</table>

<h2 class="section-title">Gestion des Dossiers</h2>

<table class="table table-bordered mt-3">
<thead>
<tr>
<th>ID</th>
<th>Utilisateur</th>
<th>Type</th>
<th>Fichier</th>
<th>Date</th>
<th>Statut</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php
$dos = $conn->query("SELECT demandes_dossier.*, users.nom 
                     FROM demandes_dossier 
                     JOIN users ON demandes_dossier.user_id = users.id
                     ORDER BY demandes_dossier.id DESC");

while($d = $dos->fetch_assoc()){

$status = $d['statut'] ?? "En attente";

$class="status-wait";
if($status=="Validé") $class="status-valid";
if($status=="Rejeté") $class="status-reject";

echo "
<tr>
<td>{$d['id']}</td>
<td>{$d['nom']}</td>
<td>{$d['type_dossier']}</td>
<td><a href='{$d['document']}' target='_blank'>Voir</a></td>
<td>{$d['created_at']}</td>
<td class='$class'>$status</td>
<td>
<a href='?validate_dossier={$d['id']}' 
class='btn btn-sm btn-validate'
onclick='return confirm(\"Valider ce dossier ?\")'>✔</a>

<a href='?reject_dossier={$d['id']}' 
class='btn btn-sm btn-reject'
onclick='return confirm(\"Rejeter ce dossier ?\")'>✖</a>
</td>
</tr>";
}
?>

</tbody>
</table>
</div>

</body>
</html>