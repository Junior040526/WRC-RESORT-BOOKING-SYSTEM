<?php
session_start();

if (empty($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$status = $_POST["status"] ?? "";

$allowed_statuses = [
    "Pending",
    "Confirmed",
    "Cancelled"
];

if (!$id || !in_array($status, $allowed_statuses, true)) {
    die("Invalid booking details.");
}

$stmt = $conn->prepare(
    "UPDATE bookings SET status = ? WHERE id = ?"
);

$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: admin.php");
    exit;
}

$stmt->close();
die("Unable to update booking.");
?>