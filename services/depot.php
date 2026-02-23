<?php
include("../includes/header.php");
include("../config/db.php"); 
include("../includes/auth_guard.php");

// Form submission
if(isset($_POST['submit'])){
    $type = trim($_POST['type_dossier']);

    if(isset($_FILES['document']) && $_FILES['document']['error'] === 0){

        $ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['pdf','zip'];

        if(!in_array($ext, $allowed_ext)){
            echo "<div class='alert alert-danger'>Erreur: Seuls PDF et ZIP sont autorisés.</div>";
        } else {
            $uploads_dir = "../uploads/";
            if(!is_dir($uploads_dir)) mkdir($uploads_dir, 0775, true);

            $file_name = time() . "_" . basename($_FILES['document']['name']);
            $path = $uploads_dir . $file_name;

            if(move_uploaded_file($_FILES['document']['tmp_name'], $path)){
                $stmt = $conn->prepare("INSERT INTO demandes_dossier(user_id, type_dossier, document) VALUES(?,?,?)");
                $stmt->bind_param("iss", $_SESSION['user_id'], $type, $path);
                $stmt->execute();

                echo "<div class='alert alert-success'>Dossier soumis avec succès !</div>";
            } else {
                echo "<div class='alert alert-danger'>Erreur lors de l'upload du fichier. Vérifie les droits du dossier uploads.</div>";
            }
        }

    } else {
        echo "<div class='alert alert-danger'>Veuillez sélectionner un fichier.</div>";
    }
}
?>

<!-- Styles CSS -->
<style>
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
h2.decorated, h3.decorated {
    color: #0d6efd;
    border-left: 6px solid #0d6efd;
    padding-left: 12px;
    margin-bottom: 25px;
    font-weight: 600;
    text-transform: uppercase;
}
form {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}
input[type="text"], input[type="file"] {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 10px;
    width: 100%;
}
button.btn-primary {
    background-color: #0d6efd;
    border: none;
    padding: 10px 20px;
    transition: all 0.3s;
}
button.btn-primary:hover {
    background-color: #0b5ed7;
    transform: translateY(-2px);
}
.alert {
    border-radius: 6px;
}
.table {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}
.table th {
    background-color: #0d6efd;
    color: #fff;
    padding: 12px 15px;
    text-align: left;
}
.table td {
    padding: 12px 15px;
}
</style>

<h2 class="decorated">Soumettre votre dossier</h2>
<p>Veuillez scanner et regrouper tous les documents suivants en un seul fichier PDF ou ZIP :</p>
<ul>
    <li>Bac / GCE</li>
    <li>Relevés niveau 1, 2, 3</li>
    <li>Diplôme de Licence</li>
    <li>Relevés Master 1 et Master 2</li>
    <li>Diplôme de Master</li>
    <li>Photo 4x4</li>
</ul>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Type de dossier</label>
        <input type="text" name="type_dossier" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Fichier (PDF ou ZIP)</label>
        <input type="file" name="document" class="form-control" required>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Soumettre</button>
</form>

<h3 class="decorated">Mes dossiers soumis</h3>

<?php
$user_id = $_SESSION['user_id'];
$res = $conn->prepare("SELECT * FROM demandes_dossier WHERE user_id = ? ORDER BY created_at DESC");
$res->bind_param("i", $user_id);
$res->execute();
$result = $res->get_result();

if($result->num_rows > 0){
    echo "<table class='table table-bordered table-striped'>";
    echo "<thead><tr>
            <th>ID</th>
            <th>Type de dossier</th>
            <th>Fichier</th>
            <th>Date</th>
            <th>Statut</th>
          </tr></thead><tbody>";
    while($row = $result->fetch_assoc()){
        $statut = isset($row['statut']) ? $row['statut'] : "En attente";
        $date = isset($row['created_at']) ? $row['created_at'] : "";
        $file_link = "<a href='".$row['document']."' target='_blank'>Voir</a>";
        echo "<tr>
                <td>".$row['id']."</td>
                <td>".$row['type_dossier']."</td>
                <td>".$file_link."</td>
                <td>".$date."</td>
                <td>".$statut."</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-info'>Vous n'avez encore soumis aucun dossier.</div>";
}
?>

<?php include("../includes/footer.php"); ?>