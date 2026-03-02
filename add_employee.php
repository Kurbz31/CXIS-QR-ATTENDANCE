<?php
// Include database connection if needed
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee - CXIS Attendance</title>
    <!-- Styles for modern UI -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #e9f2ff;
        }

        h2 {
            text-align: center;
            color: #0d6efd;
            margin-bottom: 25px;
        }

        section.form-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            border: 2px solid #0d6efd;
            border-radius: 10px;
            background-color: #f0f8ff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        label {
            font-weight: bold;
            color: #0d6efd;
        }

        input[type="text"],
        input[type="file"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #0d6efd;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #084298;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Responsive adjustments */
        @media (max-width: 650px) {
            section.form-container {
                margin: 20px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Add Employee Form Section -->
    <section class="form-container">
        <h2>Add New Employee</h2>
        <form method="POST" action="save_employee.php" enctype="multipart/form-data">
            
            <!-- Full Name -->
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" placeholder="Enter full name" required>

            <!-- Employee ID -->
            <label for="emp_id">Employee ID Number:</label>
            <input type="text" id="emp_id" name="emp_id" placeholder="Enter employee ID" required>

            <!-- Department -->
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" placeholder="Enter department" required>

            <!-- Profile Picture -->
            <label for="profile_pic">Profile Picture:</label>
            <input type="file" id="profile_pic" name="profile_pic" accept="image/*">

            <!-- Submit Button -->
            <button type="submit">Add Employee</button>
        </form>
    </section>

</body>
</html>