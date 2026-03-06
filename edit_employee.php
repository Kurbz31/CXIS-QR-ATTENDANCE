<?php
include 'db.php';
include 'navbar.php';

if(!isset($_GET['id'])){
    header("Location: view_employees.php");
    exit;
}

$id = (int)$_GET['id'];

$res = $conn->query("SELECT * FROM employees WHERE id=$id");

if($res->num_rows == 0){
    die("Employee not found.");
}

$emp = $res->fetch_assoc();


/* UPDATE EMPLOYEE */

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $fullname = $conn->real_escape_string($_POST['fullname']);

    $profile_pic = $emp['profile_pic'];

    if(!empty($_FILES['profile_pic']['name'])){

        $upload_dir = "uploads/";

        if(!is_dir($upload_dir)){
            mkdir($upload_dir);
        }

        $filename = time().'_'.$_FILES['profile_pic']['name'];
        $target = $upload_dir.$filename;

        if(move_uploaded_file($_FILES['profile_pic']['tmp_name'],$target)){

            if(!empty($profile_pic) && file_exists($profile_pic)){
                unlink($profile_pic);
            }

            $profile_pic = $target;
        }
    }

    $conn->query("
        UPDATE employees 
        SET fullname='$fullname',
        profile_pic='$profile_pic'
        WHERE id=$id
    ");

    echo "<script>
        alert('Employee updated successfully!');
        window.location='view_employees.php';
    </script>";
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Employee</title>

<style>

body{
font-family:Arial;
background:#e9f2ff;
}

.container{
max-width:500px;
margin:40px auto;
background:#f0f8ff;
padding:25px;
border-radius:10px;
border:2px solid #0d6efd;
}

h2{
text-align:center;
color:#0d6efd;
}

input{
width:100%;
padding:10px;
margin-top:10px;
margin-bottom:15px;
border-radius:5px;
border:1px solid #0d6efd;
}

button{
background:#0d6efd;
color:white;
padding:10px;
border:none;
border-radius:5px;
width:100%;
font-weight:bold;
cursor:pointer;
}

button:hover{
background:#084298;
}

.profile-preview{
display:block;
margin:auto;
width:120px;
height:120px;
border-radius:50%;
object-fit:cover;
margin-bottom:15px;
}

</style>

</head>

<body>

<div class="container">

<h2>Edit Employee</h2>

<form method="POST" enctype="multipart/form-data">

<img src="<?= $emp['profile_pic'] ?: 'default.png' ?>" class="profile-preview">

<label>Full Name</label>
<input type="text" name="fullname" value="<?= htmlspecialchars($emp['fullname']) ?>" required>

<label>Change Profile Picture</label>
<input type="file" name="profile_pic">

<button type="submit">Update Employee</button>

</form>

</div>

</body>
</html>