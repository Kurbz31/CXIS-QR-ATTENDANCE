<?php
include 'db.php';

header('Content-Type: application/json');

$current_time = date('H:i:s');

// Determine correct shift date range
if ($current_time >= '17:00:00') {
    // Between 5PM and 11:59PM
    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d', strtotime('+1 day'));
} else {
    // Between 12:00AM and 11:59AM
    $start_date = date('Y-m-d', strtotime('-1 day'));
    $end_date = date('Y-m-d');
}

$sql = "
SELECT a.*, e.fullname, e.department, e.employee_code
FROM attendance a
JOIN employees e ON a.employee_id = e.id
WHERE 
    (
        (a.date = '$start_date' AND a.time_in >= '17:00:00')
        OR
        (a.date = '$end_date' AND a.time_in <= '12:00:00')
    )
ORDER BY a.time_in DESC
";

$result = $conn->query($sql);

$rows = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

echo json_encode($rows);
?>