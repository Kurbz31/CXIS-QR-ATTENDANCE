<?php
include 'db.php';
include 'navbar.php';

// Handle delete request
if(isset($_GET['delete_id'])){
    $delete_id = (int)$_GET['delete_id'];
    
    // Optional: delete profile pic and QR file
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

<h2 style="color:#0d6efd; margin-bottom:20px;">All Employees</h2>

<div style="overflow-x:auto;">
<table class="employee-table">
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
        <td><img src="<?= $row['profile_pic'] ?: 'default.png'; ?>" class="profile-pic"></td>
        <td><?= htmlspecialchars($row['emp_id']); ?></td>
        <td><?= htmlspecialchars($row['employee_code']); ?></td>
        <td><?= htmlspecialchars($row['fullname']); ?></td>
        <td><?= htmlspecialchars($row['department']); ?></td>
        <td><img src="<?= $row['qr_path']; ?>" class="qr-thumb"></td>
        <td>
            <a href="download_id.php?id=<?= $row['id']; ?>" class="btn btn-download">Download</a>
            <a href="view_employees.php?delete_id=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
        </td>
    </tr>
    <?php } ?>
    </tbody>
</table>
</div>

<style>
/* Table Styling */
.employee-table {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
}

.employee-table thead {
    background-color: #0d6efd;
    color: white;
}

.employee-table th, .employee-table td {
    padding: 12px;
    text-align: center;
    border: 1px solid #ddd;
}

.employee-table tr:nth-child(even){
    background-color: #f2f2f2;
}

.employee-table tr:hover {
    background-color: #cce5ff;
}

/* Profile Pic */
.profile-pic {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

/* QR Thumb */
.qr-thumb {
    width: 50px;
    height: 50px;
    object-fit: contain;
}

/* Buttons */
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
    .employee-table thead {
        display: none;
    }
    .employee-table, .employee-table tbody, .employee-table tr, .employee-table td {
        display: block;
        width: 100%;
    }
    .employee-table tr {
        margin-bottom: 15px;
        border-bottom: 2px solid #0d6efd;
    }
    .employee-table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
    }
    .employee-table td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        width: 45%;
        text-align: left;
        font-weight: bold;
    }
}
</style>