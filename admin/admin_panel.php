<?php
require_once "../data/db_connection.php";
session_start();

// Проверка наличия сеанса администратора
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if ($row['role_id'] != 2) { // Предполагаем, что роль администратора имеет id = 2
        echo "You don't have permission to access this page";
        exit();
    }
} else {
    echo "User not found";
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .logout {
            float: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="mt-3">Admin Panel</h1>
            </div>
            <div class="col text-end">
                <a href="../logout.php" class="btn btn-primary mt-3 logout">Logout</a>
            </div>
        </div>
        <ul class="list-group mt-3">
            <li class="list-group-item"><a href="add_event.php">Add Event</a></li>
            <li class="list-group-item"><a href="edit_event.php">Edit Event</a></li>
            <li class="list-group-item"><a href="view_users.php">View Registrations</a></li>
        </ul>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
