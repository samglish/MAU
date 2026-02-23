<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>MAU Services</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">

        <!-- Nom App -->
        <a class="navbar-brand fw-bold" href="../dashboard.php">
            🎓 MAU Services
        </a>

        <div class="d-flex align-items-center text-white">

            <?php if(isset($_SESSION['nom'])){ ?>
                <span class="me-3">
                    👤 <?php echo $_SESSION['nom']; ?>
                </span>

                <a href="/www/mau_services_pro/auth/logout.php" 
                   class="btn btn-light btn-sm">
                   Déconnexion
                </a>
            <?php } ?>

        </div>

    </div>
</nav>

<div class="container mt-4">