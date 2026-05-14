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

.navbar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar a.nav-link {
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

.navbar a.nav-link i {
    margin-right: 8px;
}

/* Hover and active */
.navbar a.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}
.navbar a.nav-link.active {
    background-color: white;
    color: #0d6efd;
    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

/* Hamburger for mobile */
.navbar .icon {
    display: none;
    font-size: 28px;
    cursor: pointer;
    color: white;
    text-decoration: none;
    padding: 10px;
}

/* Responsive */
@media screen and (max-width: 900px) {
    .navbar {
        display: block;
        padding: 10px 20px;
    }
    .navbar-header {
        display: flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
    }
    .navbar .logo img {
        height: 60px;
    }
    .navbar .icon {
        display: block;
    }
    .navbar .nav-links {
        display: none;
        flex-direction: column;
        width: 100%;
        margin-top: 10px;
    }
    .navbar.responsive .nav-links {
        display: flex;
    }
    .navbar .nav-links a.nav-link {
        width: 100%;
        box-sizing: border-box;
        margin: 4px 0;
        justify-content: flex-start;
    }
}

/* Animation for dropdown */
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(-10px);}
    to {opacity: 1; transform: translateY(0);}
}
</style>

<div class="navbar" id="myNavbar">
    <div class="navbar-header">
        <!-- Logo on the left -->
        <div class="logo">
            <a href="index.php"><img src="img/cx_logo.png" alt="CXIS Logo"></a>
        </div>
        
        <!-- Hamburger icon for mobile -->
        <a href="javascript:void(0);" class="icon" onclick="toggleNavbar()">
            <i class="fas fa-bars"></i>
        </a>
    </div>

    <!-- Navbar links on the right -->
    <div class="nav-links">
        <a href="scanner.php" class="nav-link <?= $current_page == 'scanner.php' ? 'active' : '' ?>">
            <i class="fas fa-qrcode"></i>Scanner
        </a>
        
        <a href="view_employees.php" class="nav-link <?= $current_page == 'view_employees.php' ? 'active' : '' ?>">
            <i class="fas fa-users"></i>Employees
        </a>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <a href="hr_dashboard.php" class="nav-link <?= $current_page == 'hr_dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-clipboard-list"></i>HR Dashboard
        </a>
            <a href="add_employee.php" class="nav-link <?= $current_page == 'add_employee.php' ? 'active' : '' ?>">
            <i class="fas fa-user-plus"></i>Add Employee
        </a>
        <?php endif; ?>

        <a href="index.php" class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i>Dashboard
        </a>
        <a href="logout.php" class="nav-link btn-logout">
            <i class="fas fa-right-from-bracket"></i> Logout
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