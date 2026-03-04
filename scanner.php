<?php
include 'db.php';
include 'navbar.php';
?>

<h2 style="text-align:center; color:#0d6efd; margin-bottom:20px;">Attendance Scanner</h2>

<!-- Top row: scanner left, profile right -->
<div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap; margin-bottom:30px;">

    <!-- QR Scanner (Left) -->
    <div id="reader" style="width:400px; max-width:90%; border:2px solid #0d6efd; border-radius:10px;"></div>

    <!-- Profile Preview (Right) -->
    <div id="employee_info" style="width:400px; height:400px; border:2px solid #0d6efd; border-radius:10px; background-color:#f0f8ff; box-shadow:0 4px 8px rgba(0,0,0,0.1); display:flex; flex-direction:column; align-items:center; justify-content:center;">
        <img id="profile_pic" src="default.png" style="border-radius:50%; width:150px; height:150px; object-fit:cover; margin-bottom:15px;">
        <div id="emp_name" style="font-size:22px; font-weight:bold; text-align:center; color:#0d6efd;">Employee Name</div>
        <div id="emp_dept" style="font-size:18px; text-align:center; margin-bottom:10px; color:#0d6efd;">Department</div>
        <div id="scan_msg" style="font-size:16px; color:green; text-align:center;">Scan QR to record attendance</div>
    </div>

</div>

<!-- Live Attendance Table below -->
<div style="width:90%; max-width:1000px; margin:0 auto; border:2px solid #0d6efd; border-radius:10px; padding:15px; background-color:#f0f8ff; box-shadow:0 4px 8px rgba(0,0,0,0.1);">
    <h3 style="text-align:center; color:#0d6efd; margin-bottom:15px;">Shift Attendance (5 PM → 12 PM)</h3>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-family:Arial, sans-serif;">
            <thead style="background-color:#0d6efd; color:white;">
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
                <tr><td colspan="6" style="text-align:center; padding:10px;">No scans yet for this shift</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- HTML5 QR Code library -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let lastScannedCode = '';
let lastScanTime = 0; // timestamp in ms

function onScanSuccess(decodedText) {
    const now = Date.now();

    // Prevent duplicate scans within 8 seconds
    if(decodedText === lastScannedCode && (now - lastScanTime < 8000)) {
        return; // ignore repeated scan
    }

    lastScannedCode = decodedText;
    lastScanTime = now;

    fetch('save_attendance.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'code=' + decodedText
    })
    .then(res => res.json())
    .then(data => {
        if(data.error){
            alert(data.error);
            return;
        }

        // Update profile preview
        document.getElementById('profile_pic').src = data.profile_pic || 'default.png';
        document.getElementById('emp_name').textContent = data.name;
        document.getElementById('emp_dept').textContent = data.department;
        document.getElementById('scan_msg').textContent = data.message;

        // Refresh attendance table and highlight last scan
        fetchAttendanceTable(data.code);
    });
}

// Initialize QR scanner with guide box
new Html5QrcodeScanner("reader", {
    fps: 10,
    qrbox: 300,
    rememberLastUsedCamera: true,
    showTorchButtonIfSupported: true
}).render(onScanSuccess);

// Function to refresh attendance table
function fetchAttendanceTable(recentCode = '') {
    fetch('fetch_shift_attendance.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('attendance_table_body');
        tbody.innerHTML = '';
        if(data.length === 0){
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:10px;">No scans yet for this shift</td></tr>';
        } else {
            let i = 1;
            data.forEach(row => {
                const tr = document.createElement('tr');

                // Color-code IN/OUT and highlight last scanned row
                let bgColor = '';
                if(row.employee_code === recentCode) {
                    bgColor = row.time_out ? '#d0ebff' : '#d4edda'; // Blue=OUT, Green=IN
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

// Auto-refresh every 30 seconds
setInterval(fetchAttendanceTable, 30000);

// Initial load
fetchAttendanceTable();
</script>