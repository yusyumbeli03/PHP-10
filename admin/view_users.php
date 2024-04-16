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

// Обработка выбора мероприятия
if (isset($_GET['event_id'])) {
    // Получаем идентификатор мероприятия из запроса
    $event_id = $_GET['event_id'];

    // Получаем информацию о мероприятии
    $sql_event = "SELECT * FROM events WHERE id='$event_id'";
    $result_event = $conn->query($sql_event);

    if ($result_event->num_rows != 1) {
        echo "Event not found";
        exit();
    }

    // Получаем список зарегистрированных пользователей на мероприятие
    $sql_users = "SELECT users.name, users.surname, users.email FROM event_records
            INNER JOIN users ON event_records.user_id = users.id
            WHERE event_records.event_id = '$event_id'";
    $result_users = $conn->query($sql_users);

    // Проверяем, есть ли зарегистрированные пользователи
    if ($result_users->num_rows > 0) {
        $registered_users_exist = true;
    } else {
        $registered_users_exist = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Registrations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">View Registrations</h1>
        <?php if (isset($_GET['event_id'])) { ?>
            <div class="mt-4">
                <h2>Event Information:</h2>
                <?php
                // Выводим информацию о мероприятии
                $row_event = $result_event->fetch_assoc();
                echo "<p>Name: " . $row_event["event_name"]. " - Price: " . $row_event["price"]. " - Date: " . $row_event["date"]. "</p>";

                if ($registered_users_exist) { // Если есть зарегистрированные пользователи
                ?>
                    <h2>Registered Users:</h2>
                    <ul>
                        <?php
                        // Выводим список зарегистрированных пользователей
                        while ($row_user = $result_users->fetch_assoc()) {
                            echo "<li>Name: " . $row_user["name"]. " " . $row_user["surname"]. " - Email: " . $row_user["email"]. "</li>";
                        }
                        ?>
                    </ul>
                <?php } else { // Если зарегистрированных пользователей нет ?>
                    <p>No users are registered for this event.</p>
                <?php } ?>
            </div>
            <a href="admin_panel.php" class="btn btn-secondary mt-3">Back to Admin Panel</a>
        <?php } else { ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="mt-4">
                <div class="mb-3">
                    <label for="event_id" class="form-label">Select Event:</label>
                    <select id="event_id" name="event_id" class="form-select" required>
                        <?php
                        // Запрос для получения списка событий
                        $sql_events = "SELECT * FROM events";
                        $result_events = $conn->query($sql_events);

                        // Вывод списка событий в виде опций
                        while ($row_event = $result_events->fetch_assoc()) {
                            echo "<option value='" . $row_event['id'] . "'>" . $row_event['event_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">View Registrations</button>
            </form>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
