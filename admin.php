<?php
require_once "db.php";

$sql = "SELECT * FROM bookings ORDER BY booking_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>WRC Haven - Admin Dashboard</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f3f6f2;
    margin: 0;
    padding: 20px;
}

header {
    background: #164b35;
    color: white;
    padding: 20px;
    text-align: center;
}

.container {
    background: white;
    padding: 20px;
    margin: 25px auto;
    max-width: 1200px;
    border-radius: 10px;
    overflow-x: auto;
}

h2 {
    color: #164b35;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 750px;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

th {
    background: #164b35;
    color: white;
}

tr:nth-child(even) {
    background: #f2f2f2;
}
</style>
</head>

<body>

<header>
    <h1>WRC Haven Resort & Retreat</h1>
    <p>Administrator Dashboard</p>
</header>

<div class="container">

<h2>Customer Bookings</h2>

<?php if ($result): ?>

<table>
<tr>
    <th>ID</th>
    <th>Full Name</th>
    <th>Email</th>
    <th>Room Type</th>
    <th>Check In</th>
    <th>Check Out</th>
    <th>Guests</th>
    <th>Booking Date</th>
    <th>Actions</th>
    <th>Status</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>

<tr>
    <td><?php echo htmlspecialchars($row["id"]); ?></td>
    <td><?php echo htmlspecialchars($row["full_name"]); ?></td>
    <td><?php echo htmlspecialchars($row["email"]); ?></td>
    <td><?php echo htmlspecialchars($row["room_type"]); ?></td>
    <td><?php echo htmlspecialchars($row["check_in"]); ?></td>
    <td><?php echo htmlspecialchars($row["check_out"]); ?></td>
    <td><?php echo htmlspecialchars($row["guests"]); ?></td>
    <td><?php echo htmlspecialchars($row["booking_date"]); ?></td>
    Much<td>
    <a href="edit_booking.php?id=<?php echo (int)$row['id']; ?>">
        Edit
    </a>

    |

    <a href="delete_booking.php?id=<?php echo (int)$row['id']; ?>">
        Delete
        <form method="POST" action="delete_booking.php"
onsubmit="return confirm('Are you sure you want to delete this booking?');">
    <input type="hidden" name="id"
           value="<?php echo (int)$row['id']; ?>">
    <button type="submit">Delete</button>
</form>
    </a>
</td>
</tr>

<?php endwhile; ?>

</table>

<?php else: ?>
<td>
    <form method="POST" action="update_status.php">
        <input type="hidden" name="id"
               value="<?php echo (int)$row['id']; ?>">

        <select name="status">
            <?php foreach (
                ["Pending", "Confirmed", "Cancelled"] as $s
            ): ?>
                <option value="<?php echo $s; ?>"
                    <?php echo $row['status'] === $s
                    ? "selected" : ""; ?>>
                    <?php echo $s; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Update Status</button>
    </form>
</td>
<?php endif; ?>

</div>

</body>
</html>
<a href="logout.php" style="
    display:inline-block;
    padding:10px 20px;
    background:white;
    color:#164b35;
    text-decoration:none;
    border-radius:5px;
    font-weight:bold;
">
    Logout
</a>