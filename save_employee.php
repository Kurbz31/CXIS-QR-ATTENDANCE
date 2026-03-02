<?php
include 'db.php';
require_once __DIR__ . '/phpqrcode/qrlib.php';

// 1️⃣ Get POST data
$name = $_POST['fullname'];
$dept = $_POST['department'];
$emp_id = $_POST['emp_id'];

// 2️⃣ Handle profile picture upload
$profile_path = NULL;
if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
    $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);

    // Folder for uploads (relative to project root)
    $profile_folder = "uploads/";
    if(!file_exists($profile_folder)){
        mkdir($profile_folder, 0777, true);
    }

    // Unique file name
    $profile_file = $profile_folder . uniqid() . "." . $ext;

    // Move uploaded file
    move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_file);

    // Save relative path to DB
    $profile_path = $profile_file;
}

// 3️⃣ Insert employee (CXIS code empty for now)
$stmt = $conn->prepare("INSERT INTO employees (employee_code, emp_id, fullname, department, profile_pic) VALUES ('', ?, ?, ?, ?)");
$stmt->bind_param("ssss", $emp_id, $name, $dept, $profile_path);
$stmt->execute();

$last_id = $conn->insert_id;

// 4️⃣ Generate CXIS code
$employee_code = "CXIS-" . str_pad($last_id, 4, "0", STR_PAD_LEFT);

// Update employee_code in DB
$conn->query("UPDATE employees SET employee_code='$employee_code' WHERE id=$last_id");

// 5️⃣ Generate QR code
$folder = "qrcodes/";
if(!file_exists($folder)){
    mkdir($folder, 0777, true);
}

$qr_file = $folder . $employee_code . ".png";
QRcode::png($employee_code, $qr_file, QR_ECLEVEL_L, 8);

// Update QR path in DB
$conn->query("UPDATE employees SET qr_path='$qr_file' WHERE id=$last_id");

// 6️⃣ Success message and link to download ID
echo "✅ Employee Added Successfully!<br>";
echo "Name: $name<br>";
echo "Employee ID: $emp_id<br>";
echo "CXIS Code: $employee_code<br>";
echo "<a href='download_id.php?id=$last_id' target='_blank'>Download ID</a><br>";
echo "<a href='add_employee.php'>Add Another Employee</a>";
?>