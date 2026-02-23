<?php
include("config/db.php");
include("includes/auth_guard.php");
include("includes/header.php");
?>

<!-- Styles CSS -->
<style>
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Titre de bienvenue décoré */
h2.decorated {
    color: #0d6efd;
    border-left: 6px solid #0d6efd;
    padding-left: 12px;
    margin-bottom: 25px;
    font-weight: 600;
    text-transform: uppercase;
}

/* Cartes du dashboard */
.card {
    border-radius: 10px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.card-title {
    font-weight: 600;
    color: #0d6efd;
}
.card-text {
    color: #495057;
    margin-bottom: 15px;
}
.btn-primary {
    background-color: #0d6efd;
    border: none;
    transition: background 0.3s;
}
.btn-primary:hover {
    background-color: #0b5ed7;
}
.btn-success {
    background-color: #198754;
    border: none;
}
.btn-success:hover {
    background-color: #157347;
}
.btn-warning {
    background-color: #ffc107;
    border: none;
    color: #212529;
}
.btn-warning:hover {
    background-color: #e0a800;
}
.btn-danger {
    background-color: #dc3545;
    border: none;
}
.btn-danger:hover {
    background-color: #bb2d3b;
}
</style>

<h2 class="decorated">Welcome <?php echo $_SESSION['nom']; ?></h2>

<div class="row">

    <!-- Submit File -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Submit File</h5>
                <p class="card-text">Send your academic documents easily.</p>
                <a href="services/depot.php" class="btn btn-primary">Go</a>
            </div>
        </div>
    </div>

    <!-- Purchase -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Purchase</h5>
                <p class="card-text">Request campus services and shopping.</p>
                <a href="services/achat.php" class="btn btn-success">Go</a>
            </div>
        </div>
    </div>

    <!-- Campus Map -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">Campus Map</h5>
                <p class="card-text">Locate departments and offices.</p>
                <a href="services/localisation.php" class="btn btn-warning">Go</a>
            </div>
        </div>
    </div>

    <!-- Admin Panel -->
    <?php if($_SESSION['role']=="admin"){ ?>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-danger">
            <div class="card-body text-center">
                <h5 class="card-title text-danger">Admin Panel</h5>
                <p class="card-text">Manage users and requests.</p>
                <a href="admin/dashboard.php" class="btn btn-danger">Go</a>
            </div>
        </div>
    </div>
    <?php } ?>

</div>

<?php include("includes/footer.php"); ?>