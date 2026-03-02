<?php

$conn = new mysqli("localhost", "root", "", "company_attendance");
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>