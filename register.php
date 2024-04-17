<?php
require_once "data/db_connection.php";

// Проверка, если метод запроса POST используется
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы регистрации
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Хеширование пароля
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Вставка данных в базу данных
    $sql = "INSERT INTO users (name, surname, email, password, role_id) VALUES ('$name', '$surname', '$email', '$hashed_password', 1)";
    if ($conn->query($sql) === TRUE) {
        header('Location: login.php');
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .form-control {
            width: 300px;
        }
        .btn-register {
            background-color: green;
            color: white;
        }
        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container-fluid center">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title text-center">Registration</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="mx-auto">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="surname" class="form-label">Surname:</label>
                        <input type="text" id="surname" name="surname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-register">Register</button>
                    <a href="login.php" class="btn btn-register">Login</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
