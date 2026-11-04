<?php
require_once "db.php";

$payment_id = filter_var(
    $_GET["id"] ?? null,
    FILTER_VALIDATE_INT
);

if (!$payment_id) {
    die("Invalid payment reference.");
}

$stmt = $conn->prepare(
    "SELECT
        p.payment_reference,
        p.amount,
        p.payment_method,
        p.payment_status,
        p.payment_date,
        b.full_name,
        b.room_type,
        b.check_in,
        b.check_out
     FROM payments p
     INNER JOIN bookings b
        ON p.booking_id = b.id
     WHERE p.id = ?"
);

$stmt->bind_param("i", $payment_id);
$stmt->execute();

$result = $stmt->get_result();
$payment = $result->fetch_assoc();

$stmt->close();

if (!$payment) {
    die("Payment receipt not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>WRC Haven - Payment Receipt</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f3f6f2;
    padding: 20px;
}

.receipt {
    max-width: 600px;
    margin: 40px auto;
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

h1, h2 {
    color: #164b35;
}

.success {
    color: #155724;
    background: #d8f5df;
    padding: 12px;
    border-radius: 5px;
}

button {
    padding: 12px 20px;
    background: #164b35;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
</style>
</head>

<body>

<div class="receipt">

<h1>WRC Haven Resort</h1>
<h2>Payment Receipt</h2>

<p class="success">
    Simulated payment successful!
</p>

<p>
<strong>Guest:</strong>
<?= htmlspecialchars($payment["full_name"]) ?>
</p>

<p>
<strong>Room:</strong>
<?= htmlspecialchars($payment["room_type"]) ?>
</p>

<p>
<strong>Check-in:</strong>
<?= htmlspecialchars($payment["check_in"]) ?>
</p>

<p>
<strong>Check-out:</strong>
<?= htmlspecialchars($payment["check_out"]) ?>
</p>

<p>
<strong>Amount Paid:</strong>
KES <?= number_format(
    (float)$payment["amount"], 2
) ?>
</p>

<p>
<strong>Payment Method:</strong>
<?= htmlspecialchars($payment["payment_method"]) ?>
</p>

<p>
<strong>Payment Reference:</strong>
<?= htmlspecialchars($payment["payment_reference"]) ?>
</p>

<p>
<strong>Payment Date:</strong>
<?= htmlspecialchars($payment["payment_date"]) ?>
</p>

<button onclick="window.print()">
    Print Receipt
</button>

</div>

</body>
</html>