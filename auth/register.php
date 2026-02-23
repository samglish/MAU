<?php
session_start();
include("../config/db.php");
include("includes/header.php");

$message = "";

if(isset($_POST['register'])){

    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if(empty($nom) || empty($email) || empty($password) || empty($confirm)){
        $message = "<div class='alert alert-danger'>Veuillez remplir tous les champs.</div>";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = "<div class='alert alert-danger'>Email invalide.</div>";
    }
    elseif($password !== $confirm){
        $message = "<div class='alert alert-danger'>Les mots de passe ne correspondent pas.</div>";
    }
    else{

        // Vérifier si email existe déjà
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0){
            $message = "<div class='alert alert-danger'>Cet email est déjà utilisé.</div>";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users(nom,email,password,role) VALUES(?,?,?,?)");
            $role = "user";
            $stmt->bind_param("ssss", $nom, $email, $hashed_password, $role);
            $stmt->execute();

            $message = "<div class='alert alert-success'>Inscription réussie. Vous pouvez vous connecter.</div>";
            header("Refresh:2; url=login.php");
        }
    }
}
?>

<!-- Styles CSS -->
<style>
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.register-container {
    max-width: 450px;
    margin: 60px auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

h2.decorated {
    color: #0d6efd;
    border-left: 6px solid #0d6efd;
    padding-left: 12px;
    margin-bottom: 25px;
    font-weight: 600;
    text-transform: uppercase;
}

input {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ced4da;
    margin-bottom: 15px;
}

button {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: none;
    background-color: #0d6efd;
    color: #fff;
    font-weight: 600;
    transition: 0.3s;
}

button:hover {
    background-color: #157347;
    transform: translateY(-2px);
}

.alert {
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 15px;
}
.switch-link {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
}

.switch-link a {
    color: #0d6efd;
    text-decoration: none;
    font-weight: 600;
}

.switch-link a:hover {
    text-decoration: underline;
}
</style>

<div class="register-container">
    <h2 class="decorated">Créer un compte</h2>

    <?php echo $message; ?>

    <form method="POST">
        <input type="text" name="nom" placeholder="Nom complet" required>
        <input type="email" name="email" placeholder="Adresse email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="password" name="confirm_password" placeholder="Confirmer mot de passe" required>
        <button type="submit" name="register">S'inscrire</button>
    </form>
</div>
<p class="switch-link">
    Déjà un compte ?
    <a href="login.php">Se connecter</a>
</p>
<?php include("includes/footer.php"); ?>