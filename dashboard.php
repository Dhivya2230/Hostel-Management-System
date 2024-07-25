<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM rooms";
$rooms = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id'];
    $booking_date = date('Y-m-d');

    $sql = "INSERT INTO bookings (user_id, room_id, booking_date) VALUES ('$user_id', '$room_id', '$booking_date')";
    if ($conn->query($sql) === TRUE) {
        $sql = "UPDATE rooms SET status='booked' WHERE id='$room_id'";
        $conn->query($sql);
        echo "Room booked successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to the Hostel Management System</h1>
    <h2>Available Rooms</h2>
    <form method="POST" action="dashboard.php">
        <select name="room_id" required>
            <?php while ($room = $rooms->fetch_assoc()): ?>
                <?php if ($room['status'] == 'available'): ?>
                    <option value="<?php echo $room['id']; ?>"><?php echo $room['room_number'] . ' - ' . $room['room_type']; ?></option>
                <?php endif; ?>
            <?php endwhile; ?>
        </select>
        <input type="submit" value="Book Room">
    </form>
    <a href="logout.php">Logout</a>
</body>
</html>
