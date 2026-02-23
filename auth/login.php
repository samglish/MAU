<?php
session_start();
include("../config/db.php");
include("includes/header.php");

$message = "";

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($email) || empty($password)){
        $message = "<div class='alert alert-danger'>Veuillez remplir tous les champs.</div>";
    } else {

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 1){
            $user = $result->fetch_assoc();

            if(password_verify($password, $user['password'])){

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['role'] = $user['role'];

                header("Location: ../dashboard.php");
                exit();

            } else {
                $message = "<div class='alert alert-danger'>Mot de passe incorrect.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Email introuvable.</div>";
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

.login-container {
    max-width: 400px;
    margin: 80px auto;
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
    background-color: #0b5ed7;
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

<div class="login-container">
    <h2 class="decorated">Connexion</h2>

    <?php echo $message; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Adresse email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" name="login">Se connecter</button>
    </form>
</div>
<p class="switch-link">
    Pas encore de compte ?
    <a href="register.php">S'inscrire</a>
</p>
<?php include("includes/footer.php"); ?>