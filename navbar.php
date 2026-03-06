<?php
if (!isset($_SESSION)) session_start();

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* Modern Gradient Navbar with Logo */
.navbar {
    background: linear-gradient(90deg, #0d6efd, #0056b3);
    font-family: Arial, sans-serif;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between; /* Logo left, links right */
    align-items: center;
    padding: 0 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    position: relative;
    z-index: 1000;
    border-radius: 0 0 10px 10px;
}

/* Logo */
.navbar .logo img {
    height: 80px;
    
    cursor: pointer;
}

/* Navbar links */
.navbar .nav-links {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}

.navbar a {
    color: white;
    text-decoration: none;
    padding: 12px 18px;
    margin: 6px 4px;
    display: flex;
    align-items: center;
    font-size: 16px;
    border-radius: 8px;
    transition: 0.3s all;
    font-weight: 500;
}

.navbar a i {
    margin-right: 8px;
}

/* Hover and active */
.navbar a:hover {
    background-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}
.navbar a.active {
    background-color: white;
    color: #0d6efd;
    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

/* Hamburger for mobile */
.navbar .icon {
    display: none;
    font-size: 26px;
    cursor: pointer;
    color: white;
}

/* Responsive */
@media screen and (max-width: 768px) {
    .navbar .nav-links a { display: none; width: 100%; text-align: left; padding-left: 20px; margin: 0; }
    .navbar .nav-links a.icon { display: block; margin-left:auto; }
    .navbar.responsive .nav-links { width: 100%; flex-direction: column; }
    .navbar.responsive .nav-links a { display: block; animation: fadeIn 0.4s; }
}

/* Animation for dropdown */
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-10px);}
    to {opacity: 1; transform: translateY(0);}
}
</style>

<div class="navbar" id="myNavbar">
    <!-- Logo on the left -->
    <div class="logo">
        <a href="index.php"><img src="img/cx_logo.png" alt="CXIS Logo"></a>
    </div>

    <!-- Navbar links on the right -->
    <div class="nav-links">
        <a href="scanner.php" class="<?= $current_page == 'scanner.php' ? 'active' : '' ?>">
            <i class="fas fa-qrcode"></i>Scanner
        </a>
        
        <a href="view_employees.php" class="<?= $current_page == 'view_employees.php' ? 'active' : '' ?>">
            <i class="fas fa-users"></i>Employees
        </a>

        <?php if($_SESSION['role'] == 'admin'): ?>
            <a href="hr_dashboard.php" class="<?= $current_page == 'hr_dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-clipboard-list"></i>HR Dashboard
        </a>
            <a href="add_employee.php" class="<?= $current_page == 'add_employee.php' ? 'active' : '' ?>">
            <i class="fas fa-user-plus"></i>Add Employee
        </a>
        <?php endif; ?>

        </a>
        <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i>Dashboard
        </a>
        <a href="logout.php" class="btn-logout">
    <i class="fas fa-right-from-bracket"></i> Logout
</a>
        
        <!-- Hamburger icon for mobile -->
        <a href="javascript:void(0);" class="icon" onclick="toggleNavbar()">
            <i class="fas fa-bars"></i>
        </a>
    </div>
</div>

<script>
function toggleNavbar() {
    var x = document.getElementById("myNavbar");
    if (x.className === "navbar") {
        x.className += " responsive";
    } else {
        x.className = "navbar";
    }
}
</script>