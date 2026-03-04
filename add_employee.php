<?php
include 'db.php';
require_once __DIR__ . '/phpqrcode/qrlib.php';

$success_msg = '';
$error_msg = '';
// Initialize variables to keep form values
$fullname_val = '';
$emp_id_val = '';
$department_val = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['fullname'] ?? '';
    $dept = $_POST['department'] ?? '';
    $emp_id = $_POST['emp_id'] ?? '';

    // Save current values for re-populating the form
    $fullname_val = htmlspecialchars($name);
    $emp_id_val = htmlspecialchars($emp_id);
    $department_val = htmlspecialchars($dept);

    // Handle profile picture
    $profile_path = NULL;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $profile_folder = "uploads/";
        if(!file_exists($profile_folder)) mkdir($profile_folder, 0777, true);
        $profile_path = $profile_folder . uniqid() . "." . $ext;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_path);
    }

    // Insert employee
    $stmt = $conn->prepare("INSERT INTO employees (employee_code, emp_id, fullname, department, profile_pic) VALUES ('', ?, ?, ?, ?)");
    $stmt->bind_param("ssss", $emp_id, $name, $dept, $profile_path);

    if ($stmt->execute()) {
        $last_id = $conn->insert_id;
        // Generate CXIS code
        $employee_code = "CXIS-" . str_pad($last_id, 4, "0", STR_PAD_LEFT);
        $conn->query("UPDATE employees SET employee_code='$employee_code' WHERE id=$last_id");

        // Generate QR code
        $folder = "qrcodes/";
        if(!file_exists($folder)) mkdir($folder, 0777, true);
        $qr_file = $folder . $employee_code . ".png";
        QRcode::png($employee_code, $qr_file, QR_ECLEVEL_L, 8);
        $conn->query("UPDATE employees SET qr_path='$qr_file' WHERE id=$last_id");

        $success_msg = "✅ Employee <b>{$name}</b> added successfully! Employee Code: <b>{$employee_code}</b>";

        // Clear form values after successful submission
        $fullname_val = $emp_id_val = $department_val = '';
    } else {
        $error_msg = "⚠ Error adding employee: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Employee - CXIS</title>
<style>
body { font-family: Arial,sans-serif; margin:0; background:#e9f2ff; }
h2 { text-align:center; color:#0d6efd; margin-bottom:25px; }
section.form-container {
    max-width:600px; margin:40px auto; padding:20px;
    border:2px solid #0d6efd; border-radius:10px; background:#f0f8ff;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
label { font-weight:bold; color:#0d6efd; }
input[type="text"], input[type="file"] { padding:10px; border-radius:5px; border:1px solid #0d6efd; width:100%; box-sizing:border-box; }
button { padding:12px; border:none; border-radius:5px; background:#0d6efd; color:white; font-weight:bold; cursor:pointer; transition:0.3s; }
button:hover { background:#084298; }
form { display:flex; flex-direction:column; gap:15px; }
.message { padding:10px; border-radius:5px; margin-bottom:15px; text-align:center; font-weight:bold; }
.success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
@media (max-width:650px){ section.form-container{margin:20px; padding:15px;} }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="form-container">
    <h2>Add New Employee</h2>

    <!-- Success/Error Messages -->
    <?php if($success_msg) echo "<div class='message success'>{$success_msg}</div>"; ?>
    <?php if($error_msg) echo "<div class='message error'>{$error_msg}</div>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" placeholder="Enter full name" value="<?= $fullname_val ?>" required>

        <label for="emp_id">Employee ID Number:</label>
        <input type="text" id="emp_id" name="emp_id" placeholder="Enter employee ID" value="<?= $emp_id_val ?>" required>

        <label for="department">Department:</label>
        <input type="text" id="department" name="department" placeholder="Enter department" value="<?= $department_val ?>" required>

        <label for="profile_pic">Profile Picture:</label>
        <input type="file" id="profile_pic" name="profile_pic" accept="image/*">

        <button type="submit">Add Employee</button>
    </form>
</section>

</body>
</html>