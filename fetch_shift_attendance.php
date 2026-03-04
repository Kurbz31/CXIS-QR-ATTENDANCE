<?php
include 'db.php';

// Determine shift "date" for 5 PM → 12 PM next day
$now = new DateTime();
$shift_start = new DateTime('today 17:00');

if ($now < $shift_start) {
    // Before 5 PM, current shift started yesterday
    $shift_date = (new DateTime('yesterday'))->format('Y-m-d');
} else {
    // After 5 PM, shift is today
    $shift_date = $now->format('Y-m-d');
}

// Fetch attendance for this shift
$res = $conn->query("
    SELECT a.*, e.fullname, e.department, e.employee_code
    FROM attendance a
    JOIN employees e ON a.employee_id = e.id
    WHERE a.date = '$shift_date'
    ORDER BY a.time_in ASC
");

$data = [];
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>