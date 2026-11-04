<?php
require_once "db.php";

$message = "";
$booking = null;

$booking_id = filter_var(
    $_GET["booking_id"] ?? null,
    FILTER_VALIDATE_INT
);

if ($booking_id) {

    $stmt = $conn->prepare(
        "SELECT id, full_name, room_type, check_in,
                check_out, total_price, status
         FROM bookings
         WHERE id = ?"
    );

    $stmt->bind_param("i", $booking_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    $stmt->close();

    if (!$booking) {
        $message = "Booking not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>WRC Haven - Payment</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f3f6f2;
    margin: 0;
}

header {
    background: #164b35;
    color: white;
    text-align: center;
    padding: 25px;
}

.container {
    max-width: 600px;
    margin: 40px auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 12px;
    margin-top: 6px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 14px;
    margin-top: 20px;
    background: #164b35;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.message {
    color: #b42318;
    margin-top: 15px;
}
</style>
</head>

<body>

<header>
    <h1>WRC Haven Resort & Retreat</h1>
    <p>Secure Simulated Payment</p>
</header>

<div class="container">

<h2>Booking Payment</h2>

<?php if ($message !== ""): ?>

    <p class="message">
        <?= htmlspecialchars($message) ?>
    </p>

<?php elseif ($booking): ?>

    <p>
        <strong>Booking ID:</strong>
        <?= (int)$booking["id"] ?>
    </p>

    <p>
        <strong>Guest:</strong>
        <?= htmlspecialchars($booking["full_name"]) ?>
    </p>

    <p>
        <strong>Room:</strong>
        <?= htmlspecialchars($booking["room_type"]) ?>
    </p>

    <p>
        <strong>Amount Due:</strong>
        KES <?= number_format(
            (float)$booking["total_price"], 2
        ) ?>
    </p>

    <form method="POST" action="process_payment.php">

        <input type="hidden"
               name="booking_id"
               value="<?= (int)$booking["id"] ?>">

        <label>Cardholder Name</label>

        <input type="text"
               name="cardholder"
               maxlength="100"
               required>

        <label>Card Number (Simulation Only)</label>

        <input type="text"
               name="card_number"
               maxlength="19"
               placeholder="4242 4242 4242 4242"
               required>

        <label>Expiry Date</label>

        <input type="text"
               name="expiry"
               placeholder="MM/YY"
               maxlength="5"
               required>

        <label>CVV (Simulation Only)</label>

        <input type="password"
               name="cvv"
               maxlength="4"
               required>

        <button type="submit">
            Simulate Payment
        </button>

    </form>

    <p>
        This is a demonstration payment only.
        No real money will be transferred.
    </p>

<?php else: ?>

    <form method="GET" action="payment.php">

        <label>Enter Booking ID</label>

        <input type="number"
               name="booking_id"
               min="1"
               required>

        <button type="submit">
            Find Booking
        </button>

    </form>

<?php endif; ?>

</div>

</body>
</html>