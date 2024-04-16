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

// Проверяем, был ли передан идентификатор события для удаления
if (!isset($_POST['event_id'])) {
    echo "Event ID not provided";
    exit();
}

// Получаем идентификатор события для удаления
$event_id = $_POST['event_id'];

// Удаляем событие из базы данных
$sql_delete = "DELETE FROM events WHERE id='$event_id'";
if ($conn->query($sql_delete) === TRUE) {
    echo "Event deleted successfully";
    ?>
    <br>
    <a href="admin_panel.php">Back to Admin Panel</a>
<?php
} else {
    echo "Error deleting event: " . $conn->error;
}

// Закрываем соединение с базой данных
$conn->close();
?>
