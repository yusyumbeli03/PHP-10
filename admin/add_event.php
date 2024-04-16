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
    if ($row['role_id'] != 2) { //роль администратора имеет id = 2
        echo "You don't have permission to access this page";
        exit();
    }
} else {
    echo "User not found";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $name = $_POST['event_name'];
    $price = $_POST['price'];
    $number_seats = $_POST['number_seats'];
    $date = $_POST['date'];

    // Вставка мероприятия в базу данных
    $sql = "INSERT INTO events (event_name, price, number_seats, date) VALUES ('$name', '$price', '$number_seats', '$date')";

    if ($conn->query($sql) === TRUE) {
        header('Location: ../index.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Add Event</h1>
        <!-- Форма для добавления мероприятия -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Event Name:</label>
                <input type="text" id="name" name="event_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="number" id="price" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="number_seats" class="form-label">Number of Seats:</label>
                <input type="number" id="number_seats" name="number_seats" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date:</label>
                <input type="datetime-local" id="date" name="date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Event</button>
        </form>
        <a href="admin_panel.php" class="mt-3 btn btn-secondary">Back to Admin Panel</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
