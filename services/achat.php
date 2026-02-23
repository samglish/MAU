<?php
include("../includes/header.php");
include("../config/db.php"); 
include("../includes/auth_guard.php");

// Form submission
if(isset($_POST['submit'])){
    $service = trim($_POST['service']);
    $desc = trim($_POST['description']);

    if(empty($service)){
        echo "<div class='alert alert-danger'>Veuillez entrer le nom du service.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO commandes(user_id, service, description) VALUES(?,?,?)");
        $stmt->bind_param("iss", $_SESSION['user_id'], $service, $desc);
        $stmt->execute();

        echo "<div class='alert alert-success'>Commande envoyée avec succès !</div>";
    }
}
?>

<!-- Styles CSS -->
<style>
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Titres décorés */
h2.decorated, h3.decorated {
    color: #0d6efd;
    border-left: 6px solid #0d6efd;
    padding-left: 12px;
    margin-bottom: 20px;
    font-weight: 600;
    text-transform: uppercase;
}

/* Formulaire */
form {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

input, textarea {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 10px;
}

button.btn-success {
    background-color: #0d6efd;
    border: none;
    transition: all 0.3s;
    padding: 10px 20px;
}
button.btn-success:hover {
    background-color: #157347;
    transform: translateY(-2px);
}

/* Tableau */
.table {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
}
.table th {
    background-color: #0d6efd;
    color: #fff;
    font-weight: 600;
}
.table td, .table th {
    padding: 12px 15px;
    text-align: left;
}
.table tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Alertes */
.alert {
    border-radius: 6px;
}
</style>

<h2 class="decorated">Demande de service / achat</h2>
<p>Remplissez le formulaire ci-dessous pour soumettre votre commande.</p>

<form method="POST">
    <div class="mb-3">
        <label for="service" class="form-label">Nom du service / produit</label>
        <input type="text" id="service" name="service" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description / Détails</label>
        <textarea id="description" name="description" class="form-control" rows="4" maxlength="500"
                  placeholder="Ajoutez plus de détails ici..."></textarea>
    </div>

    <button type="submit" name="submit" class="btn btn-success mb-4">Soumettre la commande</button>
</form>

<h3 class="decorated">Mes commandes</h3>

<?php
$user_id = $_SESSION['user_id'];
$res = $conn->prepare("SELECT * FROM commandes WHERE user_id = ? ORDER BY created_at DESC");
$res->bind_param("i", $user_id);
$res->execute();
$result = $res->get_result();

if($result->num_rows > 0){
    echo "<table class='table table-bordered table-striped'>";
    echo "<thead><tr>
            <th>ID</th>
            <th>Service</th>
            <th>Description</th>
            <th>Date</th>
            <th>Statut</th>
          </tr></thead><tbody>";
    while($row = $result->fetch_assoc()){
        $statut = isset($row['statut']) ? $row['statut'] : "En attente";
        $date = isset($row['created_at']) ? $row['created_at'] : "";
        echo "<tr>
                <td>".$row['id']."</td>
                <td>".$row['service']."</td>
                <td>".$row['description']."</td>
                <td>".$date."</td>
                <td>".$statut."</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-info'>Vous n'avez pas encore de commandes.</div>";
}
?>

<?php include("../includes/footer.php"); ?>