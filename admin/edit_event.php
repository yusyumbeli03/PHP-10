<?php
session_start();

// Подключение к базе данных
require_once "../data/db_connection.php";

// Проверка, если пользователь не авторизован, перенаправляем на страницу входа
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Проверка, если пользователь не является менеджером, выводим сообщение об ошибке
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if ($row['role_id'] != 2) { // роль менеджера имеет id = 2
        echo "You don't have permission to access this page";
        exit();
    }
} else {
    echo "User not found";
    exit();
}

// Если не был передан идентификатор события для редактирования, выводим форму для выбора события
if (!isset($_GET['event_id'])) {
    // Получаем список всех событий
    $sql_events = "SELECT id, event_name FROM events";
    $result_events = $conn->query($sql_events);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Event to Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Select Event to Edit</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
            <div class="mb-3">
                <label for="event_id" class="form-label">Select Event:</label><br>
                <select id="event_id" name="event_id" class="form-select" required>
                    <?php
                    // Выводим список всех событий в виде опций
                    while ($row_event = $result_events->fetch_assoc()) {
                        echo "<option value='" . $row_event['id'] . "'>" . $row_event['event_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Edit Event</button>
        </form>
        <a href="admin_panel.php" class="btn btn-secondary mt-3">Back to Admin Panel</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php
    exit(); // Завершаем выполнение скрипта после отображения формы выбора события
}
// Обработка отправки формы для обновления информации о событии
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_GET['event_id'];
    $event_name = $_POST['event_name'];
    $price = $_POST['price'];
    $date = $_POST['date'];

    // Обновление информации о событии в базе данных
    $sql_update = "UPDATE events SET event_name='$event_name', price='$price', date='$date' WHERE id='$event_id'";
    if ($conn->query($sql_update) === TRUE) {
        echo "Event updated successfully";
    } else {
        echo "Error updating event: " . $conn->error;
    }
}

// Получаем информацию о событии
$event_id = $_GET['event_id'];
$sql_event = "SELECT * FROM events WHERE id='$event_id'";
$result_event = $conn->query($sql_event);

if ($result_event->num_rows != 1) {
    echo "Event not found";
    exit();
}

// Получаем данные о событии для предзаполнения формы
$row_event = $result_event->fetch_assoc();
$event_name = $row_event['event_name'];
$price = $row_event['price'];
$date = $row_event['date'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Edit Event</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?event_id=' . $event_id; ?>" method="post">
            <div class="mb-3">
                <label for="event_name" class="form-label">Event Name:</label><br>
                <input type="text" id="event_name" name="event_name" class="form-control" value="<?php echo $event_name; ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price:</label><br>
                <input type="number" id="price" name="price" class="form-control" value="<?php echo $price; ?>" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date:</label><br>
                <input type="date" id="date" name="date" class="form-control" value="<?php echo $date; ?>" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary me-3">Update Event</button>
                <form action="delete_event.php" method="post" style="display: inline;">
                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    <button type="submit" class="btn btn-danger">Delete Event</button>
                </form>
            </div>
        </form>
        <a href="admin_panel.php" class="btn btn-secondary mt-3">Back to Admin Panel</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

