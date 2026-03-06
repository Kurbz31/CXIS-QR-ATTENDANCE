<?php
include 'db.php';
include 'navbar.php';

/* ---------------- DELETE EMPLOYEE ---------------- */

if(isset($_GET['delete_id'])){
    $delete_id = (int)$_GET['delete_id'];

    $res = $conn->query("SELECT profile_pic, qr_path FROM employees WHERE id=$delete_id");

    if($res && $res->num_rows > 0){
        $row = $res->fetch_assoc();

        if(!empty($row['profile_pic']) && file_exists($row['profile_pic'])){
            unlink($row['profile_pic']);
        }

        if(!empty($row['qr_path']) && file_exists($row['qr_path'])){
            unlink($row['qr_path']);
        }
    }

    $conn->query("DELETE FROM employees WHERE id=$delete_id");

    echo "<script>
        alert('Employee deleted successfully!');
        window.location='view_employees.php';
    </script>";
}

/* ---------------- SEARCH ---------------- */

$search = $_GET['search'] ?? '';

/* ---------------- PAGINATION ---------------- */

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1){ $page = 1; }

$offset = ($page - 1) * $limit;

/* ---------------- QUERY ---------------- */

if($search != ''){

    $search = $conn->real_escape_string($search);

    $count_result = $conn->query("
        SELECT COUNT(*) as total
        FROM employees
        WHERE fullname LIKE '%$search%'
        OR emp_id LIKE '%$search%'
        OR employee_code LIKE '%$search%'
        OR department LIKE '%$search%'
    ");

    $count_row = $count_result->fetch_assoc();
    $total_employees = $count_row['total'];

    $result = $conn->query("
        SELECT *
        FROM employees
        WHERE fullname LIKE '%$search%'
        OR emp_id LIKE '%$search%'
        OR employee_code LIKE '%$search%'
        OR department LIKE '%$search%'
        ORDER BY id DESC
        LIMIT $limit OFFSET $offset
    ");

}else{

    $count_result = $conn->query("SELECT COUNT(*) as total FROM employees");
    $count_row = $count_result->fetch_assoc();
    $total_employees = $count_row['total'];

    $result = $conn->query("
        SELECT *
        FROM employees
        ORDER BY id DESC
        LIMIT $limit OFFSET $offset
    ");
}

$total_pages = ceil($total_employees / $limit);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Employee Management - CXIS</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
font-family:Arial;
background:#e9f2ff;
margin:0;
}

/* HEADER */

h2{
text-align:center;
color:#0d6efd;
margin:30px 0 10px;
}

.employee-count{
text-align:center;
font-weight:bold;
color:#0d6efd;
margin-bottom:20px;
}

/* SEARCH */

.search-box{
text-align:center;
margin-bottom:20px;
}

.search-box input{
padding:10px;
width:280px;
border:1px solid #0d6efd;
border-radius:5px;
}

.search-box button{
padding:10px 15px;
border:none;
border-radius:5px;
background:#0d6efd;
color:white;
font-weight:bold;
cursor:pointer;
}

.search-box a{
padding:10px 15px;
background:#6c757d;
color:white;
border-radius:5px;
text-decoration:none;
}

/* TABLE */

.table-container{
max-width:1200px;
margin:auto;
overflow-x:auto;
background:white;
border-radius:10px;
padding:15px;
border:2px solid #0d6efd;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

table{
width:100%;
border-collapse:collapse;
}

thead{
background:#0d6efd;
color:white;
position:sticky;
top:0;
}

th,td{
padding:12px;
text-align:center;
border:1px solid #ddd;
}

tr:nth-child(even){
background:#f9f9f9;
}

tr:hover{
background:#e3f2fd;
}

/* IMAGES */

.profile-pic{
width:60px;
height:60px;
border-radius:50%;
object-fit:cover;
}

.qr-thumb{
width:50px;
height:50px;
}

/* ACTION BUTTONS */

.action-buttons{
display:flex;
justify-content:center;
gap:8px;
}

.btn{
padding:8px 10px;
border-radius:6px;
text-decoration:none;
color:white;
font-size:14px;
display:flex;
align-items:center;
justify-content:center;
}

.btn.edit{ background:#3498db; }

.btn.download{ background:#27ae60; }

.btn.delete{ background:#e74c3c; }

.btn:hover{
opacity:0.85;
}

/* PAGINATION */

.pagination{
text-align:center;
margin:25px 0;
}

.pagination a{
padding:8px 12px;
margin:4px;
text-decoration:none;
border-radius:5px;
background:#0d6efd;
color:white;
font-weight:bold;
}

.pagination a:hover{
background:#084298;
}

.pagination a.active{
background:#28a745;
}

/* MOBILE */

@media screen and (max-width:768px){

thead{ display:none; }

table,tbody,tr,td{
display:block;
width:100%;
}

tr{
margin-bottom:15px;
border-bottom:2px solid #0d6efd;
}

td{
text-align:right;
padding-left:50%;
position:relative;
}

td::before{
content:attr(data-label);
position:absolute;
left:10px;
width:45%;
text-align:left;
font-weight:bold;
}

.action-buttons{
justify-content:flex-end;
}

}

</style>

</head>

<body>

<h2>Employee Management</h2>

<div class="employee-count">
Total Employees: <?= $total_employees ?>
</div>

<!-- SEARCH -->

<div class="search-box">

<form method="GET">

<input
type="text"
name="search"
placeholder="Search Name, ID, Code, Department..."
value="<?= htmlspecialchars($search) ?>"
>

<button type="submit">
<i class="fa fa-search"></i> Search
</button>

<a href="view_employees.php">
Reset
</a>

</form>

</div>

<!-- TABLE -->

<div class="table-container">

<table>

<thead>
<tr>
<th>Profile</th>
<th>ID Number</th>
<th>Name</th>
<th>Department</th>
<th>QR</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>

<td data-label="Profile">
<img src="<?= $row['profile_pic'] ?: 'default.png'; ?>" class="profile-pic">
</td>

<td data-label="ID Number">
<?= htmlspecialchars($row['emp_id']); ?>
</td>

<td data-label="Name">
<?= htmlspecialchars($row['fullname']); ?>
</td>

<td data-label="Department">
<?= htmlspecialchars($row['department']); ?>
</td>

<td data-label="QR">
<img src="<?= $row['qr_path']; ?>" class="qr-thumb">
</td>

<td data-label="Actions">

<div class="action-buttons">

<a href="edit_employee.php?id=<?= $row['id']; ?>" class="btn edit">
<i class="fas fa-edit"></i>
</a>

<a href="download_id.php?id=<?= $row['id']; ?>" class="btn download">
<i class="fas fa-download"></i>
</a>

<a href="delete_employee.php?id=<?= $row['id']; ?>"
class="btn delete"
onclick="return confirm('Delete this employee?');">
<i class="fas fa-trash"></i>
</a>

</div>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<!-- PAGINATION -->

<div class="pagination">

<?php if($page > 1){ ?>
<a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">Prev</a>
<?php } ?>

<?php for($i=1; $i<=$total_pages; $i++){ ?>

<a
href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
class="<?= ($i == $page) ? 'active' : '' ?>"
>
<?= $i ?>
</a>

<?php } ?>

<?php if($page < $total_pages){ ?>
<a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">Next</a>
<?php } ?>

</div>

</body>
</html>