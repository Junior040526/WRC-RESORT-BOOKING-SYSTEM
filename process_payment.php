<?php
require_once "db.php";

$message = "";
$payment_id = 0;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: payment.php");
    exit;
}

$booking_id = filter_var(
    $_POST["booking_id"] ?? null,
    FILTER_VALIDATE_INT
);

$cardholder = trim($_POST["cardholder"] ?? "");
$card_number = preg_replace(
    "/\D/",
    "",
    $_POST["card_number"] ?? ""
);

$expiry = trim($_POST["expiry"] ?? "");
$cvv = trim($_POST["cvv"] ?? "");

// Validate demonstration card details.
// These values are never saved in the database.
if (
    !$booking_id ||
    $cardholder === "" ||
    strlen($card_number) < 12 ||
    strlen($card_number) > 19 ||
    !preg_match("/^\d{2}\/\d{2}$/", $expiry) ||
    !preg_match("/^\d{3,4}$/", $cvv)
) {
    die("Invalid demonstration payment details.");
}

// Retrieve the booking amount from the database.
$stmt = $conn->prepare(
    "SELECT id, total_price, status
     FROM bookings
     WHERE id = ?"
);

$stmt->bind_param("i", $booking_id);
$stmt->execute();

$result = $stmt->get_result();
$booking = $result->fetch_assoc();

$stmt->close();

if (!$booking) {
    die("Booking not found.");
}

$amount = (float)$booking["total_price"];

if ($amount <= 0) {
    die("Invalid booking amount.");
}

// Generate a unique payment reference.
$reference = "WRC-" .
    strtoupper(bin2hex(random_bytes(6)));

$payment_method = "Bank Card";
$payment_status = "Successful";

// Record the simulated payment.
// Card number, expiry and CVV are NOT stored.
$stmt = $conn->prepare(
    "INSERT INTO payments
    (
        booking_id,
        amount,
        payment_method,
        payment_status,
        payment_reference
    )
    VALUES (?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    "idsss",
    $booking_id,
    $amount,
    $payment_method,
    $payment_status,
    $reference
);

if ($stmt->execute()) {

    $payment_id = $stmt->insert_id;

    $stmt->close();

    // Confirm the reservation after successful
    // simulated payment.
    $stmt = $conn->prepare(
        "UPDATE bookings
         SET status = 'Confirmed'
         WHERE id = ?"
    );

    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->close();

    header(
        "Location: receipt.php?id=" . $payment_id
    );

    exit;

} else {

    $stmt->close();

    die("Payment could not be recorded.");
}
?>