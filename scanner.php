<?php include 'navbar.php'; ?>

<h2>Attendance Scanner</h2>
<div id="reader" style="width:400px;"></div>

<!-- Display scan info -->
<div id="employee_info" style="margin-top:20px; display:flex; align-items:center;">
    <img id="profile_pic" src="" 
         style="border-radius:50%; margin-right:15px; display:none; width:120px; height:120px; object-fit:cover;">
    <div>
        <div id="emp_name" style="font-size:22px; font-weight:bold;"></div>
        <div id="emp_dept" style="font-size:18px;"></div>
        <div id="scan_msg" style="font-size:16px; color:green;"></div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function onScanSuccess(decodedText) {
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
        // Show profile pic
        if(data.profile_pic){
            document.getElementById('profile_pic').src = data.profile_pic;
            document.getElementById('profile_pic').style.display = 'block';
        } else {
            document.getElementById('profile_pic').style.display = 'none';
        }

        document.getElementById('emp_name').textContent = data.name;
        document.getElementById('emp_dept').textContent = data.department;
        document.getElementById('scan_msg').textContent = data.message;

        // Optional: remove info after 5 seconds
        setTimeout(() => {
            document.getElementById('profile_pic').style.display = 'none';
            document.getElementById('emp_name').textContent = '';
            document.getElementById('emp_dept').textContent = '';
            document.getElementById('scan_msg').textContent = '';
        }, 5000);
    });
}

new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 }).render(onScanSuccess);
</script>