<?php
include 'db.php';

// New BPO shift: 5 PM → 12 PM next day
$shift_start_time = '17:00:00'; // 5 PM
$shift_end_time   = '12:00:00'; // 12 PM next day

$now_time = date('H:i:s');
$today = date('Y-m-d');
$yesterday = date('Y-m-d');

// Determine shift start/end dates
if ($now_time >= $shift_start_time) {
    // After 5 PM → shift is today 5 PM → tomorrow 12 PM
    $shift_start_datetime = "$today $shift_start_time";
    $shift_end_datetime   = date('Y-m-d H:i:s', strtotime("$today $shift_end_time +1 day"));
} else {
    // Before 12 PM → shift is yesterday 5 PM → today 12 PM
    $shift_start_datetime = "$yesterday $shift_start_time";
    $shift_end_datetime   = "$today $shift_end_time";
}

// Fetch attendance in the shift window
$sql = "
SELECT a.*, e.fullname, e.department, e.employee_code
FROM attendance a
JOIN employees e ON a.employee_id = e.id
WHERE 
    CONCAT(a.date, ' ', a.time_in) BETWEEN '$shift_start_datetime' AND '$shift_end_datetime'
ORDER BY CONCAT(a.date, ' ', a.time_in) ASC
";

$res = $conn->query($sql);

$data = [];
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $data[] = [
            'employee_code' => $row['employee_code'],
            'fullname' => $row['fullname'],
            'department' => $row['department'],
            'time_in' => date('h:i A', strtotime($row['time_in'])),
            'time_out' => $row['time_out'] ? date('h:i A', strtotime($row['time_out'])) : ''
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($data);