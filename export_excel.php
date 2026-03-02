<?php
include 'db.php';

// 1️⃣ Set month to export (default: current month)
$month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$start_date = $month . "-01";
$end_date = date("Y-m-t", strtotime($start_date)); // last day of month

// 2️⃣ Set CSV headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=attendance_'.$month.'.csv');

$output = fopen('php://output', 'w');

// 3️⃣ Column headers
fputcsv($output, ['Employee Code', 'Employee Name', 'Department', 'Date', 'Time IN', 'Time OUT']);

// 4️⃣ Query attendance
$result = $conn->query("
    SELECT a.*, e.fullname, e.department 
    FROM attendance a
    JOIN employees e ON a.employee_id = e.id
    WHERE a.date BETWEEN '$start_date' AND '$end_date'
    ORDER BY a.employee_id, a.date ASC
");

// 5️⃣ Check for errors
if (!$result) {
    die("Query Error: " . $conn->error);
}

// 6️⃣ Write rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['employee_code'],
        $row['fullname'],
        $row['department'],
        $row['date'],
        "'".$row['time_in'],   // Force Excel to treat as text
        "'".$row['time_out'] ?? ''
    ]);
}

fclose($output);
exit;