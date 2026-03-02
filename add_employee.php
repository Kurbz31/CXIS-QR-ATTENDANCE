<?php include 'navbar.php'; ?>

<h2>Add New Employee</h2>

<form method="POST" action="save_employee.php" enctype="multipart/form-data">
    Full Name: <input type="text" name="fullname" required><br><br>
    Employee ID Number: <input type="text" name="emp_id" required><br><br>
    Department: <input type="text" name="department" required><br><br>
    Profile Picture: <input type="file" name="profile_pic" accept="image/*"><br><br>
    <button type="submit">Add Employee</button>
</form>

</div>