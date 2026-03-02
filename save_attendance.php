<?php
include 'db.php';

if (!isset($_POST['code'])) die(json_encode(['error'=>'No QR code received']));

$code = $_POST['code'];

// 1️⃣ Find employee
$result = $conn->query("SELECT * FROM employees WHERE employee_code='$code'");
if (!$result || $result->num_rows == 0) {
    die(json_encode(['error'=>'Employee not found']));
}

$employee = $result->fetch_assoc();
$employee_id = $employee['id'];
$date = date('Y-m-d');
$time_now = date('H:i:s');

// 2️⃣ Check today's attendance
$att_result = $conn->query("SELECT * FROM attendance WHERE employee_id=$employee_id AND date='$date'");
$response = [
    'name' => $employee['fullname'],
    'department' => $employee['department'],
    'profile_pic' => $employee['profile_pic'] ?? '',
    'code' => $employee['employee_code'],
];

if ($att_result->num_rows == 0) {
    // First scan = IN
    $conn->query("INSERT INTO attendance (employee_id, employee_code, time_in, date) VALUES ($employee_id, '$code', NOW(), '$date')");
    $response['status'] = 'IN';
    $response['time'] = $time_now;
    $response['message'] = "✅ Time IN recorded";
} else {
    $attendance = $att_result->fetch_assoc();
    if ($attendance['time_out'] == NULL) {
        // Second scan = OUT
        $conn->query("UPDATE attendance SET time_out=NOW() WHERE id=".$attendance['id']);
        $response['status'] = 'OUT';
        $response['time'] = $time_now;
        $response['message'] = "✅ Time OUT recorded";
    } else {
        $response['status'] = 'ALREADY';
        $response['time'] = $time_now;
        $response['message'] = "⚠ Already scanned OUT today";
    }
}

echo json_encode($response);
?>