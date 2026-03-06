<?php
include 'db.php';
include 'navbar.php';

/* ---------------- DELETE EMPLOYEE ---------------- */
if(isset($_GET['delete_id'])){
    $delete_id = (int)$_GET['delete_id'];

    // delete files
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

/* ---------------- SEARCH FUNCTION ---------------- */

$search = $_GET['search'] ?? '';

if($search != ''){
    $search = $conn->real_escape_string($search);

    $result = $conn->query("
        SELECT * FROM employees
        WHERE fullname LIKE '%$search%'
        OR emp_id LIKE '%$search%'
        OR employee_code LIKE '%$search%'
        OR department LIKE '%$search%'
        ORDER BY id DESC
    ");
}else{
    $result = $conn->query("SELECT * FROM employees ORDER BY id DESC");
}

$total_employees = $result->num_rows;

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<title>All Employees - CXIS</title>

<style>

body{
    font-family: Arial, sans-serif;
    background:#e9f2ff;
    margin:0;
}

h2{
    text-align:center;
    color:#0d6efd;
    margin:30px 0 10px 0;
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
    width:260px;
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

.search-box button:hover{
    background:#084298;
}

.search-box a{
    padding:10px 15px;
    background:#6c757d;
    color:white;
    border-radius:5px;
    text-decoration:none;
    font-weight:bold;
}

/* TABLE */

.table-container{
    max-width:1200px;
    margin:auto;
    overflow-x:auto;
    background:#f0f8ff;
    border-radius:10px;
    padding:15px;
    border:2px solid #0d6efd;
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#0d6efd;
    color:white;
}

th, td{
    padding:12px;
    text-align:center;
    border:1px solid #ddd;
}

tr:nth-child(even){
    background:#f2f2f2;
}

tr:hover{
    background:#cce5ff;
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


.btn {
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-left: 20px;
}

.btn i {
    pointer-events: none;
}

.btn.edit {
    background-color: #3498db;
}

.btn.download {
    background-color: #27ae60;
}

.btn.delete {
    background-color: #e74c3c;
}

.btn:hover {
    opacity: 0.85;
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
.action-buttons {
    display: flex;
    gap: 8px;
}

.btn {
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn i {
    pointer-events: none;
}

.btn.edit {
    background-color: #3498db;
}

.btn.download {
    background-color: #27ae60;
}

.btn.delete {
    background-color: #e74c3c;
}

.btn:hover {
    opacity: 0.85;
}

}

</style>
</head>

<body>

<h2>All Employees</h2>

<div class="employee-count">
Total Employees: <?= $total_employees ?>
</div>

<!-- SEARCH BAR -->

<div class="search-box">

<form method="GET">

<input 
type="text"
name="search"
placeholder="Search Name, ID, Code, Department..."
value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
>

<button type="submit">Search</button>

<a href="view_employees.php">Reset</a>

</form>

</div>

<!-- EMPLOYEE TABLE -->

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

<?php while ($row = $result->fetch_assoc()) { ?>

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

<td class="action-buttons">

    <a href="edit_employee.php?id=<?php echo $row['id']; ?>" class="btn edit">
        <i class="fas fa-edit"></i>
    </a>

    <a href="download_id.php?id=<?php echo $row['id']; ?>" class="btn download">
        <i class="fas fa-download"></i>
    </a>

    <a href="delete_employee.php?id=<?php echo $row['id']; ?>" 
       class="btn delete"
       onclick="return confirm('Are you sure you want to delete this employee?');">
        <i class="fas fa-trash"></i>
    </a>

</td>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</body>
</html>