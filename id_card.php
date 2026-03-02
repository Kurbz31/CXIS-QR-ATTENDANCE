<?php
include 'db.php';
include 'navbar.php';

// Check if 'id' exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<p style='color:red;'>Error: Employee ID not provided. Use a URL like id_card.php?id=1</p>");
}

$id = (int) $_GET['id'];

// Query employee
$result = $conn->query("SELECT * FROM employees WHERE id=$id");

// Check if employee exists
if (!$result || $result->num_rows == 0) {
    die("<p style='color:red;'>Error: Employee not found for ID $id</p>");
}

$row = $result->fetch_assoc();
?>

<div style="width:300px; border:1px solid #000; padding:10px; text-align:center; margin-top:20px;">
    <h3>CXIS Company</h3>
    <img src="<?php echo $row['qr_path']; ?>" width="150"><br>
    <b><?php echo $row['fullname']; ?></b><br>
    <?php echo $row['department']; ?><br>
    Code: <?php echo $row['employee_code']; ?>
</div>

<button onclick="window.print()">Print</button>

</div> <!-- close content -->