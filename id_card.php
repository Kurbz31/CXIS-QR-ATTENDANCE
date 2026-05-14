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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - <?php echo $row['fullname']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #e9f2ff; margin: 0; }
        .id-card {
            width: 300px;
            max-width: 90%;
            border: 2px solid #0d6efd;
            border-radius: 10px;
            background: #fff;
            padding: 20px;
            text-align: center;
            margin: 40px auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        .id-card img { max-width: 100%; height: auto; }
    </style>
</head>
<body>

<div class="id-card">
    <h3 style="color:#0d6efd;">CXIS Company</h3>
    <img src="<?php echo $row['qr_path']; ?>" width="150"><br><br>
    <b style="font-size:18px;"><?php echo $row['fullname']; ?></b><br>
    <?php echo $row['department']; ?><br>
    <small>Code: <?php echo $row['employee_code']; ?></small>
</div>

</body>
</html>

<button onclick="window.print()">Print</button>

</div> <!-- close content -->