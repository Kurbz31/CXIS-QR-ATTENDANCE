<?php
include 'db.php';
include 'navbar.php';

// Handle delete request
if(isset($_GET['delete_id'])){
    $delete_id = (int)$_GET['delete_id'];
    
    // Delete profile pic and QR if exist
    $res = $conn->query("SELECT profile_pic, qr_path FROM employees WHERE id=$delete_id");
    if($res && $res->num_rows > 0){
        $row = $res->fetch_assoc();
        if(file_exists($row['profile_pic'])) unlink($row['profile_pic']);
        if(file_exists($row['qr_path'])) unlink($row['qr_path']);
    }
    
    $conn->query("DELETE FROM employees WHERE id=$delete_id");
    echo "<script>alert('Employee deleted successfully!'); window.location='view_employees.php';</script>";
}

$result = $conn->query("SELECT * FROM employees ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Employees - CXIS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9f2ff;
            margin: 0;
        }

        h2 {
            text-align: center;
            color: #0d6efd;
            margin: 30px 0 20px 0;
        }

        .table-container {
            max-width: 1200px;
            margin: 0 auto 40px auto;
            overflow-x: auto;
            background-color: #f0f8ff;
            border-radius: 10px;
            padding: 15px;
            border: 2px solid #0d6efd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #0d6efd;
            color: white;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #cce5ff;
        }

        .profile-pic {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .qr-thumb {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .btn {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            margin: 2px;
            display: inline-block;
        }

        .btn-download {
            background-color: #198754; /* green */
        }

        .btn-download:hover {
            background-color: #157347;
        }

        .btn-delete {
            background-color: #dc3545; /* red */
        }

        .btn-delete:hover {
            background-color: #a71d2a;
        }

        /* Responsive */
        @media screen and (max-width: 768px){
            thead { display: none; }
            table, tbody, tr, td { display: block; width: 100%; }
            tr { margin-bottom: 15px; border-bottom: 2px solid #0d6efd; }
            td { text-align: right; padding-left: 50%; position: relative; }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                text-align: left;
                font-weight: bold;
            }
        }
    </style>
</head>
<body>

<h2>All Employees</h2>
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Profile</th>
                <th>ID Number</th>
                <th>Code</th>
                <th>Name</th>
                <th>Department</th>
                <th>QR</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td data-label="Profile"><img src="<?= $row['profile_pic'] ?: 'default.png'; ?>" class="profile-pic"></td>
                <td data-label="ID Number"><?= htmlspecialchars($row['emp_id']); ?></td>
                <td data-label="Code"><?= htmlspecialchars($row['employee_code']); ?></td>
                <td data-label="Name"><?= htmlspecialchars($row['fullname']); ?></td>
                <td data-label="Department"><?= htmlspecialchars($row['department']); ?></td>
                <td data-label="QR"><img src="<?= $row['qr_path']; ?>" class="qr-thumb"></td>
                <td data-label="Actions">
                    <a href="download_id.php?id=<?= $row['id']; ?>" class="btn btn-download">Download</a>
                    <a href="view_employees.php?delete_id=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>