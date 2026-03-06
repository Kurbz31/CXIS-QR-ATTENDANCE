<?php
include 'db.php';

include 'auth.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Dashboard - CXIS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #e9f2ff;
        }

        h2 {
            text-align: center;
            color: #0d6efd;
            margin: 30px 0 20px 0;
        }

        section.dashboard-section {
            max-width: 1000px;
            margin: 0 auto 40px auto;
            overflow-x: auto;
            border: 2px solid #0d6efd;
            border-radius: 10px;
            background-color: #f0f8ff;
            padding: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #0d6efd;
            text-align: center;
        }

        table thead {
            background-color: #0d6efd;
            color: white;
        }

        @media (max-width: 650px) {
            section.dashboard-section {
                padding: 10px;
            }
            table th, table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Page Heading -->
    <h2>Attendance Dashboard - <?= date('Y-m-d') ?></h2>

    <!-- Attendance Table -->
    <section class="dashboard-section">
        <?php
        $date = date('Y-m-d');
        $att_result = $conn->query("
            SELECT a.*, e.fullname, e.department, e.employee_code
            FROM attendance a
            JOIN employees e ON a.employee_id = e.id
            WHERE a.date='$date'
            ORDER BY a.time_in ASC
        ");
        if (!$att_result) {
            die("Query Error: " . $conn->error);
        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee Code</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Time IN</th>
                    <th>Time OUT</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                while ($row = $att_result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$i}</td>
                            <td>{$row['employee_code']}</td>
                            <td>{$row['fullname']}</td>
                            <td>{$row['department']}</td>
                            <td>{$row['time_in']}</td>
                            <td>" . ($row['time_out'] ?? '---') . "</td>
                          </tr>";
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </section>

</body>
</html>