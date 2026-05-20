<?php
session_start();
require_once __DIR__ . '/Config/db_connection.php';

$errors = [];
$successMessage = '';
$fullName = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($fullName === '') {
        $errors[] = 'Моля въведете три имена.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Моля въведете валиден имейл.';
    }

    if ($password === '' || strlen($password) < 6) {
        $errors[] = 'Паролата трябва да има поне 6 символа.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = 'Този имейл вече е регистриран.';
        } else {
            $stmt->close();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $conn->prepare('INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)');
            $insertStmt->bind_param('sss', $fullName, $email, $hashedPassword);

            if ($insertStmt->execute()) {
                $successMessage = 'Регистрацията е успешна. Можете да влезете в профила си.';
                $fullName = '';
                $email = '';
            } else {
                $errors[] = 'Възникна грешка при регистрацията. Моля опитайте по-късно.';
            }

            $insertStmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрация - eBookStore</title>
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
                <h2 class="fw-bold" style="color: #0b5367; font-size: 2rem;">Създаване на акаунт</h2>
                <p class="text-muted">Станете част от нашето общество</p>
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

            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="post" action="registr.php">
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small uppercase">ТРИТЕ ИМЕНА</label>
                    <input type="text" name="full_name" class="form-control form-control-lg border-2" style="border-radius: 0.7rem;" placeholder="Иван Иванов" value="<?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small uppercase">ИМЕЙЛ</label>
                    <input type="email" name="email" class="form-control form-control-lg border-2" style="border-radius: 0.7rem;" placeholder="email@example.com" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary small uppercase">ПАРОЛА</label>
                    <input type="password" name="password" class="form-control form-control-lg border-2" style="border-radius: 0.7rem;" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-action w-100 shadow-sm">РЕГИСТРИРАЙ СЕ</button>
            </form>

            <div class="text-center mt-4">
                <p class="small mb-0">Вече имате акаунт? <a href="login.php" class="fw-bold text-decoration-none" style="color: #0b5367;">Влезте тук</a></p>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
