<?php
include 'db.php';
include 'navbar.php';

$result = $conn->query("SELECT * FROM employees ORDER BY id DESC");
?>

<h2>All Employees</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Code</th>
        <th>Name</th>
        <th>Department</th>
        <th>QR</th>
        <th>Action</th>
    </tr>

<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['employee_code']; ?></td>
    <td><?php echo $row['fullname']; ?></td>
    <td><?php echo $row['department']; ?></td>
    <td><img src="<?php echo $row['qr_path']; ?>" width="50"></td>
<td><a href="download_id.php?id=<?php echo $row['id']; ?>">Download QR ID</a></td>
<?php } ?>

</table>

</div> <!-- close content -->