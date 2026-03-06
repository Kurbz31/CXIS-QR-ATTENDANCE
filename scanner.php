<?php
include 'db.php';
include 'navbar.php';
include 'auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance Scanner</title>

<style>

body{
    font-family: Arial, sans-serif;
}

/* Page Title */
.page-title{
    text-align:center;
    color:#0d6efd;
    margin-bottom:20px;
}

/* Top Layout */
.scanner-row{
    display:flex;
    justify-content:center;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:30px;
}

/* QR Scanner Box */
#reader{
    width:400px;
    max-width:90%;
    border:2px solid #0d6efd;
    border-radius:10px;
}

/* Employee Profile */
#employee_info{
    width:400px;
    height:400px;
    border:2px solid #0d6efd;
    border-radius:10px;
    background-color:#f0f8ff;
    box-shadow:0 4px 8px rgba(0,0,0,0.1);
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
}

/* Profile Picture */
#profile_pic{
    border-radius:50%;
    width:250px;
    height:250px;
    object-fit:cover;
    margin-bottom:15px;
}

/* Employee Name */
#emp_name{
    font-size:22px;
    font-weight:bold;
    text-align:center;
    color:#0d6efd;
}

/* Department */
#emp_dept{
    font-size:18px;
    text-align:center;
    margin-bottom:10px;
    color:#0d6efd;
}

/* Scan Message */
#scan_msg{
    font-size:16px;
    color:green;
    text-align:center;
}

/* Attendance Container */
.attendance-container{
    width:90%;
    max-width:1000px;
    margin:0 auto;
    border:2px solid #0d6efd;
    border-radius:10px;
    padding:15px;
    background-color:#f0f8ff;
    box-shadow:0 4px 8px rgba(0,0,0,0.1);
}

/* Attendance Title */
.attendance-title{
    text-align:center;
    color:#0d6efd;
    margin-bottom:15px;
}

/* Table Wrapper */
.table-wrapper{
    overflow-x:auto;
}

/* Table */
.attendance-table{
    width:100%;
    border-collapse:collapse;
    font-family:Arial, sans-serif;
}

/* Table Head */
.attendance-table thead{
    background-color:#0d6efd;
    color:white;
}

/* Empty Row */
.empty-row{
    text-align:center;
    padding:10px;
}

</style>
</head>

<body>

<h2 class="page-title">Attendance Scanner</h2>

<div class="scanner-row">

    <!-- QR Scanner -->
    <div id="reader"></div>

    <!-- Profile Preview -->
    <div id="employee_info">
        <img id="profile_pic" src="default.png">

        <div id="emp_name">Employee Name</div>

        <div id="emp_dept">Department</div>

        <div id="scan_msg">Scan QR to record attendance</div>
    </div>

</div>

<!-- Attendance Table -->
<div class="attendance-container">

<h3 class="attendance-title">Shift Attendance (5 PM → 12 PM)</h3>

<div class="table-wrapper">

<table class="attendance-table">

<thead>
<tr>
<th>#</th>
<th>Code</th>
<th>Name</th>
<th>Dept</th>
<th>Time IN</th>
<th>Time OUT</th>
</tr>
</thead>

<tbody id="attendance_table_body">
<tr>
<td colspan="6" class="empty-row">No scans yet for this shift</td>
</tr>
</tbody>

</table>

</div>
</div>

<!-- QR Code Library -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>

let lastScannedCode = '';
let lastScanTime = 0;

function onScanSuccess(decodedText) {

    const now = Date.now();

    if(decodedText === lastScannedCode && (now - lastScanTime < 8000)){
        return;
    }

    lastScannedCode = decodedText;
    lastScanTime = now;

    fetch('save_attendance.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'code=' + decodedText
    })
    .then(res => res.json())
    .then(data => {

        if(data.error){
            alert(data.error);
            return;
        }

        document.getElementById('profile_pic').src = data.profile_pic || 'default.png';
        document.getElementById('emp_name').textContent = data.name;
        document.getElementById('emp_dept').textContent = data.department;
        document.getElementById('scan_msg').textContent = data.message;

        fetchAttendanceTable(data.code);

    });

}

new Html5QrcodeScanner("reader",{
    fps:10,
    qrbox:300,
    rememberLastUsedCamera:true,
    showTorchButtonIfSupported:true
}).render(onScanSuccess);


function fetchAttendanceTable(recentCode=''){

    fetch('fetch_shift_attendance.php')
    .then(res => res.json())
    .then(data => {

        const tbody = document.getElementById('attendance_table_body');

        tbody.innerHTML='';

        if(data.length === 0){

            tbody.innerHTML='<tr><td colspan="6" class="empty-row">No scans yet for this shift</td></tr>';

        } else {

            let i=1;

            data.forEach(row=>{

                const tr=document.createElement('tr');

                let bgColor='';

                if(row.employee_code === recentCode){
                    bgColor = row.time_out ? '#d0ebff' : '#d4edda';
                }

                tr.style.backgroundColor = bgColor;

                tr.innerHTML = `
                    <td>${i++}</td>
                    <td>${row.employee_code}</td>
                    <td>${row.fullname}</td>
                    <td>${row.department}</td>
                    <td>${row.time_in}</td>
                    <td>${row.time_out || '---'}</td>
                `;

                tbody.appendChild(tr);

            });

        }

    });

}

setInterval(fetchAttendanceTable,30000);

fetchAttendanceTable();

</script>

</body>
</html>