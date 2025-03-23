<?php
$host = 'localhost';
$dbname = 'myapp';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                header("Location: dashboard.html");
                exit();
            } else {
                header("Location: login.html?error=Incorrect password");
                exit();
            }
        } else {
            header("Location: login.html?error=User not found");
            exit();
        }
    } catch (PDOException $e) {
        die("Error fetching user: " . $e->getMessage());
    }
}
?>