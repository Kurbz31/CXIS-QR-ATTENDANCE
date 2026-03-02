<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

.navbar {
    background-color: #1e2a38;
    overflow: hidden;
    padding: 0 20px;
}

.navbar a {
    float: left;
    display: block;
    color: #fff;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 15px;
}

.navbar a:hover {
    background-color: #34495e;
}

.navbar .active {
    background-color: #00a8ff;
    color: white;
}

.content {
    padding: 20px;
}
</style>

<div class="navbar">
    <a href="scanner.php" class="<?= $current_page == 'scanner.php' ? 'active' : '' ?>">Scanner</a>
    <a href="add_employee.php" class="<?= $current_page == 'add_employee.php' ? 'active' : '' ?>">Add Employee</a>
    <a href="view_employees.php" class="<?= $current_page == 'view_employees.php' ? 'active' : '' ?>">Employees</a>
    <a href="export_excel.php" class="<?= $current_page == 'export_excel.php' ? 'active' : '' ?>">Export</a>
    <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Dashboard</a>
</div>

<div class="content">