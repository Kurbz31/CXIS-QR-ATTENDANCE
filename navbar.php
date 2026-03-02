<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* Navbar Container */
.navbar {
    background-color: #0d6efd;
    overflow: hidden;
    font-family: Arial, sans-serif;
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end; /* Push links to right */
    align-items: center;
    padding: 0 20px;
}

/* Navbar links */
.navbar a {
    color: white;
    text-decoration: none;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    font-size: 16px;
}

.navbar a i {
    margin-right: 8px;
}

/* Hover and active */
.navbar a:hover {
    background-color: #0b5ed7;
}
.navbar a.active {
    background-color: #ffffff;
    color: #0d6efd;
    border-radius: 5px;
}

/* Hamburger */
.navbar .icon {
    display: none;
    font-size: 24px;
    cursor: pointer;
    color: white;
}

/* Responsive */
@media screen and (max-width: 768px) {
    .navbar a { display: none; width: 100%; text-align: left; padding-left: 20px; }
    .navbar a.icon { display: block; margin-left:auto; }
    .navbar.responsive { position: relative; }
    .navbar.responsive a { display: block; }
}
</style>

<div class="navbar" id="myNavbar">
    <a href="scanner.php" class="<?= $current_page == 'scanner.php' ? 'active' : '' ?>"><i class="fas fa-qrcode"></i>Scanner</a>
    <a href="add_employee.php" class="<?= $current_page == 'add_employee.php' ? 'active' : '' ?>"><i class="fas fa-user-plus"></i>Add Employee</a>
    <a href="view_employees.php" class="<?= $current_page == 'view_employees.php' ? 'active' : '' ?>"><i class="fas fa-users"></i>Employees</a>
    <a href="export_excel.php" class="<?= $current_page == 'export_excel.php' ? 'active' : '' ?>"><i class="fas fa-file-csv"></i>Export</a>
    <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
    <a href="javascript:void(0);" class="icon" onclick="toggleNavbar()"><i class="fas fa-bars"></i></a>
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