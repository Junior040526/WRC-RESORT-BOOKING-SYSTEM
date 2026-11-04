<?php
session_start();

if (empty($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}

require_once "db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    die("Invalid booking ID.");
}

$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) {
    die("Booking not found.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $room = trim($_POST["room_type"] ?? "");
    $check_in = $_POST["check_in"] ?? "";
    $check_out = $_POST["check_out"] ?? "";
    $guests = filter_var($_POST["guests"] ?? null, FILTER_VALIDATE_INT);

    $rooms = [
        "Standard Room",
        "Deluxe Room",
        "Executive Suite",
        "Family Room"
    ];

    if (
        $name === "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !in_array($room, $rooms, true) ||
        !$guests || $guests < 1 || $guests > 20 ||
        !strtotime($check_in) || !strtotime($check_out) ||
        $check_out <= $check_in
    ) {
        $message = "Please enter valid booking information.";
    } else {
        $update = $conn->prepare(
            "UPDATE bookings SET full_name=?, email=?, room_type=?,
            check_in=?, check_out=?, guests=? WHERE id=?"
        );

        $update->bind_param(
            "sssssii",
            $name, $email, $room,
            $check_in, $check_out, $guests, $id
        );

        if ($update->execute()) {
            $update->close();
            header("Location: admin.php");
            exit;
        } else {
            $message = "Unable to update booking.";
            $update->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Booking - WRC Haven</title>
<style>
body {
    font-family: Arial;
    background: #f3f6f2;
    padding: 30px;
}
.container {
    background: white;
    max-width: 550px;
    margin: auto;
    padding: 25px;
    border-radius: 10px;
}
input, select {
    width: 100%;
    padding: 12px;
    margin: 8px 0 15px;
    box-sizing: border-box;
}
button {
    background: #164b35;
    color: white;
    padding: 12px;
    border: none;
    width: 100%;
    cursor: pointer;
}
</style>
</head>
<body>

<div class="container">
<h2>Edit Customer Booking</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<form method="POST">

<label>Full Name</label>
<input type="text" name="full_name"
value="<?php echo htmlspecialchars($booking["full_name"]); ?>"
required>

<label>Email</label>
<input type="email" name="email"
value="<?php echo htmlspecialchars($booking["email"]); ?>"
required>

<label>Room Type</label>
<select name="room_type" required>
<?php foreach ($rooms as $room_option): ?>
<option value="<?php echo htmlspecialchars($room_option); ?>"
<?php echo $booking["room_type"] === $room_option ? "selected" : ""; ?>>
<?php echo htmlspecialchars($room_option); ?>
</option>
<?php endforeach; ?>
</select>

<label>Check-in Date</label>
<input type="date" name="check_in"
value="<?php echo htmlspecialchars($booking["check_in"]); ?>"
required>

<label>Check-out Date</label>
<input type="date" name="check_out"
value="<?php echo htmlspecialchars($booking["check_out"]); ?>"
required>

<label>Guests</label>
<input type="number" name="guests" min="1" max="20"
value="<?php echo htmlspecialchars($booking["guests"]); ?>"
required>

<button type="submit">Update Booking</button>

</form>

<p><a href="admin.php">Back to Dashboard</a></p>
</div>

</body>
</html>