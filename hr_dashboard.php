<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>HR Attendance Dashboard - CXIS</title>
<style>
    body { font-family: Arial, sans-serif; margin:0; background:#e9f2ff; }
    h2 { text-align:center; color:#0d6efd; margin-bottom:20px; }
    section.filter-section {
        max-width: 900px;
        margin: 20px auto;
        padding: 15px;
        display:flex;
        flex-wrap:wrap;
        gap:15px;
        justify-content:center;
        align-items:center;
    }
    select, input[type="date"] {
        padding:8px; border-radius:5px; border:1px solid #0d6efd;
    }
    button {
        padding:8px 15px; border:none; border-radius:5px;
        background:#0d6efd; color:white; font-weight:bold;
        cursor:pointer; transition:.3s;
    }
    button:hover { background:#084298; }
    section.profile-preview {
        max-width:900px;
        margin: 0 auto 20px auto;
        padding:15px;
        display:flex;
        gap:15px;
        align-items:center;
        border:2px solid #0d6efd;
        border-radius:10px;
        background:#f0f8ff;
    }
    section.profile-preview img {
        width:100px; height:100px; border-radius:50%; object-fit:cover; border:1px solid #0d6efd;
    }
    section.profile-preview div { font-weight:bold; color:#0d6efd; }
    section.table-section {
        max-width:1000px;
        margin:0 auto 40px auto;
        overflow-x:auto;
        border:2px solid #0d6efd;
        border-radius:10px;
        background:#f0f8ff;
        padding:15px;
    }
    table { width:100%; border-collapse:collapse; font-family:Arial, sans-serif; }
    table th, table td { padding:10px; border:1px solid #0d6efd; text-align:center; }
    table thead { background:#0d6efd; color:white; }
    a.export-btn {
        display:inline-block;
        margin-top:15px;
        background:#0d6efd;
        color:white;
        padding:8px 15px;
        border-radius:5px;
        text-decoration:none;
        font-weight:bold;
    }
    a.export-btn:hover { background:#084298; }
    p.no-records { text-align:center; color:red; font-weight:bold; }
    @media(max-width:650px) {
        section.filter-section, section.profile-preview { flex-direction:column; align-items:stretch; }
        section.profile-preview div { text-align:center; }
    }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<h2>HR Attendance Dashboard</h2>

<!-- Filter Form -->
<section class="filter-section">
<form method="POST" style="display:flex; flex-wrap:wrap; gap:15px; justify-content:center; align-items:center;">
    <?php
    $employees = $conn->query("SELECT id, fullname, employee_code, department, profile_pic FROM employees ORDER BY fullname");
    $selected_emp_id = $_POST['employee_id'] ?? '';
    ?>
    <select name="employee_id" onchange="this.form.submit()">
        <option value="">All Employees</option>
        <?php while($emp=$employees->fetch_assoc()) {
            $selected = ($selected_emp_id==$emp['id'])?'selected':'';
            echo "<option value='{$emp['id']}' $selected>{$emp['fullname']} ({$emp['employee_code']})</option>";
        } ?>
    </select>
    <input type="date" name="from_date" value="<?= $_POST['from_date'] ?? date('Y-m-01') ?>">
    <input type="date" name="to_date" value="<?= $_POST['to_date'] ?? date('Y-m-d') ?>">
    <button type="submit">Search</button>
</form>
</section>

<?php
// Initialize
$attendance_records = [];
$from_date = $_POST['from_date'] ?? date('Y-m-01');
$to_date = $_POST['to_date'] ?? date('Y-m-d');
$employee_info = null;

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $where = "a.date BETWEEN '$from_date' AND '$to_date'";
    if (!empty($_POST['employee_id'])) {
        $emp_id = (int)$_POST['employee_id'];
        $where .= " AND a.employee_id = $emp_id";
        // fetch profile for preview
        $res_emp = $conn->query("SELECT fullname, employee_code, department, profile_pic FROM employees WHERE id=$emp_id");
        if ($res_emp && $res_emp->num_rows>0) $employee_info = $res_emp->fetch_assoc();
    }

    $sql = "SELECT a.*, e.fullname, e.department, e.employee_code
            FROM attendance a
            JOIN employees e ON a.employee_id=e.id
            WHERE $where
            ORDER BY a.date ASC, e.fullname ASC";

    $res = $conn->query($sql);
    if ($res && $res->num_rows>0) {
        while($row=$res->fetch_assoc()) {
            $hours_worked='';
            if($row['time_out']){
                $diff = strtotime($row['time_out'])-strtotime($row['time_in']);
                $hours_worked=gmdate("H:i",$diff);
            }
            $row['hours_worked']=$hours_worked;
            $attendance_records[]=$row;
        }
    }
}
?>

<!-- Profile Preview -->
<?php if($employee_info): ?>
<section class="profile-preview">
    <img src="<?= $employee_info['profile_pic'] ?: 'default.png' ?>">
    <div>
        Name: <?= htmlspecialchars($employee_info['fullname']) ?><br>
        Code: <?= htmlspecialchars($employee_info['employee_code']) ?><br>
        Dept: <?= htmlspecialchars($employee_info['department']) ?>
    </div>
</section>
<?php endif; ?>

<!-- Attendance Table -->
<?php if($attendance_records): ?>
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
<?php $i=1; foreach($attendance_records as $rec):
    $row_style='';
    if(!$rec['time_out']) $row_style='background-color:#f8d7da;';
    else if(strtotime($rec['time_in'])>strtotime('17:00:00')) $row_style='background-color:#fff3cd;';
?>
<tr style="<?= $row_style ?>">
<td><?= $i++ ?></td>
<td><?= $rec['employee_code'] ?></td>
<td><?= $rec['fullname'] ?></td>
<td><?= $rec['department'] ?></td>
<td><?= $rec['date'] ?></td>
<td><?= $rec['time_in'] ?></td>
<td><?= $rec['time_out'] ?: '---' ?></td>
<td><?= $rec['hours_worked'] ?: '---' ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<!-- Export Buttons -->
<a class="export-btn" href="export_excel.php?from_date=<?= $from_date ?>&to_date=<?= $to_date ?>">Export All</a>
<?php if(!empty($_POST['employee_id'])): ?>
<a class="export-btn" href="export_excel.php?from_date=<?= $from_date ?>&to_date=<?= $to_date ?>&emp_id=<?= (int)$_POST['employee_id'] ?>">Export Individual</a>
<?php endif; ?>
</section>
<?php elseif($_SERVER['REQUEST_METHOD']==='POST'): ?>
<p class="no-records">No records found for this selection.</p>
<?php endif; ?>

</body>
</html>