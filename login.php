<?php

session_start();

require_once "data/db_connection.php";

// Проверка, если пользователь уже авторизован, перенаправляем на index.php
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Проверка, если метод запроса POST используется
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверка данных введенных пользователем
    if (isset($_POST['email']) && isset($_POST['password'])) {
        require_once "data/db_connection.php";

        // Получение данных из формы
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Запрос на поиск пользователя в базе данных
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Пользователь найден
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                var_dump($row);
                // Пароль совпадает, авторизация успешна
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role_id'] = $row['role_id'];
                header('Location: index.php');
                exit;
            } else {
                // Пароль не совпадает
                echo "Incorrect password";
            }
        } else {
            // Пользователь не найден
            echo "User not found";
        }

        $conn->close();
    } else {
        // Поля не заполнены
        echo "Please fill all fields";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .login-form {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title text-center">Login</h1>
                        <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
