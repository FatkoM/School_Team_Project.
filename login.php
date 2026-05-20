<?php
session_start();
require_once __DIR__ . '/Config/db_connection.php';

$errors = [];
$email = '';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Моля въведете валиден имейл.';
    }

    if ($password === '') {
        $errors[] = 'Моля въведете парола.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('SELECT id, full_name, password, is_admin FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($userId, $fullName, $hashedPassword, $isAdmin);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $fullName;
                $_SESSION['user_email'] = $email;
                $_SESSION['is_admin'] = (bool) $isAdmin;
                header('Location: index.php');
                exit;
            }
        }

        $errors[] = 'Грешен имейл или парола.';
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход - eBookStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>

    <!-- Navbar -->
    <?php include 'includes/header_auth.php'; ?>

    <main>
        <div class="card login-card">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: #0b5367; font-size: 2rem;">Добре дошли</h2>
                <p class="text-muted">Влезте в своя профил</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="login.php">
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small uppercase" style="letter-spacing: 1px;">ИМЕЙЛ</label>
                    <input type="email" name="email" class="form-control form-control-lg border-2" style="border-radius: 0.7rem;" placeholder="email@example.com" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small uppercase" style="letter-spacing: 1px;">ПАРОЛА</label>
                    <input type="password" name="password" class="form-control form-control-lg border-2" style="border-radius: 0.7rem;" placeholder="••••••••" required>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rem">
                        <label class="form-check-label small" for="rem">Запомни ме</label>
                    </div>
                    <a href="forgotten_password.php" class="small text-decoration-none" style="color: #348096;">Забравена парола?</a>
                </div>

                <button type="submit" class="btn btn-action w-100 shadow-sm">ВЛЕЗ</button>
            </form>

            <div class="text-center mt-4">
                <p class="small mb-0">Нямате акаунт? <a href="registr.php" class="fw-bold text-decoration-none" style="color: #0b5367;">Регистрирайте се</a></p>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
