<?php
include 'db.php';

// 1️⃣ Get filter parameters from GET
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');
$emp_id = isset($_GET['emp_id']) ? (int)$_GET['emp_id'] : 0;

// 2️⃣ CSV headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=attendance_export.csv');

$output = fopen('php://output', 'w');

// 3️⃣ Column headers
fputcsv($output, ['Employee Code', 'Employee Name', 'Department', 'Date', 'Time IN', 'Time OUT']);

// 4️⃣ Build query
$where = "a.date BETWEEN '$from_date' AND '$to_date'";
if ($emp_id > 0) {
    $where .= " AND a.employee_id = $emp_id";
}

$sql = "
    SELECT a.*, e.fullname, e.department, e.employee_code
    FROM attendance a
    JOIN employees e ON a.employee_id = e.id
    WHERE $where
    ORDER BY e.fullname ASC, a.date ASC
";

$result = $conn->query($sql);
if (!$result) {
    die("Query Error: " . $conn->error);
}

// 5️⃣ Write rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['employee_code'],
        $row['fullname'],
        $row['department'],
        $row['date'],
        "'".$row['time_in'],   // force Excel to treat as text
        "'".$row['time_out'] ?? ''
    ]);
}

fclose($output);
exit;