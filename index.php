<?php
include 'db.php';
include 'navbar.php';

$date = date('Y-m-d');

// 1️⃣ Run the query safely
$att_result = $conn->query("
    SELECT a.*, e.fullname, e.department 
    FROM attendance a
    JOIN employees e ON a.employee_id = e.id
    WHERE a.date='$date'
    ORDER BY a.time_in ASC
");

// 2️⃣ Check if query succeeded
if (!$att_result) {
    die("Query Error: " . $conn->error);
}
?>

<h2>Attendance Dashboard - <?= $date ?></h2>

<table border="1" cellpadding="10">
    <tr>
        <th>#</th>
        <th>Employee Code</th>
        <th>Name</th>
        <th>Department</th>
        <th>Time IN</th>
        <th>Time OUT</th>
    </tr>

<?php
$i = 1;
while ($row = $att_result->fetch_assoc()) { ?>
<tr>
    <td><?= $i++; ?></td>
    <td><?= $row['employee_code']; ?></td>
    <td><?= $row['fullname']; ?></td>
    <td><?= $row['department']; ?></td>
    <td><?= $row['time_in']; ?></td>
    <td><?= $row['time_out'] ?? '---'; ?></td>
</tr>
<?php } ?>
</table>

</div> <!-- close content -->