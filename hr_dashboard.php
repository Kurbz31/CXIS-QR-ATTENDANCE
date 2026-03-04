<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HR Attendance Dashboard - CXIS</title>

<!-- Styles -->
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #e9f2ff;
}
h2 {
    text-align: center;
    color: #0d6efd;
    margin-bottom: 20px;
}

/* Filter Section */
section.filter-section {
    max-width: 900px;
    margin: 20px auto;
    padding: 15px;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    align-items: center;
}
select, input[type="date"] {
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #0d6efd;
}
button, .export-btn {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    background-color: #0d6efd;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
    text-decoration: none;
}
button:hover, .export-btn:hover {
    background-color: #084298;
}

/* Attendance Table Section */
section.table-section {
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
p.no-records {
    text-align: center;
    color: red;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 650px) {
    section.filter-section {
        flex-direction: column;
        align-items: stretch;
    }
    table th, table td {
        padding: 8px;
        font-size: 14px;
    }
}
</style>
</head>
<body>

<!-- Navbar -->
<?php include 'navbar.php'; ?>

<h2>HR Attendance Dashboard</h2>

<!-- Filter Form -->
<section class="filter-section">
    <form method="POST" style="display:flex; flex-wrap:wrap; gap:15px; justify-content:center; align-items:center;">
        <?php
        // Employee dropdown
        $employees = $conn->query("SELECT id, fullname, employee_code FROM employees ORDER BY fullname");
        $emp_id = $_POST['employee_id'] ?? '';
        ?>
        <select name="employee_id">
            <option value="">All Employees</option>
            <?php while ($emp = $employees->fetch_assoc()): ?>
                <option value="<?= $emp['id'] ?>" <?= ($emp_id == $emp['id'] ? 'selected' : '') ?>>
                    <?= htmlspecialchars($emp['fullname']) ?> (<?= htmlspecialchars($emp['employee_code']) ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Date Filters -->
        <?php
        $from_date = $_POST['from_date'] ?? date('Y-m-01');
        $to_date = $_POST['to_date'] ?? date('Y-m-d');
        ?>
        <input type="date" name="from_date" value="<?= $from_date ?>">
        <input type="date" name="to_date" value="<?= $to_date ?>">

        <button type="submit">Search</button>
    </form>
</section>

<?php
// Fetch Attendance Records
$attendance_records = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $where = "a.date BETWEEN '$from_date' AND '$to_date'";
    if (!empty($_POST['employee_id'])) {
        $where .= " AND a.employee_id = " . (int)$_POST['employee_id'];
    }

    $sql = "SELECT a.*, e.fullname, e.department, e.employee_code
            FROM attendance a
            JOIN employees e ON a.employee_id = e.id
            WHERE $where
            ORDER BY a.date ASC, e.fullname ASC";

    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $hours_worked = '';
            if ($row['time_out']) {
                $time_in = strtotime($row['time_in']);
                $time_out = strtotime($row['time_out']);
                $diff = $time_out - $time_in;
                $hours_worked = gmdate("H:i", $diff);
            }
            $row['hours_worked'] = $hours_worked;
            $attendance_records[] = $row;
        }
    }
}
?>

<!-- Attendance Table -->
<?php if ($attendance_records): ?>
<section class="table-section">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee Code</th>
                <th>Name</th>
                <th>Department</th>
                <th>Date</th>
                <th>Time IN</th>
                <th>Time OUT</th>
                <th>Hours Worked</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($attendance_records as $rec): 
                $row_style = '';
                if (!$rec['time_out']) $row_style = 'background-color:#f8d7da;'; // missing OUT
                elseif (strtotime($rec['time_in']) > strtotime('17:00:00')) $row_style = 'background-color:#fff3cd;'; // late
            ?>
            <tr style="<?= $row_style ?>">
                <td><?= $i ?></td>
                <td><?= htmlspecialchars($rec['employee_code']) ?></td>
                <td><?= htmlspecialchars($rec['fullname']) ?></td>
                <td><?= htmlspecialchars($rec['department']) ?></td>
                <td><?= $rec['date'] ?></td>
                <td><?= $rec['time_in'] ?></td>
                <td><?= $rec['time_out'] ?: '---' ?></td>
                <td><?= $rec['hours_worked'] ?: '---' ?></td>
            </tr>
            <?php $i++; endforeach; ?>
        </tbody>
    </table>

    <!-- Export Form -->
    <form method="GET" action="export_excel.php" style="text-align:center; margin-top:15px;">
        <input type="hidden" name="from_date" value="<?= $from_date ?>">
        <input type="hidden" name="to_date" value="<?= $to_date ?>">
        <input type="hidden" name="emp_id" value="<?= $emp_id ?>">
        <button type="submit" class="export-btn">Export CSV</button>
    </form>
</section>

<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p class="no-records">No records found for this selection.</p>
<?php endif; ?>

</body>
</html>