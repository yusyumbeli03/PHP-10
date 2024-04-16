<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h1>Event Registration</h1>
    <?php 
    session_start();
    require_once "data/db_connection.php";

    // Проверка, если пользователь не авторизован, перенаправляем на страницу входа
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
            // Проверяем, был ли передан идентификатор события через GET
    if (!isset($_GET['event_id'])) {
        echo "Event ID not provided";
        exit();
        
    }
    
    // Получаем идентификатор пользователя из сессии
    $user_id = $_SESSION['user_id'];
    // Получаем идентификатор события из GET-запроса
    $event_id = $_GET['event_id'];

    // Проверяем, уже ли зарегистрирован пользователь на данное событие
    $sql_check = "SELECT * FROM event_records WHERE user_id = $user_id AND event_id = $event_id";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "You are already registered for this event.";
        ?>
        <br>
        <a href="index.php" class="btn btn-primary mt-3">Back to events</a>
        <?php
        exit();
    }

    // Запрос на получение информации о мероприятии
    $sql = "SELECT * FROM events WHERE id = '$event_id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo "Event not found";
        exit();
    }
        // Отображение информации о мероприятии
        $row = $result->fetch_assoc();
        echo "<p>Name: " . $row["event_name"]. " - Price: " . $row["price"]. " - Date: " . $row["date"]. "</p>";
    
        // Форма для подтверждения регистрации
        echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>";
        echo "<input type='hidden' name='event_id' value='$event_id'>";
        echo "<input type='hidden' name='user_id' value='{$_SESSION['user_id']}'>";
        echo "<input type='submit' value='Confirm' class='btn btn-primary'>";
        echo "</form>";
    }


    // Если форма отправлена, добавляем запись о регистрации пользователя на событие
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Получаем идентификатор пользователя из сессии
    $user_id = $_SESSION['user_id'];
    // Получаем идентификатор события из GET-запроса
    $event_id = $_POST['event_id'];
        // Выполняем SQL-запрос INSERT
        $sql_insert = "INSERT INTO event_records (user_id, event_id) VALUES ($user_id, $event_id)";
        if ($conn->query($sql_insert) === TRUE) {
            echo "Registration successful!";
            ?>
            <br>
            <a href="index.php" class="btn btn-primary mt-3">Back to events</a>
            <?php
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    }
    
    // Закрываем соединение с базой данных
    $conn->close();
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
