<?php

$conn = new mysqli("localhost", "root", "brainstorm", "company_attendance");
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>