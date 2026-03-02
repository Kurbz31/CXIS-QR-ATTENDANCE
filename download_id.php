<?php
include 'db.php';

// 1️⃣ Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Employee ID not provided.");
}

$id = (int) $_GET['id'];

// 2️⃣ Fetch employee
$result = $conn->query("SELECT * FROM employees WHERE id=$id");
if (!$result || $result->num_rows == 0) {
    die("Error: Employee not found.");
}

$employee = $result->fetch_assoc();

// 3️⃣ Load QR code
$qr_path = $employee['qr_path'];
if (!file_exists($qr_path)) {
    die("Error: QR code file not found.");
}

$qr = imagecreatefrompng($qr_path);
$qr_width = imagesx($qr);
$qr_height = imagesy($qr);

// 4️⃣ Create new image for ID
$padding = 20;
$font_size = 5;
$font_height = imagefontheight($font_size);
$text = $employee['fullname'];
$text_width = imagefontwidth($font_size) * strlen($text);

$width = $qr_width + 2*$padding;
$height = $qr_height + $padding*2 + $font_height;

$image = imagecreatetruecolor($width, $height);

// 5️⃣ Colors
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);

// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $white);

// Copy QR code onto image
imagecopy($image, $qr, $padding, $padding, 0, 0, $qr_width, $qr_height);
imagedestroy($qr);

// Add name below QR, centered
$x = ($width - $text_width)/2;
$y = $qr_height + $padding;
imagestring($image, $font_size, $x, $y, $text, $black);

// 6️⃣ Output PNG for download
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="CXIS-'.$employee['employee_code'].'.png"');

imagepng($image);
imagedestroy($image);