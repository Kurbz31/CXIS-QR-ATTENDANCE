<?php
include 'db.php';

$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

$res = $conn->query("
SELECT a.*, e.fullname, e.department, e.employee_code
FROM attendance a
JOIN employees e ON a.employee_id = e.id
WHERE 
    (a.date='$today')
    OR
    (a.date='$yesterday' AND a.time_in >= '17:00:00')
ORDER BY a.time_in ASC
");

$data = [];

if($res && $res->num_rows > 0){
    while($row = $res->fetch_assoc()){
        $data[] = $row;
    }
}

echo json_encode($data);
?>